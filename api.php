<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once 'database.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

// --- MENANGANI METODE GET (AMBIL DATA) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'getProducts') {
        $result = $mysqli->query("SELECT * FROM products ORDER BY id DESC");
        $products = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach($products as &$p) {
            $p['price'] = (int)$p['price'];
            $p['cost'] = (int)$p['cost'];
        }
        echo json_encode($products);
        
    } elseif ($action === 'getTransactions') {
        $result = $mysqli->query("SELECT * FROM transactions ORDER BY date DESC");
        $transactions = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($transactions as &$trx) {
            $stmtItems = $mysqli->prepare("SELECT * FROM transaction_items WHERE transaction_id = ?");
            $stmtItems->bind_param("s", $trx['id']);
            $stmtItems->execute();
            $resultItems = $stmtItems->get_result();
            $items = $resultItems->fetch_all(MYSQLI_ASSOC);
            
            $trx['items'] = [];
            foreach ($items as $item) {
                $trx['items'][] = [
                    'qty' => (int)$item['quantity'],
                    'product' => [
                        'name' => $item['product_name'],
                        'price' => (int)$item['price']
                    ]
                ];
            }
            
            $trx['revenue'] = (int)$trx['total_revenue'];
            $trx['cost'] = (int)$trx['total_cost'];
            $trx['profit'] = (int)$trx['profit'];
            $trx['cash'] = (int)$trx['cash_received'];
            $trx['change'] = (int)$trx['change_amount'];
            $trx['date'] = date('d/m/Y, H:i:s', strtotime($trx['date']));
        }
        echo json_encode($transactions);
    } elseif ($action === 'getCapitalData') {
        $capitalResult = $mysqli->query("SELECT * FROM capital_records ORDER BY date DESC");
        $capitals = $capitalResult->fetch_all(MYSQLI_ASSOC);
        foreach($capitals as &$c) {
            $c['amount'] = (int)$c['amount'];
        }

        $monthlyResult = $mysqli->query("SELECT * FROM monthly_records ORDER BY date DESC");
        $monthlies = $monthlyResult->fetch_all(MYSQLI_ASSOC);
        foreach($monthlies as &$m) {
            $m['amount'] = (int)$m['amount'];
        }

        echo json_encode([
            'capitals' => $capitals,
            'monthlies' => $monthlies
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Action tidak valid."]);
    }
} 

// --- MENANGANI METODE POST (SIMPAN / HAPUS DATA) ---
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if ($action === 'addProduct') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = (int)$_POST['price'];
        $cost = (int)$_POST['cost'];
        $icon = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $icon = $targetPath;
            }
        }
        
        if (empty($icon)) {
            $icon = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80';
        }

        $stmt = $mysqli->prepare("INSERT INTO products (id, name, icon, price, cost) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $id, $name, $icon, $price, $cost);
        $stmt->execute();
        echo json_encode(["status" => "success"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    
    if ($action === 'deleteProduct') {
        $stmtImg = $mysqli->prepare("SELECT icon FROM products WHERE id = ?");
        $stmtImg->bind_param("s", $data['id']);
        $stmtImg->execute();
        $result = $stmtImg->get_result();
        $row = $result->fetch_assoc();
        $img = $row ? $row['icon'] : null;
        
        if ($img && strpos($img, 'uploads/') === 0 && file_exists($img)) {
            unlink($img);
        }

        $stmt = $mysqli->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("s", $data['id']);
        $stmt->execute();
        echo json_encode(["status" => "success"]);
        
    } elseif ($action === 'saveTransaction') {
        try {
            $mysqli->begin_transaction();
            
            $mysqlDate = date('Y-m-d H:i:s'); 
            
            $stmt = $mysqli->prepare("INSERT INTO transactions (id, date, total_revenue, total_cost, profit, cash_received, change_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiiiii", 
                $data['id'], 
                $mysqlDate, 
                $data['revenue'], 
                $data['cost'], 
                $data['profit'], 
                $data['cash'], 
                $data['change']
            );
            $stmt->execute();
            
            $stmtItem = $mysqli->prepare("INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($data['items'] as $item) {
                $stmtItem->bind_param("ssii",
                    $data['id'],
                    $item['product']['name'],
                    $item['qty'],
                    $item['product']['price']
                );
                $stmtItem->execute();
            }
            
            $mysqli->commit();
            
            $data['date'] = date('d/m/Y, H:i:s');
            echo json_encode(["status" => "success", "transaction" => $data]);
            
        } catch(Exception $e) {
            $mysqli->rollback();
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        
    } elseif ($action === 'clearTransactions') {
        $mysqli->query("DELETE FROM transactions");
        echo json_encode(["status" => "success"]);
    } elseif ($action === 'importDummy') {
        try {
            $sqlFile = 'data_demo.sql';
            if (!file_exists($sqlFile)) {
                throw new Exception("Berkas '$sqlFile' tidak ditemukan di direktori proyek.");
            }

            $sqlContent = file_get_contents($sqlFile);
            
            // Hilangkan komentar SQL (-- dan /* ... */)
            $sqlContent = preg_replace('/--.*\n/', '', $sqlContent);
            $sqlContent = preg_replace('/(\/\*([\s\S]*?)\*\/)/', '', $sqlContent);
            
            // Pisahkan query berdasarkan titik koma (;)
            $queries = explode(';', $sqlContent);
            
            $mysqli->begin_transaction();
            
            foreach ($queries as $query) {
                $trimmedQuery = trim($query);
                if (empty($trimmedQuery)) {
                    continue;
                }
                
                // Lewati query USE database karena koneksi sudah established di database.php
                if (stripos($trimmedQuery, 'USE ') === 0) {
                    continue;
                }

                if (!$mysqli->query($trimmedQuery)) {
                    throw new Exception("Gagal mengeksekusi query: " . $mysqli->error . "\nQuery: " . $trimmedQuery);
                }
            }
            
            $mysqli->commit();
            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            if (isset($mysqli) && $mysqli->in_transaction) {
                $mysqli->rollback();
            }
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } elseif ($action === 'addCapital') {
        $stmt = $mysqli->prepare("INSERT INTO capital_records (description, amount, date) VALUES (?, ?, NOW())");
        $stmt->bind_param("si", $data['description'], $data['amount']);
        $stmt->execute();
        echo json_encode(["status" => "success"]);

    } elseif ($action === 'addMonthlyRecord') {
        $month = isset($data['month']) ? $data['month'] : date('Y-m');
        $stmt = $mysqli->prepare("INSERT INTO monthly_records (month, type, description, amount, date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssi", $month, $data['type'], $data['description'], $data['amount']);
        $stmt->execute();
        echo json_encode(["status" => "success"]);

    } elseif ($action === 'deleteCapital') {
        $stmt = $mysqli->prepare("DELETE FROM capital_records WHERE id = ?");
        $stmt->bind_param("i", $data['id']);
        $stmt->execute();
        echo json_encode(["status" => "success"]);

    } elseif ($action === 'deleteMonthlyRecord') {
        $stmt = $mysqli->prepare("DELETE FROM monthly_records WHERE id = ?");
        $stmt->bind_param("i", $data['id']);
        $stmt->execute();
        echo json_encode(["status" => "success"]);

    } else {
        echo json_encode(["status" => "error", "message" => "POST action tidak valid."]);
    }
}
?>
