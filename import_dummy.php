<?php
// ====================================================================
// KASIR PINTAR - AUTOMATIC DUMMY DATA IMPORTER FOR DEMO
// Premium UI styled with Rich Aesthetics to wow the user/audience.
// ====================================================================

require_once 'database.php';

$success = false;
$error = '';
$importedStats = [
    'products' => 0,
    'capitals' => 0,
    'monthlies' => 0,
    'transactions' => 0,
    'items' => 0
];

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
        
        // Hitung statistik impor berdasarkan jenis perintah
        if (stripos($trimmedQuery, 'INSERT INTO products') !== false) {
            $importedStats['products'] += $mysqli->affected_rows;
        } elseif (stripos($trimmedQuery, 'INSERT INTO capital_records') !== false) {
            $importedStats['capitals'] += $mysqli->affected_rows;
        } elseif (stripos($trimmedQuery, 'INSERT INTO monthly_records') !== false) {
            $importedStats['monthlies'] += $mysqli->affected_rows;
        } elseif (stripos($trimmedQuery, 'INSERT INTO transactions') !== false) {
            $importedStats['transactions'] += $mysqli->affected_rows;
        } elseif (stripos($trimmedQuery, 'INSERT INTO transaction_items') !== false) {
            $importedStats['items'] += $mysqli->affected_rows;
        }
    }
    
    $mysqli->commit();
    $success = true;
} catch (Exception $e) {
    if (isset($mysqli) && $mysqli->in_transaction) {
        $mysqli->rollback();
    }
    $success = false;
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Pintar - Pengimpor Data Demo</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-border: rgba(255, 255, 255, 0.08);
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.3);
            --success: #10b981;
            --success-glow: rgba(16, 185, 129, 0.2);
            --danger: #ef4444;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            backdrop-filter: blur(16px);
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            text-align: center;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            position: relative;
        }

        .container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, var(--primary), var(--success));
            border-radius: 26px;
            z-index: -1;
            opacity: 0.15;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
            font-weight: bold;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
        }

        .icon-success {
            background: var(--success);
            box-shadow: 0 0 20px var(--success-glow);
        }

        .icon-danger {
            background: var(--danger);
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 15px;
            color: var(--text-muted);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 32px;
            text-align: left;
        }

        .stat-card {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 16px;
            padding: 16px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.1);
            background: rgba(15, 23, 42, 0.6);
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 4px;
            display: block;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-main);
        }

        .stat-success {
            color: var(--success);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: white;
            font-weight: 600;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.2s ease;
            box-shadow: 0 8px 16px var(--primary-glow);
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.4);
        }

        .btn:active {
            transform: translateY(1px);
        }

        .error-box {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border-radius: 16px;
            padding: 20px;
            font-size: 14px;
            text-align: left;
            margin-bottom: 32px;
            max-height: 200px;
            overflow-y: auto;
            white-space: pre-wrap;
            line-height: 1.5;
        }

        .footer {
            margin-top: 24px;
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <div class="container">
        <?php if ($success): ?>
            <div class="icon-wrapper icon-success">✓</div>
            <h1>Impor Data Berhasil!</h1>
            <p class="subtitle">Seluruh data demo Kasir Pintar telah berhasil diunggah ke database MySQL. Sistem Anda kini siap dipresentasikan di depan audiens.</p>
            
            <div class="stats-grid">
                <div class="stat-card" style="grid-column: span 2;">
                    <span class="stat-label">Investasi & Modal Awal</span>
                    <span class="stat-value stat-success">Rp 30.000.000</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Daftar Menu Makanan</span>
                    <span class="stat-value"><?php echo $importedStats['products']; ?> Menu</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Modal Awal Tercatat</span>
                    <span class="stat-value"><?php echo $importedStats['capitals']; ?> Aset</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Riwayat Penjualan</span>
                    <span class="stat-value"><?php echo $importedStats['transactions']; ?> Transaksi</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Item Terjual Detail</span>
                    <span class="stat-value"><?php echo $importedStats['items']; ?> Item</span>
                </div>
                <div class="stat-card" style="grid-column: span 2;">
                    <span class="stat-label">Pemasukan & Pengeluaran Bulanan (April-Mei)</span>
                    <span class="stat-value"><?php echo $importedStats['monthlies']; ?> Catatan</span>
                </div>
            </div>
            
            <a href="index.php" class="btn">Buka Aplikasi Kasir Pintar</a>
        <?php else: ?>
            <div class="icon-wrapper icon-danger">✕</div>
            <h1>Gagal Mengimpor Data</h1>
            <p class="subtitle">Terjadi kesalahan teknis saat mengunggah data dummy ke server database MySQL Anda.</p>
            
            <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
            
            <button onclick="window.location.reload();" class="btn" style="background: rgba(255, 255, 255, 0.1); box-shadow: none;">Coba Impor Ulang</button>
        <?php endif; ?>
        
        <div class="footer">
            Kasir Pintar POS • Sistem Akuntansi UMKM Premium
        </div>
    </div>

</body>
</html>
