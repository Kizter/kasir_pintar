<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Pintar (PHP & MySQL)</title>
    <!-- Modern Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0071e4;
            --primary-dark: #005bb5;
            --secondary: #b5e0ea;
            --dark: #0f4c81;
            --bg: #f4f7f6;
            --surface: #ffffff;
            --text: #1a1a1a;
            --text-light: #666666;
            --border: #e0e0e0;
            --danger: #e74c3c;
            --success: #2ecc71;
            --warning: #f1c40f;
            --radius: 12px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --sidebar-width: 250px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit', sans-serif; background-color: var(--bg); color: var(--text); display: flex; height: 100vh; overflow: hidden; }

        /* --- SIDEBAR NAV --- */
        .sidebar { width: var(--sidebar-width); background: var(--dark); color: white; display: flex; flex-direction: column; padding: 24px 0; box-shadow: 2px 0 10px rgba(0,0,0,0.1); z-index: 20; }
        .brand { font-size: 1.5rem; font-weight: 700; padding: 0 24px; margin-bottom: 30px; color: var(--secondary); display: flex; align-items: center; gap: 10px; }
        .nav-items { display: flex; flex-direction: column; gap: 8px; padding: 0 16px; }
        .nav-btn { background: transparent; color: rgba(255,255,255,0.7); border: none; padding: 14px 20px; border-radius: 8px; text-align: left; font-size: 1.05rem; font-weight: 600; font-family: inherit; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 12px; }
        .nav-btn:hover { background: rgba(255,255,255,0.1); color: white; }
        .nav-btn.active { background: var(--primary); color: white; box-shadow: 0 4px 10px rgba(0,113,228,0.4); }

        /* --- MAIN CONTENT --- */
        .main-content { flex: 1; position: relative; overflow: hidden; display: flex; flex-direction: column; }
        .view-section { display: none; width: 100%; height: 100%; animation: fadeIn 0.3s ease; }
        .view-section.active { display: flex; flex-direction: column; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .page-header { padding: 24px; background: var(--surface); border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .page-title h1 { font-size: 1.8rem; color: var(--dark); }
        .page-title p { color: var(--text-light); font-size: 0.95rem; }

        /* --- KASIR VIEW --- */
        .pos-container { display: flex; flex: 1; overflow: hidden; }
        .products-area { flex: 2; padding: 24px; overflow-y: auto; }
        .cart-area { flex: 1; min-width: 350px; background: var(--surface); border-left: 1px solid var(--border); display: flex; flex-direction: column; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; }
        .product-card { background: var(--surface); border-radius: var(--radius); padding: 16px; text-align: center; cursor: pointer; border: 2px solid transparent; box-shadow: var(--shadow); transition: all 0.2s ease; }
        .product-card:hover { transform: translateY(-4px); border-color: var(--primary); }
        .product-img { width: 100%; height: 130px; object-fit: cover; border-radius: 8px; margin-bottom: 12px; }
        .product-name { font-weight: 700; font-size: 1.1rem; margin-bottom: 4px; }
        .product-price { color: var(--primary); font-weight: 600; }

        /* Cart List */
        .cart-list { flex: 1; overflow-y: auto; padding: 24px; }
        .cart-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed var(--border); }
        .item-info h4 { font-size: 1rem; margin-bottom: 4px; }
        .item-info p { font-size: 0.85rem; color: var(--text-light); }
        .stepper { display: flex; align-items: center; background: var(--bg); border-radius: 20px; overflow: hidden; }
        .stepper button { background: none; border: none; padding: 6px 12px; cursor: pointer; font-weight: bold; }
        .stepper button:hover { background: var(--secondary); color: var(--primary); }
        .stepper span { font-size: 0.9rem; font-weight: 600; min-width: 20px; text-align: center; }
        .item-total { font-weight: 700; color: var(--dark); }

        /* Checkout Box */
        .checkout-box { padding: 24px; border-top: 1px solid var(--border); background: var(--bg); }
        .total-row { display: flex; justify-content: space-between; font-weight: 700; font-size: 1.4rem; color: var(--dark); margin-bottom: 16px; }
        .input-group { margin-bottom: 16px; }
        .input-group label { display: block; font-size: 0.9rem; font-weight: 600; color: var(--text-light); margin-bottom: 8px; }
        .input-group input { width: 100%; padding: 12px; border: 2px solid var(--border); border-radius: 8px; font-size: 1.1rem; font-family: inherit; }
        .input-group input:focus { border-color: var(--primary); outline: none; }
        .btn-pay { width: 100%; padding: 16px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: 0.2s; }
        .btn-pay:hover { background: var(--dark); }

        /* --- MANAJEMEN MENU VIEW --- */
        .menu-manager-area { padding: 24px; overflow-y: auto; flex: 1; }
        .form-card { background: var(--surface); padding: 24px; border-radius: var(--radius); box-shadow: var(--shadow); margin-bottom: 24px; max-width: 800px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
        .btn-add { background: var(--success); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn-add:hover { background: #27ae60; }
        
        .menu-table-wrapper { background: var(--surface); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px; text-align: left; border-bottom: 1px solid var(--border); }
        th { background: var(--bg); font-weight: 600; color: var(--text-light); }
        .btn-del { background: var(--danger); color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;}

        /* --- ANALISIS VIEW --- */
        .analytics-area { padding: 24px; overflow-y: auto; flex: 1; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 24px; }
        .stat-card { background: var(--surface); padding: 24px; border-radius: var(--radius); box-shadow: var(--shadow); border-left: 4px solid var(--primary); }
        .stat-title { font-size: 1rem; color: var(--text-light); margin-bottom: 8px; font-weight: 600; }
        .stat-value { font-size: 2rem; font-weight: 700; color: var(--dark); }
        .stat-value.profit { color: var(--success); }
        .btn-export { background: var(--success); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;}
        .btn-export:hover { background: #27ae60; }

        /* --- FILTER BAR --- */
        .filter-bar { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
        .filter-bar label { font-weight: 600; font-size: 0.9rem; color: var(--text-light); }
        .filter-bar select { padding: 10px 14px; border: 2px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.95rem; background: var(--surface); color: var(--text); cursor: pointer; min-width: 200px; }
        .filter-bar select:focus { border-color: var(--primary); outline: none; }

        /* --- RIWAYAT TRANSAKSI TABLE --- */
        .trx-detail-toggle { background: none; border: 1px solid var(--border); padding: 4px 10px; border-radius: 4px; cursor: pointer; font-size: 0.75rem; font-family: inherit; color: var(--primary); font-weight: 600; }
        .trx-detail-toggle:hover { background: var(--primary); color: white; }
        .trx-detail-row td { padding: 0; }
        .trx-detail-content { padding: 12px 24px; background: var(--bg); font-size: 0.85rem; }
        .trx-detail-content .detail-item { display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed var(--border); }
        .trx-detail-content .detail-item:last-child { border-bottom: none; }

        /* --- BALIK MODAL VIEW --- */
        .bep-area { padding: 24px; overflow-y: auto; flex: 1; }

        /* Estimasi Card (Full-width highlight) */
        .bep-hero { background: linear-gradient(135deg, var(--dark) 0%, #1a6cb5 100%); color: white; padding: 28px 32px; border-radius: var(--radius); box-shadow: var(--shadow); margin-bottom: 24px; }
        .bep-hero .bep-hero-label { font-size: 0.9rem; opacity: 0.8; font-weight: 600; margin-bottom: 4px; }
        .bep-hero .bep-hero-value { font-size: 2.2rem; font-weight: 700; margin-bottom: 16px; }
        .bep-hero-stats { display: flex; gap: 32px; flex-wrap: wrap; margin-bottom: 20px; }
        .bep-hero-stat { min-width: 120px; }
        .bep-hero-stat .hs-label { font-size: 0.8rem; opacity: 0.7; }
        .bep-hero-stat .hs-value { font-size: 1.3rem; font-weight: 700; }
        .bep-progress-bar { width: 100%; height: 12px; background: rgba(255,255,255,0.2); border-radius: 6px; overflow: hidden; }
        .bep-progress-fill { height: 100%; background: linear-gradient(90deg, var(--warning), var(--success)); border-radius: 6px; transition: width 0.8s cubic-bezier(0.4,0,0.2,1); }
        .bep-progress-meta { display: flex; justify-content: space-between; margin-top: 8px; font-size: 0.8rem; opacity: 0.7; }

        /* Kolom form + riwayat */
        .bep-columns { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .bep-col-card { background: var(--surface); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; display: flex; flex-direction: column; }
        .bep-col-header { padding: 16px 20px; font-weight: 700; font-size: 1rem; color: white; }
        .bep-col-header.capital { background: var(--dark); }
        .bep-col-header.income { background: var(--success); }
        .bep-col-header.expense { background: var(--danger); }
        .bep-col-header .col-total { font-size: 1.3rem; margin-top: 4px; }
        .bep-col-form { padding: 16px 20px; border-bottom: 1px solid var(--border); }
        .bep-col-form .input-group { margin-bottom: 8px; }
        .bep-col-form .input-group label { font-size: 0.8rem; margin-bottom: 4px; }
        .bep-col-form .input-group input { padding: 10px; font-size: 0.95rem; }
        .bep-col-form .btn-add { width: 100%; padding: 10px; font-size: 0.85rem; font-family: inherit; margin-top: 4px; }
        .bep-col-list { flex: 1; padding: 12px 20px; overflow-y: auto; max-height: 280px; }
        .bep-col-list .list-title { font-size: 0.8rem; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
        .record-list { display: flex; flex-direction: column; gap: 6px; }
        .record-item { display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: var(--bg); border-radius: 6px; font-size: 0.85rem; transition: background 0.15s; }
        .record-item:hover { background: var(--border); }
        .record-item .rec-desc { font-weight: 600; margin-bottom: 2px; }
        .record-item .rec-amount { font-weight: 700; white-space: nowrap; }
        .record-item .rec-amount.income { color: var(--success); }
        .record-item .rec-amount.expense { color: var(--danger); }
        .record-item .rec-date { font-size: 0.7rem; color: var(--text-light); }
        .record-item .btn-del-sm { background: transparent; border: 1px solid var(--danger); color: var(--danger); padding: 2px 8px; border-radius: 4px; cursor: pointer; font-size: 0.7rem; transition: all 0.15s; flex-shrink: 0; }
        .record-item .btn-del-sm:hover { background: var(--danger); color: white; }

        /* Modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; opacity: 0; pointer-events: none; transition: 0.3s; z-index: 100;}
        .modal-overlay.active { opacity: 1; pointer-events: auto; }
        .modal-content { background: white; padding: 30px; border-radius: var(--radius); width: 90%; max-width: 400px; text-align: center; transform: scale(0.95); transition: 0.3s; }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .btn-close { background: var(--bg); border: 1px solid var(--border); padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; margin-top: 10px;}

        /* ==========================================
           RESPONSIVE DESIGN (MOBILE OPTIMIZATION)
           ========================================== */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            
            /* 1. Sidebar becomes Bottom Navigation */
            .sidebar { 
                width: 100%; 
                flex-direction: row; 
                padding: 0; 
                order: 2; /* Pindah ke paling bawah layar */
                box-shadow: 0 -4px 15px rgba(0,0,0,0.05); 
                justify-content: space-around;
                background: var(--surface);
                z-index: 50;
            }
            .brand { display: none; /* Sembunyikan judul di HP untuk hemat ruang */ }
            .nav-items { 
                flex-direction: row; 
                width: 100%; 
                padding: 0; 
                justify-content: space-around; 
                gap: 0;
            }
            .nav-btn { 
                flex-direction: column; 
                padding: 12px 4px; 
                font-size: 0.75rem; 
                border-radius: 0; 
                color: var(--text-light);
                gap: 6px;
                text-align: center;
                flex: 1;
            }
            .nav-btn:hover { background: var(--bg); color: var(--primary); }
            .nav-btn.active { 
                background: transparent; 
                color: var(--primary); 
                box-shadow: none;
                border-top: 3px solid var(--primary);
                font-weight: 700;
            }

            /* 2. Main Content adjustments */
            .main-content { order: 1; flex: 1; }
            .page-header { padding: 16px; flex-direction: column; gap: 12px; align-items: flex-start; }
            
            /* 3. Kasir View (Stacked) */
            .pos-container { flex-direction: column; overflow: hidden; }
            .products-area { flex: 1; min-height: 0; height: auto; padding: 12px; }
            .products-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; }
            .product-img { height: 100px; }
            .cart-area { flex: 1; min-height: 0; height: auto; min-width: 100%; border-left: none; border-top: 2px solid var(--border); }
            .checkout-box { padding: 16px; box-shadow: 0 -4px 10px rgba(0,0,0,0.05); }

            /* 4. Menu Management & Analytics */
            .menu-manager-area, .analytics-area { padding: 16px; }
            .form-grid { grid-template-columns: 1fr; gap: 12px; } /* Jadi 1 kolom full */
            .stats-grid { grid-template-columns: 1fr; gap: 16px; }

            /* 5. Make tables scrollable horizontally */
            .menu-table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            table { min-width: 600px; }

            /* 6. Balik Modal responsive */
            .bep-columns { grid-template-columns: 1fr; }
            .bep-hero-value { font-size: 1.6rem; }
            .bep-hero-stats { gap: 16px; }
            .bep-hero-stat .hs-value { font-size: 1.1rem; }
        }

        @media (max-width: 480px) {
            .bep-hero { padding: 20px; }
            .bep-hero-value { font-size: 1.3rem; }
            .bep-hero-stats { flex-direction: column; gap: 10px; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <nav class="sidebar">
        <div class="brand">Kasir Pintar</div>
        <div class="nav-items">
            <button class="nav-btn active" onclick="switchView('pos', this)">Kasir</button>
            <button class="nav-btn" onclick="switchView('menu', this)">Manajemen Menu</button>
            <button class="nav-btn" onclick="switchView('analytics', this)">Analitik Bisnis</button>
            <button class="nav-btn" onclick="switchView('bep', this)">kalkulator Balik Modal</button>
        </div>
    </nav>

    <main class="main-content">
        <!-- VIEW 1: KASIR -->
        <section id="view-pos" class="view-section active">
            <div class="pos-container">
                <div class="products-area">
                    <div class="products-grid" id="pos-products">
                        <div style="color:var(--text-light); grid-column: 1 / -1;">Memuat menu dari database...</div>
                    </div>
                </div>
                <div class="cart-area">
                    <div class="page-header" style="padding: 16px 24px;">
                        <h2>Keranjang</h2>
                        <span id="cart-count" style="background: var(--secondary); padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">0 item</span>
                    </div>
                    <div class="cart-list" id="cart-list"></div>
                    <div class="checkout-box">
                        <div class="total-row">
                            <span>Total</span>
                            <span id="pos-total">Rp 0</span>
                        </div>
                        <div class="input-group">
                            <label>Uang Tunai (Rp)</label>
                            <input type="number" id="pos-cash" placeholder="Contoh: 50000">
                        </div>
                        <button class="btn-pay" onclick="processCheckout()">Proses Pembayaran</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 2: MANAJEMEN MENU -->
        <section id="view-menu" class="view-section">
            <div class="page-header">
                <div class="page-title">
                    <h1>Manajemen Menu</h1>
                    <p>Atur daftar produk yang tersimpan di Database MySQL.</p>
                </div>
            </div>
            <div class="menu-manager-area">
                <div class="form-card">
                    <h3 style="margin-bottom: 16px; color: var(--dark);">Tambah Menu Baru</h3>
                    <div class="form-grid">
                        <div class="input-group">
                            <label>Nama Menu</label>
                            <input type="text" id="menu-name" placeholder="Misal: Es Kopi Susu">
                        </div>
                        <div class="input-group">
                            <label>Unggah Gambar</label>
                            <input type="file" id="menu-image" accept="image/png, image/jpeg, image/jpg, image/webp" style="padding: 9px;">
                        </div>
                        <div class="input-group">
                            <label>Harga Jual (Rp)</label>
                            <input type="number" id="menu-price" placeholder="Harga untuk pelanggan">
                        </div>
                        <div class="input-group">
                            <label>Harga Modal (Rp) <span style="color:var(--danger); font-size:0.8rem;">*Untuk hitung untung</span></label>
                            <input type="number" id="menu-cost" placeholder="Modal belanja">
                        </div>
                    </div>
                    <button class="btn-add" onclick="addNewProduct()">+ Simpan Menu ke DB</button>
                </div>

                <div class="menu-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Menu</th>
                                <th>Harga Jual</th>
                                <th>Harga Modal</th>
                                <th>Laba/Item</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="menu-table-body">
                            <tr><td colspan="6" style="text-align:center;">Memuat dari database...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- VIEW 3: ANALISIS BISNIS -->
        <section id="view-analytics" class="view-section">
            <div class="page-header">
                <div class="page-title">
                    <h1>Analitik Bisnis</h1>
                    <p>Pantau omset, keuntungan bersih, dan produk terlaris Anda.</p>
                </div>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <button class="btn-export" style="background: var(--bg); color: var(--primary); border: 1px solid var(--primary);" onclick="importDummyData()">Impor Data Demo</button>
                    <button class="btn-export" style="background: var(--bg); color: var(--danger); border: 1px solid var(--danger);" onclick="resetAnalytics()">Hapus Riwayat DB</button>
                    <button class="btn-export" onclick="exportToExcel()">Export ke Excel (.csv)</button>
                </div>
            </div>
            <div class="analytics-area">
                <!-- Filter Bulan -->
                <div class="filter-bar">
                    <label for="filter-month">Filter Bulan:</label>
                    <select id="filter-month" onchange="renderAnalytics()">
                        <option value="all">Semua Bulan</option>
                    </select>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-title">Total Transaksi</div>
                        <div class="stat-value" id="stat-trx-count">0</div>
                    </div>
                    <div class="stat-card" style="border-left-color: var(--secondary);">
                        <div class="stat-title">Total Omset (Pendapatan Kotor)</div>
                        <div class="stat-value" id="stat-omset">Rp 0</div>
                    </div>
                    <div class="stat-card" style="border-left-color: var(--success);">
                        <div class="stat-title">Laba Bersih (Keuntungan)</div>
                        <div class="stat-value profit" id="stat-profit">Rp 0</div>
                    </div>
                </div>

                <div class="form-card" style="max-width: 100%;">
                    <h3 style="margin-bottom: 16px; color: var(--dark);">Produk Terlaris (Best Sellers)</h3>
                    <div class="menu-table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah Terjual</th>
                                    <th>Total Disumbangkan (Omset)</th>
                                </tr>
                            </thead>
                            <tbody id="bestseller-body"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Riwayat Transaksi -->
                <div class="form-card" style="max-width: 100%;">
                    <h3 style="margin-bottom: 16px; color: var(--dark);">Riwayat Transaksi</h3>
                    <div class="menu-table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Waktu</th>
                                    <th>Total Belanja</th>
                                    <th>Modal</th>
                                    <th>Laba</th>
                                    <th>Bayar</th>
                                    <th>Kembali</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody id="trx-history-body">
                                <tr><td colspan="8" style="text-align:center;">Memuat...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIEW 4: KALKULATOR BALIK MODAL -->
        <section id="view-bep" class="view-section">
            <div class="page-header">
                <div class="page-title">
                    <h1>Kalkulator Balik Modal</h1>
                    <p>Catat modal awal, pemasukan, dan pengeluaran bulanan untuk estimasi titik impas (BEP).</p>
                </div>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <button class="btn-export" style="background: var(--bg); color: var(--primary); border: 1px solid var(--primary);" onclick="importDummyData()">Impor Data Demo</button>
                </div>
            </div>
            <div class="bep-area">
                <!-- Filter Bulan BEP -->
                <div class="filter-bar">
                    <label for="filter-bep-month">Pilih Bulan Analisis:</label>
                    <select id="filter-bep-month" onchange="renderBEP()">
                        <!-- Akan diisi secara dinamis -->
                    </select>
                </div>

                <!-- Hero Estimasi -->
                <div class="bep-hero">
                    <div class="bep-hero-label">Estimasi Balik Modal</div>
                    <div class="bep-hero-value" id="bep-estimation">Belum dapat dihitung</div>
                    <div class="bep-hero-stats">
                        <div class="bep-hero-stat">
                            <div class="hs-label">Modal Awal</div>
                            <div class="hs-value" id="bep-total-modal">Rp 0</div>
                        </div>
                        <div class="bep-hero-stat">
                            <div class="hs-label" id="bep-income-label">Pemasukan Bulan Ini</div>
                            <div class="hs-value" id="bep-income">Rp 0</div>
                        </div>
                        <div class="bep-hero-stat">
                            <div class="hs-label" id="bep-expense-label">Pengeluaran Bulan Ini</div>
                            <div class="hs-value" id="bep-expense">Rp 0</div>
                        </div>
                        <div class="bep-hero-stat">
                            <div class="hs-label" id="bep-profit-label">Profit Bersih / Bulan</div>
                            <div class="hs-value" id="bep-profit">Rp 0</div>
                        </div>
                    </div>
                    <div class="bep-progress-bar">
                        <div class="bep-progress-fill" id="bep-progress" style="width: 0%;"></div>
                    </div>
                    <div class="bep-progress-meta">
                        <span id="bep-recovered">Terkumpul: Rp 0</span>
                        <span id="bep-pct">0%</span>
                        <span id="bep-target">Target: Rp 0</span>
                    </div>
                </div>

                <!-- 3 Kolom: Form + Riwayat per kategori -->
                <div class="bep-columns">
                    <!-- Kolom Modal Awal -->
                    <div class="bep-col-card">
                        <div class="bep-col-header capital">
                            <div>Modal Awal</div>
                            <div class="col-total" id="col-total-capital">Rp 0</div>
                        </div>
                        <div class="bep-col-form">
                            <div class="input-group">
                                <label>Keterangan</label>
                                <input type="text" id="cap-desc" placeholder="Misal: Sewa Tempat">
                            </div>
                            <div class="input-group">
                                <label>Jumlah (Rp)</label>
                                <input type="number" id="cap-amount" placeholder="5000000">
                            </div>
                            <button class="btn-add" onclick="addCapitalRecord()">+ Catat Modal</button>
                        </div>
                        <div class="bep-col-list">
                            <div class="list-title">Riwayat Tercatat</div>
                            <div class="record-list" id="capital-list">Memuat...</div>
                        </div>
                    </div>

                    <!-- Kolom Pemasukan -->
                    <div class="bep-col-card">
                        <div class="bep-col-header income">
                            <div>Pemasukan Bulanan</div>
                            <div class="col-total" id="col-total-income">Rp 0</div>
                        </div>
                        <div class="bep-col-form">
                            <div class="input-group">
                                <label>Keterangan</label>
                                <input type="text" id="inc-desc" placeholder="Misal: Pendapatan Jualan">
                            </div>
                            <div class="input-group">
                                <label>Jumlah (Rp)</label>
                                <input type="number" id="inc-amount" placeholder="3000000">
                            </div>
                            <button class="btn-add" onclick="addMonthlyRecord('income')">+ Catat Pemasukan</button>
                        </div>
                        <div class="bep-col-list">
                            <div class="list-title" id="income-list-title">Riwayat Bulan Ini</div>
                            <div class="record-list" id="income-list">Memuat...</div>
                        </div>
                    </div>

                    <!-- Kolom Pengeluaran -->
                    <div class="bep-col-card">
                        <div class="bep-col-header expense">
                            <div>Pengeluaran Bulanan</div>
                            <div class="col-total" id="col-total-expense">Rp 0</div>
                        </div>
                        <div class="bep-col-form">
                            <div class="input-group">
                                <label>Keterangan</label>
                                <input type="text" id="exp-desc" placeholder="Misal: Listrik, Gaji">
                            </div>
                            <div class="input-group">
                                <label>Jumlah (Rp)</label>
                                <input type="number" id="exp-amount" placeholder="1500000">
                            </div>
                            <button class="btn-add" style="background: var(--danger);" onclick="addMonthlyRecord('expense')">+ Catat Pengeluaran</button>
                        </div>
                        <div class="bep-col-list">
                            <div class="list-title" id="expense-list-title">Riwayat Bulan Ini</div>
                            <div class="record-list" id="expense-list">Memuat...</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal Receipt -->
    <div class="modal-overlay" id="receipt-modal">
        <div class="modal-content">
            <h2 style="margin: 10px 0; color: var(--success);">Transaksi Sukses</h2>
            <div id="receipt-details" style="text-align: left; background: var(--bg); padding: 16px; border-radius: 8px; font-family: monospace; margin: 20px 0;"></div>
            <button class="btn-close" onclick="closeModal('receipt-modal')">Tutup & Pesanan Baru</button>
        </div>
    </div>

    <script>
        /* ==========================================
           UTILITIES & FORMATTERS
           ========================================== */
        const formatRp = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);

        /* ==========================================
           API DATA LAYER (Mengganti LocalStorage)
           ========================================== */
        class ApiRepository {
            async getProducts() {
                try {
                    const res = await fetch('api.php?action=getProducts');
                    return await res.json();
                } catch(e) { console.error(e); return []; }
            }
            async addProductWithFile(formData) {
                await fetch('api.php?action=addProduct', {
                    method: 'POST',
                    body: formData
                });
            }
            async deleteProduct(id) {
                await fetch('api.php?action=deleteProduct', {
                    method: 'POST', headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
            }
            async getTransactions() {
                try {
                    const res = await fetch('api.php?action=getTransactions');
                    return await res.json();
                } catch(e) { console.error(e); return []; }
            }
            async saveTransaction(trx) {
                try {
                    const res = await fetch('api.php?action=saveTransaction', {
                        method: 'POST', headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(trx)
                    });
                    return await res.json();
                } catch(e) { console.error(e); return {status:'error'}; }
            }
            async clearTransactions() {
                await fetch('api.php?action=clearTransactions', { method: 'POST' });
            }
            async getCapitalData() {
                try {
                    const res = await fetch('api.php?action=getCapitalData');
                    return await res.json();
                } catch(e) { console.error(e); return { capitals: [], monthlies: [] }; }
            }
            async addCapital(data) {
                await fetch('api.php?action=addCapital', {
                    method: 'POST', headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
            }
            async addMonthlyRecord(data) {
                await fetch('api.php?action=addMonthlyRecord', {
                    method: 'POST', headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
            }
            async deleteCapital(id) {
                await fetch('api.php?action=deleteCapital', {
                    method: 'POST', headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
            }
            async deleteMonthlyRecord(id) {
                await fetch('api.php?action=deleteMonthlyRecord', {
                    method: 'POST', headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
            }
            async importDummy() {
                try {
                    const res = await fetch('api.php?action=importDummy', { method: 'POST' });
                    return await res.json();
                } catch(e) { console.error(e); return {status:'error', message: e.message}; }
            }
        }

        const api = new ApiRepository();
        
        // Simpan produk sementara di memori agar checkout cepat tanpa API Call berulang
        window.currentProducts = [];

        /* ==========================================
           USE CASES (Cart State)
           ========================================== */
        class CartState {
            constructor() { this.items = []; }
            add(product, qty = 1) {
                const existing = this.items.find(i => parseInt(i.product.id) === parseInt(product.id));
                if (existing) {
                    existing.qty += qty;
                    if(existing.qty <= 0) this.items = this.items.filter(i => parseInt(i.product.id) !== parseInt(product.id));
                } else if (qty > 0) {
                    this.items.push({ product, qty });
                }
            }
            getRevenue() { return this.items.reduce((sum, item) => sum + (item.product.price * item.qty), 0); }
            getCost() { return this.items.reduce((sum, item) => sum + (item.product.cost * item.qty), 0); }
            clear() { this.items = []; }
        }

        const cart = new CartState();

        /* ==========================================
           CONTROLLERS & UI RENDERERS
           ========================================== */
        
        function switchView(viewId, btn) {
            document.querySelectorAll('.view-section').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('active'));
            
            document.getElementById(`view-${viewId}`).classList.add('active');
            btn.classList.add('active');

            if (viewId === 'pos') renderPOSProducts();
            if (viewId === 'menu') renderMenuTable();
            if (viewId === 'analytics') renderAnalytics();
            if (viewId === 'bep') renderBEP();
        }

        // --- POS (Kasir) ---
        async function renderPOSProducts() {
            const grid = document.getElementById('pos-products');
            grid.innerHTML = '<div style="color:var(--text-light); grid-column: 1 / -1;">Memuat menu dari database...</div>';
            
            const products = await api.getProducts();
            window.currentProducts = products;
            
            if (!Array.isArray(products)) {
                grid.innerHTML = `<div style="color:var(--danger); grid-column: 1 / -1; font-weight:bold;">Error Database: ${products.message || 'Pastikan MySQL menyala dan database pos_kasir sudah dibuat.'}</div>`;
                return;
            }
            if (products.length === 0) {
                grid.innerHTML = '<div style="color:var(--text-light); grid-column: 1 / -1;">Belum ada menu di Database. Silakan tambah di Manajemen Menu.</div>';
                return;
            }

            grid.innerHTML = products.map(p => `
                <div class="product-card" onclick="addToCart(${p.id})">
                    <img src="${p.icon || 'https://via.placeholder.com/150?text=No+Image'}" alt="${p.name}" class="product-img" onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                    <div class="product-name">${p.name}</div>
                    <div class="product-price">${formatRp(p.price)}</div>
                </div>
            `).join('');
        }

        function renderCart() {
            const list = document.getElementById('cart-list');
            document.getElementById('cart-count').innerText = `${cart.items.reduce((sum, i) => sum + i.qty, 0)} item`;
            document.getElementById('pos-total').innerText = formatRp(cart.getRevenue());

            if (cart.items.length === 0) {
                list.innerHTML = '<div style="text-align:center; color:var(--text-light); margin-top:20px;">Keranjang kosong</div>';
                return;
            }

            list.innerHTML = cart.items.map(item => `
                <div class="cart-item">
                    <div class="item-info">
                        <h4>${item.product.name}</h4>
                        <p>${formatRp(item.product.price)}</p>
                    </div>
                    <div style="display:flex; align-items:center; gap:15px;">
                        <div class="stepper">
                            <button onclick="updateCartQty(${item.product.id}, -1)">-</button>
                            <span>${item.qty}</span>
                            <button onclick="updateCartQty(${item.product.id}, 1)">+</button>
                        </div>
                        <div class="item-total">${formatRp(item.product.price * item.qty)}</div>
                    </div>
                </div>
            `).join('');
        }

        window.addToCart = (id) => {
            const product = window.currentProducts.find(p => parseInt(p.id) === parseInt(id));
            if(product) { cart.add(product, 1); renderCart(); }
        };

        window.updateCartQty = (id, change) => {
            const product = window.currentProducts.find(p => parseInt(p.id) === parseInt(id));
            if(product) { cart.add(product, change); renderCart(); }
        };

        window.processCheckout = async () => {
            const cashInput = document.getElementById('pos-cash');
            const cash = parseInt(cashInput.value);
            const total = cart.getRevenue();
            const cost = cart.getCost();

            if (cart.items.length === 0) return alert('Keranjang belanja masih kosong!');
            if (isNaN(cash) || cash < total) return alert('Nominal uang pembayaran tidak mencukupi!');

            const btnPay = document.querySelector('.btn-pay');
            btnPay.innerText = "Memproses...";
            btnPay.disabled = true;

            const trx = {
                id: 'TRX-' + Date.now(),
                items: [...cart.items],
                revenue: total,
                cost: cost,
                profit: total - cost,
                cash: cash,
                change: cash - total
            };

            const result = await api.saveTransaction(trx);
            
            btnPay.innerText = "Proses Pembayaran";
            btnPay.disabled = false;

            if (result && result.status === 'success') {
                const finalTrx = result.transaction;
                cart.clear();
                renderCart();
                cashInput.value = '';

                document.getElementById('receipt-details').innerHTML = `
                    <div>ID: ${finalTrx.id}</div>
                    <div>Waktu: ${finalTrx.date}</div>
                    <hr style="margin: 10px 0; border:0; border-top:1px dashed #ccc;">
                    ${finalTrx.items.map(i => `<div>${i.product.name} (x${i.qty}) : ${formatRp(i.product.price * i.qty)}</div>`).join('')}
                    <hr style="margin: 10px 0; border:0; border-top:1px dashed #ccc;">
                    <div>Total Belanja: ${formatRp(finalTrx.revenue)}</div>
                    <div>Tunai: ${formatRp(finalTrx.cash)}</div>
                    <div style="color:var(--success); font-weight:bold; font-size:1.1rem; margin-top:5px;">Kembali: ${formatRp(finalTrx.change)}</div>
                `;
                document.getElementById('receipt-modal').classList.add('active');
            } else {
                alert("Gagal menyimpan transaksi ke database!");
            }
        };

        // --- Menu Management ---
        async function renderMenuTable() {
            const tbody = document.getElementById('menu-table-body');
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Memuat dari database...</td></tr>';
            
            const products = await api.getProducts();
            
            if (!Array.isArray(products)) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:var(--danger); font-weight:bold;">Error DB: ${products.message || 'Koneksi gagal'}</td></tr>`;
                return;
            }
            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Belum ada menu terdaftar.</td></tr>';
                return;
            }

            tbody.innerHTML = products.map(p => `
                <tr>
                    <td><img src="${p.icon || 'https://via.placeholder.com/150?text=No+Image'}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px;" onerror="this.src='https://via.placeholder.com/150?text=No+Image'"></td>
                    <td style="font-weight:600;">${p.name}</td>
                    <td>${formatRp(p.price)}</td>
                    <td>${formatRp(p.cost)}</td>
                    <td style="color:var(--success); font-weight:bold;">${formatRp(p.price - p.cost)}</td>
                    <td><button class="btn-del" onclick="deleteMenu(${p.id})">Hapus DB</button></td>
                </tr>
            `).join('');
        }

        window.addNewProduct = async () => {
            const name = document.getElementById('menu-name').value;
            const fileInput = document.getElementById('menu-image');
            const price = parseInt(document.getElementById('menu-price').value);
            const cost = parseInt(document.getElementById('menu-cost').value);

            if(!name || isNaN(price) || isNaN(cost)) return alert('Harap isi Nama, Harga Jual, dan Harga Modal dengan valid!');

            const formData = new FormData();
            formData.append('id', Date.now());
            formData.append('name', name);
            formData.append('price', price);
            formData.append('cost', cost);
            
            if (fileInput.files.length > 0) {
                formData.append('image', fileInput.files[0]);
            }

            await api.addProductWithFile(formData);
            
            document.getElementById('menu-name').value = '';
            fileInput.value = '';
            document.getElementById('menu-price').value = '';
            document.getElementById('menu-cost').value = '';
            
            renderMenuTable();
        };

        window.deleteMenu = async (id) => {
            if(confirm('Yakin ingin menghapus menu ini dari Database?')) {
                await api.deleteProduct(id);
                renderMenuTable();
            }
        };

        // --- Analytics ---
        // Simpan data transaksi agar tidak fetch ulang saat ganti filter
        window._analyticsHistory = null;

        async function renderAnalytics() {
            const filterSelect = document.getElementById('filter-month');
            const selectedMonth = filterSelect.value;

            // Fetch data hanya sekali, simpan di memori
            if (!window._analyticsHistory) {
                document.getElementById('bestseller-body').innerHTML = '<tr><td colspan="4" style="text-align:center;">Menarik data...</td></tr>';
                document.getElementById('trx-history-body').innerHTML = '<tr><td colspan="8" style="text-align:center;">Menarik data...</td></tr>';
                const history = await api.getTransactions();

                if (!Array.isArray(history)) {
                    document.getElementById('bestseller-body').innerHTML = `<tr><td colspan="4" style="text-align:center; color:var(--danger); font-weight:bold;">Error DB: ${history.message || 'Koneksi gagal'}</td></tr>`;
                    return;
                }
                window._analyticsHistory = history;

                // Bangun opsi dropdown bulan dari data
                const months = new Set();
                history.forEach(trx => {
                    // Format date: "dd/mm/yyyy, HH:ii:ss" -> ambil mm/yyyy
                    const parts = trx.date.split(',')[0].split('/');
                    if (parts.length === 3) {
                        months.add(parts[2] + '-' + parts[1]); // yyyy-mm
                    }
                });
                const sortedMonths = [...months].sort().reverse();
                const currentVal = filterSelect.value;
                filterSelect.innerHTML = '<option value="all">Semua Bulan</option>';
                sortedMonths.forEach(m => {
                    const [y, mo] = m.split('-');
                    const label = new Date(y, parseInt(mo) - 1).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
                    filterSelect.innerHTML += `<option value="${m}">${label}</option>`;
                });
                filterSelect.value = currentVal !== 'all' && sortedMonths.includes(currentVal) ? currentVal : 'all';
            }

            const history = window._analyticsHistory;

            // Filter berdasarkan bulan
            let filtered = history;
            if (selectedMonth !== 'all') {
                filtered = history.filter(trx => {
                    const parts = trx.date.split(',')[0].split('/');
                    if (parts.length === 3) {
                        const ym = parts[2] + '-' + parts[1];
                        return ym === selectedMonth;
                    }
                    return false;
                });
            }

            let totalOmset = 0;
            let totalProfit = 0;
            let productSales = {}; 

            if(filtered.length > 0) {
                filtered.forEach(trx => {
                    totalOmset += trx.revenue;
                    totalProfit += trx.profit;

                    trx.items.forEach(item => {
                        const pName = item.product.name;
                        if (!productSales[pName]) productSales[pName] = { qty: 0, omset: 0 };
                        productSales[pName].qty += item.qty;
                        productSales[pName].omset += (item.product.price * item.qty);
                    });
                });
            }

            document.getElementById('stat-trx-count').innerText = filtered.length;
            document.getElementById('stat-omset').innerText = formatRp(totalOmset);
            document.getElementById('stat-profit').innerText = formatRp(totalProfit);

            // Best sellers
            const bestSellers = Object.keys(productSales).map(key => ({
                name: key,
                qty: productSales[key].qty,
                omset: productSales[key].omset
            })).sort((a, b) => b.qty - a.qty);

            const tbody = document.getElementById('bestseller-body');
            if (bestSellers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">Belum ada penjualan di DB.</td></tr>';
            } else {
                tbody.innerHTML = bestSellers.map((bs, index) => `
                    <tr>
                        <td style="font-weight:bold;">#${index + 1}</td>
                        <td>${bs.name}</td>
                        <td>${bs.qty} Terjual</td>
                        <td style="color:var(--primary); font-weight:600;">${formatRp(bs.omset)}</td>
                    </tr>
                `).join('');
            }

            // Riwayat Transaksi
            const trxBody = document.getElementById('trx-history-body');
            if (filtered.length === 0) {
                trxBody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Belum ada transaksi.</td></tr>';
            } else {
                trxBody.innerHTML = filtered.map(trx => {
                    const detailId = 'detail-' + trx.id.replace(/[^a-zA-Z0-9]/g, '');
                    const detailItems = trx.items.map(i => 
                        `<div class="detail-item"><span>${i.product.name} x${i.qty}</span><span>${formatRp(i.product.price * i.qty)}</span></div>`
                    ).join('');
                    return `
                        <tr>
                            <td style="font-weight:600; font-size:0.8rem;">${trx.id}</td>
                            <td style="white-space:nowrap;">${trx.date}</td>
                            <td style="font-weight:600;">${formatRp(trx.revenue)}</td>
                            <td>${formatRp(trx.cost)}</td>
                            <td style="color:var(--success); font-weight:600;">${formatRp(trx.profit)}</td>
                            <td>${formatRp(trx.cash)}</td>
                            <td>${formatRp(trx.change)}</td>
                            <td><button class="trx-detail-toggle" onclick="toggleDetail('${detailId}')">Lihat</button></td>
                        </tr>
                        <tr class="trx-detail-row" id="${detailId}" style="display:none;">
                            <td colspan="8">
                                <div class="trx-detail-content">${detailItems}</div>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        }

        window.toggleDetail = (id) => {
            const row = document.getElementById(id);
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        };

        window.resetAnalytics = async () => {
            if (confirm("PERINGATAN: Anda yakin ingin menghapus SELURUH riwayat di DATABASE MySQL? Tindakan ini permanen.")) {
                await api.clearTransactions();
                window._analyticsHistory = null;
                renderAnalytics();
                alert("Seluruh data laporan berhasil dihapus dari Database.");
            }
        };

        window.exportToExcel = async () => {
            // Gunakan data yang sudah di-cache, atau fetch baru jika belum ada
            if (!window._analyticsHistory) {
                const history = await api.getTransactions();
                if (!Array.isArray(history)) return alert("Gagal mengambil data transaksi!");
                window._analyticsHistory = history;
            }

            const history = window._analyticsHistory;
            if (!history || history.length === 0) return alert("Belum ada data transaksi untuk diekspor!");

            // Terapkan filter bulan yang sedang aktif
            const selectedMonth = document.getElementById('filter-month').value;
            let filtered = history;
            let filterLabel = 'Semua_Bulan';

            if (selectedMonth !== 'all') {
                filtered = history.filter(trx => {
                    const parts = trx.date.split(',')[0].split('/');
                    if (parts.length === 3) {
                        const ym = parts[2] + '-' + parts[1];
                        return ym === selectedMonth;
                    }
                    return false;
                });
                // Nama bulan yang mudah dibaca, misal: "April_2026"
                const [y, mo] = selectedMonth.split('-');
                const bulanNama = new Date(y, parseInt(mo) - 1).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' }).replace(' ', '_');
                filterLabel = bulanNama;
            }

            if (filtered.length === 0) return alert("Tidak ada data transaksi untuk bulan yang dipilih!");

            let csvContent = "ID Transaksi,Waktu,Total Omset (Rp),Total Modal (Rp),Laba Bersih (Rp),Detail Barang Terjual\n";

            filtered.forEach(trx => {
                const detail = trx.items.map(i => `${i.product.name} (x${i.qty})`).join(' | ');
                const row = [
                    trx.id,
                    `"${trx.date}"`,
                    trx.revenue,
                    trx.cost,
                    trx.profit,
                    `"${detail}"`
                ].join(',');
                csvContent += row + "\n";
            });

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", `Laporan_${filterLabel}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        window.closeModal = (id) => document.getElementById(id).classList.remove('active');

        /* ==========================================
           BALIK MODAL (BEP) CONTROLLER
           ========================================== */
        async function renderBEP() {
            const data = await api.getCapitalData();
            if (!data || !data.capitals) return;

            // Bangun opsi dropdown bulan dari data monthly_records
            const filterSelect = document.getElementById('filter-bep-month');
            const selectedMonth = filterSelect.value;

            const months = new Set();
            data.monthlies.forEach(m => {
                months.add(m.month);
            });
            // Pastikan bulan berjalan selalu ada di daftar pilihan
            const currentRealMonth = new Date().toISOString().slice(0, 7);
            months.add(currentRealMonth);

            const sortedMonths = [...months].sort().reverse();

            filterSelect.innerHTML = '';
            sortedMonths.forEach(m => {
                const [y, mo] = m.split('-');
                const label = new Date(y, parseInt(mo) - 1).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
                filterSelect.innerHTML += `<option value="${m}">${label}</option>`;
            });

            // Tentukan bulan yang aktif untuk kalkulasi
            let activeMonth = selectedMonth;
            if (!activeMonth || !sortedMonths.includes(activeMonth)) {
                activeMonth = currentRealMonth;
            }
            filterSelect.value = activeMonth;

            // Label Bulan Terpilih untuk Tampilan Premium
            const [y, mo] = activeMonth.split('-');
            const labelBulan = new Date(y, parseInt(mo) - 1).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });

            document.getElementById('bep-income-label').innerText = `Pemasukan (${labelBulan})`;
            document.getElementById('bep-expense-label').innerText = `Pengeluaran (${labelBulan})`;
            document.getElementById('bep-profit-label').innerText = `Profit Bersih (${labelBulan})`;
            document.getElementById('income-list-title').innerText = `Riwayat ${labelBulan}`;
            document.getElementById('expense-list-title').innerText = `Riwayat ${labelBulan}`;

            const totalModal = data.capitals.reduce((s, c) => s + c.amount, 0);

            const monthlyIncomes = data.monthlies.filter(m => m.type === 'income' && m.month === activeMonth);
            const monthlyExpenses = data.monthlies.filter(m => m.type === 'expense' && m.month === activeMonth);
            const totalIncome = monthlyIncomes.reduce((s, m) => s + m.amount, 0);
            const totalExpense = monthlyExpenses.reduce((s, m) => s + m.amount, 0);
            const monthlyProfit = totalIncome - totalExpense;

            const allIncomes = data.monthlies.filter(m => m.type === 'income');
            const allExpenses = data.monthlies.filter(m => m.type === 'expense');
            const totalAllIncome = allIncomes.reduce((s, m) => s + m.amount, 0);
            const totalAllExpense = allExpenses.reduce((s, m) => s + m.amount, 0);
            const totalRecovered = totalAllIncome - totalAllExpense;

            document.getElementById('bep-total-modal').innerText = formatRp(totalModal);
            document.getElementById('bep-income').innerText = formatRp(totalIncome);
            document.getElementById('bep-expense').innerText = formatRp(totalExpense);
            document.getElementById('bep-profit').innerText = formatRp(monthlyProfit);

            // Update total di header kolom
            document.getElementById('col-total-capital').innerText = formatRp(totalModal);
            document.getElementById('col-total-income').innerText = formatRp(totalIncome);
            document.getElementById('col-total-expense').innerText = formatRp(totalExpense);

            const elEstimation = document.getElementById('bep-estimation');
            const elProgress = document.getElementById('bep-progress');
            const elRecovered = document.getElementById('bep-recovered');
            const elTarget = document.getElementById('bep-target');
            const elPct = document.getElementById('bep-pct');

            elRecovered.innerText = `Terkumpul: ${formatRp(Math.max(0, totalRecovered))}`;
            elTarget.innerText = `Target: ${formatRp(totalModal)}`;

            if (totalModal <= 0) {
                elEstimation.innerText = 'Belum ada data modal awal';
                elProgress.style.width = '0%';
                elPct.innerText = '0%';
            } else if (totalRecovered >= totalModal) {
                elEstimation.innerText = 'Modal sudah kembali sepenuhnya';
                elProgress.style.width = '100%';
                elPct.innerText = '100%';
            } else if (monthlyProfit <= 0) {
                const pct = Math.min(100, Math.max(0, (totalRecovered / totalModal) * 100));
                elProgress.style.width = pct + '%';
                elPct.innerText = Math.round(pct) + '%';
                elEstimation.innerText = 'Profit bulan ini belum positif, estimasi tidak dapat dihitung';
            } else {
                const remaining = totalModal - totalRecovered;
                const monthsLeft = Math.ceil(remaining / monthlyProfit);
                const pct = Math.min(100, Math.max(0, (totalRecovered / totalModal) * 100));
                elProgress.style.width = pct + '%';
                elPct.innerText = Math.round(pct) + '%';
                if (monthsLeft <= 1) {
                    elEstimation.innerText = 'Kurang dari 1 bulan lagi';
                } else {
                    elEstimation.innerText = `Sekitar ${monthsLeft} bulan lagi`;
                }
            }

            // Render daftar riwayat
            const capitalList = document.getElementById('capital-list');
            if (data.capitals.length === 0) {
                capitalList.innerHTML = '<div style="text-align:center; color:var(--text-light); padding:12px;">Belum ada catatan modal.</div>';
            } else {
                capitalList.innerHTML = data.capitals.map(c => `
                    <div class="record-item">
                        <div>
                            <div class="rec-desc">${c.description}</div>
                            <div class="rec-date">${c.date}</div>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span class="rec-amount">${formatRp(c.amount)}</span>
                            <button class="btn-del-sm" onclick="deleteCapitalRecord(${c.id})">Hapus</button>
                        </div>
                    </div>
                `).join('');
            }

            const incomeList = document.getElementById('income-list');
            if (monthlyIncomes.length === 0) {
                incomeList.innerHTML = `<div style="text-align:center; color:var(--text-light); padding:12px;">Belum ada pemasukan pada ${labelBulan}.</div>`;
            } else {
                incomeList.innerHTML = monthlyIncomes.map(m => `
                    <div class="record-item">
                        <div>
                            <div class="rec-desc">${m.description}</div>
                            <div class="rec-date">${m.date}</div>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span class="rec-amount income">+${formatRp(m.amount)}</span>
                            <button class="btn-del-sm" onclick="deleteMonthly(${m.id})">Hapus</button>
                        </div>
                    </div>
                `).join('');
            }

            const expenseList = document.getElementById('expense-list');
            if (monthlyExpenses.length === 0) {
                expenseList.innerHTML = `<div style="text-align:center; color:var(--text-light); padding:12px;">Belum ada pengeluaran pada ${labelBulan}.</div>`;
            } else {
                expenseList.innerHTML = monthlyExpenses.map(m => `
                    <div class="record-item">
                        <div>
                            <div class="rec-desc">${m.description}</div>
                            <div class="rec-date">${m.date}</div>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span class="rec-amount expense">-${formatRp(m.amount)}</span>
                            <button class="btn-del-sm" onclick="deleteMonthly(${m.id})">Hapus</button>
                        </div>
                    </div>
                `).join('');
            }
        }

        window.addCapitalRecord = async () => {
            const desc = document.getElementById('cap-desc').value;
            const amount = parseInt(document.getElementById('cap-amount').value);
            if (!desc || isNaN(amount) || amount <= 0) return alert('Harap isi keterangan dan jumlah modal dengan valid.');
            await api.addCapital({ description: desc, amount });
            document.getElementById('cap-desc').value = '';
            document.getElementById('cap-amount').value = '';
            renderBEP();
        };

        window.addMonthlyRecord = async (type) => {
            const prefix = type === 'income' ? 'inc' : 'exp';
            const desc = document.getElementById(`${prefix}-desc`).value;
            const amount = parseInt(document.getElementById(`${prefix}-amount`).value);
            if (!desc || isNaN(amount) || amount <= 0) return alert('Harap isi keterangan dan jumlah dengan valid.');
            
            const activeMonth = document.getElementById('filter-bep-month').value;
            await api.addMonthlyRecord({ type, description: desc, amount, month: activeMonth });
            document.getElementById(`${prefix}-desc`).value = '';
            document.getElementById(`${prefix}-amount`).value = '';
            renderBEP();
        };

        window.deleteCapitalRecord = async (id) => {
            if (confirm('Hapus catatan modal ini?')) {
                await api.deleteCapital(id);
                renderBEP();
            }
        };

        window.deleteMonthly = async (id) => {
            if (confirm('Hapus catatan ini?')) {
                await api.deleteMonthlyRecord(id);
                renderBEP();
            }
        };

        window.importDummyData = async () => {
            if (confirm("Apakah Anda yakin ingin mengimpor seluruh data demo? Ini akan menyetel ulang database Anda ke kondisi demo awal.")) {
                const res = await api.importDummy();
                if (res && res.status === 'success') {
                    window._analyticsHistory = null;
                    renderPOSProducts();
                    renderCart();
                    renderAnalytics();
                    renderBEP();
                    alert("Data demo berhasil diimpor!");
                } else {
                    alert("Gagal mengimpor data demo: " + (res.message || "Unknown error"));
                }
            }
        };

        // Init App (Asynchronous load)
        renderPOSProducts();
        renderCart();

    </script>
</body>
</html>
