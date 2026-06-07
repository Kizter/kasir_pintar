-- ====================================================================
-- KASIR PINTAR - DUMMY DATA FOR DEMO & PRESENTATION
-- Disesuaikan dengan slide materi_presentasi.md & dokumentasi_sistem.md
-- ====================================================================

USE pos_kasir;

-- Hapus data lama untuk memastikan demo mulai dengan data bersih dan akurat
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE transaction_items;
TRUNCATE TABLE transactions;
TRUNCATE TABLE products;
TRUNCATE TABLE capital_records;
TRUNCATE TABLE monthly_records;
SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================
-- 1. DATA PRODUK (MENU UTAMA KASIR)
-- ==========================================
-- Menyediakan 12 produk dengan margin realistis
INSERT INTO products (id, name, icon, price, cost) VALUES
(1700000000001, 'Es Kopi Susu', 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 18000, 8000),
(1700000000002, 'Nasi Goreng', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 25000, 15000),
(1700000000003, 'Es Teh Manis', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 5000, 2000),
(1700000000004, 'Ayam Geprek', 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 20000, 11000),
(1700000000005, 'Kentang Goreng', 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 12000, 6000),
(1700000000006, 'Roti Bakar', 'https://images.unsplash.com/photo-1484723091739-30a097e8f929?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 15000, 7000),
(1700000000007, 'Mie Goreng Spesial', 'https://images.unsplash.com/photo-1585032226651-759b368d7246?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 15000, 9000),
(1700000000008, 'Burger Sapi', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 22000, 12000),
(1700000000009, 'Caffe Latte', 'https://images.unsplash.com/photo-1541167760496-1628856ab772?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 20000, 9000),
(1700000000010, 'Matcha Latte', 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 22000, 10000),
(1700000000011, 'Pisang Goreng Keju', 'https://images.unsplash.com/photo-1590080875515-8a3a8dc5735e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 10000, 4000),
(1700000000012, 'Jus Alpukat', 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 15000, 7000),
(1700000000013, 'Sate Ayam', 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 25000, 15000),
(1700000000014, 'Kopi Hitam', 'https://images.unsplash.com/photo-1550133730-695473e544be?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 10000, 4000),
(1700000000015, 'Mendoan', 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 12000, 6000);


-- ==========================================
-- 2. DANA INVESTASI / MODAL AWAL (Slide 7 BEP)
-- ==========================================
-- Total Modal Awal: Rp 34.000.000
INSERT INTO capital_records (id, description, amount, date) VALUES
(1, 'Sewa Ruko 1 Tahun', 15000000, '2026-04-01 08:00:00'),
(2, 'Beli Mesin Kopi & Grinder', 10000000, '2026-04-02 10:00:00'),
(3, 'Renovasi interior & Peralatan Kasir', 5000000, '2026-04-03 14:00:00'),
(4, 'Papan Nama Toko & Neon Box', 1500000, '2026-04-03 16:00:00'),
(5, 'Lisensi Software POS & Internet', 1000000, '2026-04-04 09:00:00'),
(6, 'Stok Kemasan & Gelas Kertas Awal', 1500000, '2026-04-05 10:00:00');


-- ==========================================
-- 3. CATATAN KEUANGAN BULANAN (Slide 7 BEP)
-- ==========================================
-- April 2026 (Pemasukan: Rp 8.000.000, Pengeluaran: Rp 5.000.000) -> Profit: Rp 3.000.000
-- Mei 2026 (Pemasukan: Rp 10.000.000, Pengeluaran: Rp 5.000.000) -> Profit: Rp 5.000.000
-- Juni 2026 (Pemasukan: Rp 10.000.000, Pengeluaran: Rp 5.700.000) -> Profit: Rp 4.300.000
-- Total Terkumpul: Rp 12.300.000 (36.2% dari Rp 34.000.000)
-- Sisa Modal: Rp 21.700.000 -> Estimasi Balik Modal: 21.7jt / 4.3jt = 6 Bulan Lagi (Mulai dari Juni 2026)
INSERT INTO monthly_records (id, month, type, description, amount, date) VALUES
-- April 2026
(1, '2026-04', 'income', 'Hasil Jualan Harian (April)', 8000000, '2026-04-30 20:00:00'),
(2, '2026-04', 'expense', 'Listrik dan Air (April)', 800000, '2026-04-28 10:00:00'),
(3, '2026-04', 'expense', 'Gaji 2 Karyawan (April)', 3000000, '2026-04-29 17:00:00'),
(4, '2026-04', 'expense', 'Bahan Baku Bulanan (April)', 1200000, '2026-04-30 11:00:00'),

-- Mei 2026 (Bulan berjalan demo)
(5, '2026-05', 'income', 'Hasil Jualan Harian (Mei)', 8000000, '2026-05-20 20:00:00'),
(6, '2026-05', 'income', 'Pesanan Katering Acara Kantor', 2000000, '2026-05-15 14:00:00'),
(7, '2026-05', 'expense', 'Listrik dan Air (Mei)', 800000, '2026-05-20 10:00:00'),
(8, '2026-05', 'expense', 'Gaji 2 Karyawan (Mei)', 3000000, '2026-05-20 17:00:00'),
(9, '2026-05', 'expense', 'Bahan Baku Bulanan (Mei)', 1200000, '2026-05-21 11:00:00'),

-- Juni 2026
(10, '2026-06', 'income', 'Hasil Jualan Harian (Juni)', 9500000, '2026-06-07 20:00:00'),
(11, '2026-06', 'income', 'Pendapatan Sewa Space Banner', 500000, '2026-06-05 11:00:00'),
(12, '2026-06', 'expense', 'Listrik dan Air (Juni)', 850000, '2026-06-07 10:00:00'),
(13, '2026-06', 'expense', 'Gaji 2 Karyawan (Juni)', 3000000, '2026-06-07 17:00:00'),
(14, '2026-06', 'expense', 'Bahan Baku Bulanan (Juni)', 1500000, '2026-06-07 11:00:00'),
(15, '2026-06', 'expense', 'Biaya Wi-Fi & Internet (Juni)', 350000, '2026-06-03 14:00:00'),

-- Juli 2026
(16, '2026-07', 'income', 'Hasil Jualan Harian (Juli)', 11500000, '2026-07-31 20:00:00'),
(17, '2026-07', 'income', 'Pendapatan Sewa Space Banner', 500000, '2026-07-05 11:00:00'),
(18, '2026-07', 'expense', 'Listrik dan Air (Juli)', 900000, '2026-07-30 10:00:00'),
(19, '2026-07', 'expense', 'Gaji 2 Karyawan (Juli)', 3000000, '2026-07-30 17:00:00'),
(20, '2026-07', 'expense', 'Bahan Baku Bulanan (Juli)', 1800000, '2026-07-30 11:00:00'),
(21, '2026-07', 'expense', 'Biaya Wi-Fi & Internet (Juli)', 350000, '2026-07-03 14:00:00');


-- ==========================================
-- 4. RIWAYAT TRANSAKSI KASIR (Slide 5-6 Analitik)
-- ==========================================
-- Menyediakan riwayat transaksi penjualan yang detail & matematis secara akurat

-- April 2026
INSERT INTO transactions (id, date, total_revenue, total_cost, profit, cash_received, change_amount) VALUES
('TRX-1711958400001', '2026-04-01 11:30:00', 30000, 17000, 13000, 50000, 20000),
('TRX-1712048400002', '2026-04-02 12:45:00', 48000, 22000, 26000, 50000, 2000),
('TRX-1712235600003', '2026-04-04 15:20:00', 40000, 18000, 22000, 50000, 10000),
('TRX-1712397600004', '2026-04-06 18:10:00', 62000, 32000, 30000, 100000, 38000),
('TRX-1712660000005', '2026-04-09 19:30:00', 129000, 69000, 60000, 15000, 21000),
('TRX-1712832800006', '2026-04-11 13:15:00', 25000, 13000, 12000, 50000, 25000),
('TRX-1713092000007', '2026-04-14 16:40:00', 60000, 28000, 32000, 100000, 40000),
('TRX-1713351200008', '2026-04-17 19:00:00', 35000, 16000, 19000, 50000, 15000),
('TRX-1713783200009', '2026-04-22 20:30:00', 81000, 42000, 39000, 100000, 19000),
('TRX-1714128800010', '2026-04-26 12:00:00', 96000, 44000, 52000, 100000, 4000);

-- Mei 2026
INSERT INTO transactions (id, date, total_revenue, total_cost, profit, cash_received, change_amount) VALUES
('TRX-1714561200011', '2026-05-01 10:30:00', 51000, 23000, 28000, 100000, 49000),
('TRX-1714734000012', '2026-05-03 12:15:00', 30000, 17000, 13000, 50000, 20000),
('TRX-1714906800013', '2026-05-05 14:00:00', 50000, 26000, 24000, 50000, 0),
('TRX-1715079600014', '2026-05-07 15:45:00', 60000, 28000, 32000, 100000, 40000),
('TRX-1715252400015', '2026-05-09 18:30:00', 90000, 51000, 39000, 100000, 10000),
('TRX-1715425200016', '2026-05-11 19:15:00', 50000, 25000, 25000, 50000, 0),
('TRX-1715598000017', '2026-05-13 13:00:00', 66000, 30000, 36000, 100000, 34000),
('TRX-1715770800018', '2026-05-15 17:30:00', 55000, 30000, 25000, 100000, 45000),
('TRX-1715943600019', '2026-05-17 20:00:00', 84000, 38000, 46000, 100000, 16000),
('TRX-1716116400020', '2026-05-19 12:30:00', 90000, 44000, 46000, 100000, 10000),
('TRX-1716289200021', '2026-05-21 15:00:00', 43000, 23000, 20000, 50000, 7000),
('TRX-1779417360022', '2026-05-22 09:36:00', 36000, 16000, 20000, 50000, 14000),
('TRX-1779453531023', '2026-05-22 19:38:51', 63000, 29000, 34000, 63000, 0),
('TRX-1779494262024', '2026-05-23 06:57:42', 92000, 50000, 42000, 100000, 8000),
('TRX-1779532534025', '2026-05-23 17:35:34', 30000, 14000, 16000, 50000, 20000),
('TRX-1779573685026', '2026-05-24 05:01:25', 5000, 2000, 3000, 5000, 0),
('TRX-1779613277027', '2026-05-24 16:01:17', 22000, 10000, 12000, 50000, 28000),
('TRX-1779656648028', '2026-05-25 04:04:08', 20000, 11000, 9000, 20000, 0),
('TRX-1779697379029', '2026-05-25 15:22:59', 42000, 23000, 19000, 50000, 8000),
('TRX-1779733191030', '2026-05-26 01:19:51', 36000, 16000, 20000, 50000, 14000),
('TRX-1779777462031', '2026-05-26 13:37:42', 55000, 32000, 23000, 100000, 45000),
('TRX-1779817954032', '2026-05-27 00:52:34', 83000, 45000, 38000, 100000, 17000),
('TRX-1779857485033', '2026-05-27 11:51:25', 42000, 23000, 19000, 50000, 8000),
('TRX-1779895697034', '2026-05-27 22:28:17', 61000, 31000, 30000, 100000, 39000),
('TRX-1779935648035', '2026-05-28 09:34:08', 55000, 30000, 25000, 100000, 45000),
('TRX-1779975899036', '2026-05-28 20:44:59', 55000, 30000, 25000, 100000, 45000),
('TRX-1780013511037', '2026-05-29 07:11:51', 15000, 7000, 8000, 20000, 5000),
('TRX-1780055922038', '2026-05-29 18:58:42', 88000, 44000, 44000, 100000, 12000),
('TRX-1780093534039', '2026-05-30 05:25:34', 52000, 24000, 28000, 100000, 48000),
('TRX-1780132705040', '2026-05-30 16:18:25', 50000, 26000, 24000, 50000, 0),
('TRX-1780173917041', '2026-05-31 03:45:17', 47000, 22000, 25000, 50000, 3000),
('TRX-1780215068042', '2026-05-31 15:11:08', 18000, 8000, 10000, 20000, 2000),
('TRX-1780253339043', '2026-06-01 01:48:59', 30000, 14000, 16000, 50000, 20000),
('TRX-1780296831044', '2026-06-01 13:53:51', 50000, 30000, 20000, 50000, 0),
('TRX-1780334022045', '2026-06-02 00:13:42', 39000, 19000, 20000, 50000, 11000),
('TRX-1780378054046', '2026-06-02 12:27:34', 30000, 14000, 16000, 50000, 20000),
('TRX-1780417885047', '2026-06-02 23:31:25', 37000, 19000, 18000, 37000, 0),
('TRX-1780452917048', '2026-06-03 09:15:17', 52000, 28000, 24000, 100000, 48000),
('TRX-1780493048049', '2026-06-03 20:24:08', 42000, 20000, 22000, 50000, 8000),
('TRX-1780534439050', '2026-06-04 07:53:59', 89000, 49000, 40000, 100000, 11000),
('TRX-1780573551051', '2026-06-04 18:45:51', 65000, 33000, 32000, 100000, 35000),
('TRX-1780617762052', '2026-06-05 07:02:42', 36000, 16000, 20000, 50000, 14000),
('TRX-1780653634053', '2026-06-05 17:00:34', 25000, 15000, 10000, 50000, 25000),
('TRX-1780693885054', '2026-06-06 04:11:25', 55000, 32000, 23000, 100000, 45000),
('TRX-1780733417055', '2026-06-06 15:10:17', 30000, 14000, 16000, 50000, 20000),
('TRX-1780774688056', '2026-06-07 02:38:08', 40000, 22000, 18000, 50000, 10000),
('TRX-1780811234057', '2026-06-08 10:15:00', 35000, 15000, 20000, 50000, 15000),
('TRX-1780852345058', '2026-06-08 14:20:00', 60000, 30000, 30000, 100000, 40000),
('TRX-1780893456059', '2026-06-09 11:45:00', 45000, 25000, 20000, 50000, 5000),
('TRX-1780934567060', '2026-06-09 18:30:00', 75000, 40000, 35000, 100000, 25000),
('TRX-1780975678061', '2026-06-10 12:10:00', 25000, 15000, 10000, 50000, 25000),
('TRX-1781016789062', '2026-06-10 19:50:00', 80000, 45000, 35000, 100000, 20000);


-- ==========================================
-- 5. DETAIL ITEM TRANSAKSI
-- ==========================================
-- Menghubungkan transaksi di atas dengan daftar menu produk secara tepat

-- April 2026
-- TRX-1711958400001: 1 Nasi Goreng (25k), 1 Es Teh (5k) = 30k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1711958400001', 'Nasi Goreng', 1, 25000),
('TRX-1711958400001', 'Es Teh Manis', 1, 5000);

-- TRX-1712048400002: 2 Es Kopi Susu (36k), 1 Kentang Goreng (12k) = 48k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1712048400002', 'Es Kopi Susu', 2, 18000),
('TRX-1712048400002', 'Kentang Goreng', 1, 12000);

-- TRX-1712235600003: 2 Roti Bakar (30k), 2 Es Teh Manis (10k) = 40k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1712235600003', 'Roti Bakar', 2, 15000),
('TRX-1712235600003', 'Es Teh Manis', 2, 5000);

-- TRX-1712397600004: 2 Ayam Geprek (40k), 2 Es Teh Manis (10k), 1 Kentang Goreng (12k) = 62k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1712397600004', 'Ayam Geprek', 2, 20000),
('TRX-1712397600004', 'Es Teh Manis', 2, 5000),
('TRX-1712397600004', 'Kentang Goreng', 1, 12000);

-- TRX-1712660000005: 3 Nasi Goreng (75k), 3 Es Kopi Susu (54k) = 129k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1712660000005', 'Nasi Goreng', 3, 25000),
('TRX-1712660000005', 'Es Kopi Susu', 3, 18000);

-- TRX-1712832800006: 1 Ayam Geprek (20k), 1 Es Teh (5k) = 25k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1712832800006', 'Ayam Geprek', 1, 20000),
('TRX-1712832800006', 'Es Teh Manis', 1, 5000);

-- TRX-1713092000007: 2 Kentang Goreng (24k), 2 Es Kopi Susu (36k) = 60k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1713092000007', 'Kentang Goreng', 2, 12000),
('TRX-1713092000007', 'Es Kopi Susu', 2, 18000);

-- TRX-1713351200008: 2 Roti Bakar (30k), 1 Es Teh Manis (5k) = 35k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1713351200008', 'Roti Bakar', 2, 15000),
('TRX-1713351200008', 'Es Teh Manis', 1, 5000);

-- TRX-1713783200009: 1 Nasi Goreng (25k), 1 Ayam Geprek (20k), 2 Es Kopi Susu (36k) = 81k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1713783200009', 'Nasi Goreng', 1, 25000),
('TRX-1713783200009', 'Ayam Geprek', 1, 20000),
('TRX-1713783200009', 'Es Kopi Susu', 2, 18000);

-- TRX-1714128800010: 4 Es Kopi Susu (72k), 2 Kentang Goreng (24k) = 96k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1714128800010', 'Es Kopi Susu', 4, 18000),
('TRX-1714128800010', 'Kentang Goreng', 2, 12000);

-- Mei 2026
-- TRX-1714561200011: 2 Es Kopi Susu (36k), 1 Roti Bakar (15k) = 51k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1714561200011', 'Es Kopi Susu', 2, 18000),
('TRX-1714561200011', 'Roti Bakar', 1, 15000);

-- TRX-1714734000012: 1 Nasi Goreng (25k), 1 Es Teh Manis (5k) = 30k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1714734000012', 'Nasi Goreng', 1, 25000),
('TRX-1714734000012', 'Es Teh Manis', 1, 5000);

-- TRX-1714906800013: 2 Ayam Geprek (40k), 2 Es Teh Manis (10k) = 50k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1714906800013', 'Ayam Geprek', 2, 20000),
('TRX-1714906800013', 'Es Teh Manis', 2, 5000);

-- TRX-1715079600014: 2 Kentang Goreng (24k), 2 Es Kopi Susu (36k) = 60k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1715079600014', 'Kentang Goreng', 2, 12000),
('TRX-1715079600014', 'Es Kopi Susu', 2, 18000);

-- TRX-1715252400015: 3 Nasi Goreng (75k), 3 Es Teh Manis (15k) = 90k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1715252400015', 'Nasi Goreng', 3, 25000),
('TRX-1715252400015', 'Es Teh Manis', 3, 5000);

-- TRX-1715425200016: 1 Ayam Geprek (20k), 1 Kentang Goreng (12k), 1 Es Kopi Susu (18k) = 50k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1715425200016', 'Ayam Geprek', 1, 20000),
('TRX-1715425200016', 'Kentang Goreng', 1, 12000),
('TRX-1715425200016', 'Es Kopi Susu', 1, 18000);

-- TRX-1715598000017: 2 Roti Bakar (30k), 2 Es Kopi Susu (36k) = 66k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1715598000017', 'Roti Bakar', 2, 15000),
('TRX-1715598000017', 'Es Kopi Susu', 2, 18000);

-- TRX-1715770800018: 1 Nasi Goreng (25k), 1 Ayam Geprek (20k), 2 Es Teh Manis (10k) = 55k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1715770800018', 'Nasi Goreng', 1, 25000),
('TRX-1715770800018', 'Ayam Geprek', 1, 20000),
('TRX-1715770800018', 'Es Teh Manis', 2, 5000);

-- TRX-1715943600019: 4 Es Kopi Susu (72k), 1 Kentang Goreng (12k) = 84k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1715943600019', 'Es Kopi Susu', 4, 18000),
('TRX-1715943600019', 'Kentang Goreng', 1, 12000);

-- TRX-1716116400020: 2 Ayam Geprek (40k), 2 Roti Bakar (30k), 4 Es Teh Manis (20k) = 90k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1716116400020', 'Ayam Geprek', 2, 20000),
('TRX-1716116400020', 'Roti Bakar', 2, 15000),
('TRX-1716116400020', 'Es Teh Manis', 4, 5000);

-- TRX-1716289200021: 1 Es Kopi Susu (18k), 1 Nasi Goreng (25k) = 43k
INSERT INTO transaction_items (transaction_id, product_name, quantity, price) VALUES
('TRX-1716289200021', 'Es Kopi Susu', 1, 18000),
('TRX-1716289200021', 'Nasi Goreng', 1, 25000),

-- TRX-1779417360022: 2 Es Kopi Susu = 36000
('TRX-1779417360022', 'Es Kopi Susu', 2, 18000),
-- TRX-1779453531023: 2 Es Kopi Susu, 1 Kentang Goreng, 1 Roti Bakar = 63000
('TRX-1779453531023', 'Es Kopi Susu', 2, 18000),
('TRX-1779453531023', 'Kentang Goreng', 1, 12000),
('TRX-1779453531023', 'Roti Bakar', 1, 15000),
-- TRX-1779494262024: 2 Kentang Goreng, 1 Es Kopi Susu, 2 Nasi Goreng = 92000
('TRX-1779494262024', 'Kentang Goreng', 2, 12000),
('TRX-1779494262024', 'Es Kopi Susu', 1, 18000),
('TRX-1779494262024', 'Nasi Goreng', 2, 25000),
-- TRX-1779532534025: 2 Roti Bakar = 30000
('TRX-1779532534025', 'Roti Bakar', 2, 15000),
-- TRX-1779573685026: 1 Es Teh Manis = 5000
('TRX-1779573685026', 'Es Teh Manis', 1, 5000),
-- TRX-1779613277027: 2 Es Teh Manis, 1 Kentang Goreng = 22000
('TRX-1779613277027', 'Es Teh Manis', 2, 5000),
('TRX-1779613277027', 'Kentang Goreng', 1, 12000),
-- TRX-1779656648028: 1 Ayam Geprek = 20000
('TRX-1779656648028', 'Ayam Geprek', 1, 20000),
-- TRX-1779697379029: 1 Es Teh Manis, 1 Kentang Goreng, 1 Nasi Goreng = 42000
('TRX-1779697379029', 'Es Teh Manis', 1, 5000),
('TRX-1779697379029', 'Kentang Goreng', 1, 12000),
('TRX-1779697379029', 'Nasi Goreng', 1, 25000),
-- TRX-1779733191030: 2 Es Kopi Susu = 36000
('TRX-1779733191030', 'Es Kopi Susu', 2, 18000),
-- TRX-1779777462031: 2 Nasi Goreng, 1 Es Teh Manis = 55000
('TRX-1779777462031', 'Nasi Goreng', 2, 25000),
('TRX-1779777462031', 'Es Teh Manis', 1, 5000),
-- TRX-1779817954032: 1 Roti Bakar, 1 Es Kopi Susu, 2 Nasi Goreng = 83000
('TRX-1779817954032', 'Roti Bakar', 1, 15000),
('TRX-1779817954032', 'Es Kopi Susu', 1, 18000),
('TRX-1779817954032', 'Nasi Goreng', 2, 25000),
-- TRX-1779857485033: 1 Kentang Goreng, 1 Nasi Goreng, 1 Es Teh Manis = 42000
('TRX-1779857485033', 'Kentang Goreng', 1, 12000),
('TRX-1779857485033', 'Nasi Goreng', 1, 25000),
('TRX-1779857485033', 'Es Teh Manis', 1, 5000),
-- TRX-1779895697034: 2 Es Kopi Susu, 1 Nasi Goreng = 61000
('TRX-1779895697034', 'Es Kopi Susu', 2, 18000),
('TRX-1779895697034', 'Nasi Goreng', 1, 25000),
-- TRX-1779935648035: 1 Ayam Geprek, 1 Nasi Goreng, 2 Es Teh Manis = 55000
('TRX-1779935648035', 'Ayam Geprek', 1, 20000),
('TRX-1779935648035', 'Nasi Goreng', 1, 25000),
('TRX-1779935648035', 'Es Teh Manis', 2, 5000),
-- TRX-1779975899036: 1 Ayam Geprek, 2 Es Teh Manis, 1 Nasi Goreng = 55000
('TRX-1779975899036', 'Ayam Geprek', 1, 20000),
('TRX-1779975899036', 'Es Teh Manis', 2, 5000),
('TRX-1779975899036', 'Nasi Goreng', 1, 25000),
-- TRX-1780013511037: 1 Roti Bakar = 15000
('TRX-1780013511037', 'Roti Bakar', 1, 15000),
-- TRX-1780055922038: 2 Es Kopi Susu, 2 Ayam Geprek, 1 Kentang Goreng = 88000
('TRX-1780055922038', 'Es Kopi Susu', 2, 18000),
('TRX-1780055922038', 'Ayam Geprek', 2, 20000),
('TRX-1780055922038', 'Kentang Goreng', 1, 12000),
-- TRX-1780093534039: 1 Kentang Goreng, 2 Es Teh Manis, 2 Roti Bakar = 52000
('TRX-1780093534039', 'Kentang Goreng', 1, 12000),
('TRX-1780093534039', 'Es Teh Manis', 2, 5000),
('TRX-1780093534039', 'Roti Bakar', 2, 15000),
-- TRX-1780132705040: 1 Roti Bakar, 2 Es Teh Manis, 1 Nasi Goreng = 50000
('TRX-1780132705040', 'Roti Bakar', 1, 15000),
('TRX-1780132705040', 'Es Teh Manis', 2, 5000),
('TRX-1780132705040', 'Nasi Goreng', 1, 25000),
-- TRX-1780173917041: 2 Kentang Goreng, 1 Es Kopi Susu, 1 Es Teh Manis = 47000
('TRX-1780173917041', 'Kentang Goreng', 2, 12000),
('TRX-1780173917041', 'Es Kopi Susu', 1, 18000),
('TRX-1780173917041', 'Es Teh Manis', 1, 5000),
-- TRX-1780215068042: 1 Es Kopi Susu = 18000
('TRX-1780215068042', 'Es Kopi Susu', 1, 18000),
-- TRX-1780253339043: 2 Roti Bakar = 30000
('TRX-1780253339043', 'Roti Bakar', 2, 15000),
-- TRX-1780296831044: 2 Nasi Goreng = 50000
('TRX-1780296831044', 'Nasi Goreng', 2, 25000),
-- TRX-1780334022045: 2 Kentang Goreng, 1 Roti Bakar = 39000
('TRX-1780334022045', 'Kentang Goreng', 2, 12000),
('TRX-1780334022045', 'Roti Bakar', 1, 15000),
-- TRX-1780378054046: 2 Roti Bakar = 30000
('TRX-1780378054046', 'Roti Bakar', 2, 15000),
-- TRX-1780417885047: 1 Es Teh Manis, 1 Ayam Geprek, 1 Kentang Goreng = 37000
('TRX-1780417885047', 'Es Teh Manis', 1, 5000),
('TRX-1780417885047', 'Ayam Geprek', 1, 20000),
('TRX-1780417885047', 'Kentang Goreng', 1, 12000),
-- TRX-1780452917048: 1 Kentang Goreng, 1 Nasi Goreng, 1 Roti Bakar = 52000
('TRX-1780452917048', 'Kentang Goreng', 1, 12000),
('TRX-1780452917048', 'Nasi Goreng', 1, 25000),
('TRX-1780452917048', 'Roti Bakar', 1, 15000),
-- TRX-1780493048049: 1 Es Kopi Susu, 2 Kentang Goreng = 42000
('TRX-1780493048049', 'Es Kopi Susu', 1, 18000),
('TRX-1780493048049', 'Kentang Goreng', 2, 12000),
-- TRX-1780534439050: 1 Nasi Goreng, 2 Kentang Goreng, 2 Ayam Geprek = 89000
('TRX-1780534439050', 'Nasi Goreng', 1, 25000),
('TRX-1780534439050', 'Kentang Goreng', 2, 12000),
('TRX-1780534439050', 'Ayam Geprek', 2, 20000),
-- TRX-1780573551051: 2 Ayam Geprek, 2 Es Teh Manis, 1 Roti Bakar = 65000
('TRX-1780573551051', 'Ayam Geprek', 2, 20000),
('TRX-1780573551051', 'Es Teh Manis', 2, 5000),
('TRX-1780573551051', 'Roti Bakar', 1, 15000),
-- TRX-1780617762052: 2 Es Kopi Susu = 36000
('TRX-1780617762052', 'Es Kopi Susu', 2, 18000),
-- TRX-1780653634053: 1 Nasi Goreng = 25000
('TRX-1780653634053', 'Nasi Goreng', 1, 25000),
-- TRX-1780693885054: 2 Nasi Goreng, 1 Es Teh Manis = 55000
('TRX-1780693885054', 'Nasi Goreng', 2, 25000),
('TRX-1780693885054', 'Es Teh Manis', 1, 5000),
-- TRX-1780733417055: 1 Kentang Goreng, 1 Es Kopi Susu = 30000
('TRX-1780733417055', 'Kentang Goreng', 1, 12000),
('TRX-1780733417055', 'Es Kopi Susu', 1, 18000),
-- TRX-1780774688056: 2 Ayam Geprek = 40000
('TRX-1780774688056', 'Ayam Geprek', 2, 20000),
-- TRX-1780811234057: 1 Sate Ayam, 1 Kopi Hitam = 35000
('TRX-1780811234057', 'Sate Ayam', 1, 25000),
('TRX-1780811234057', 'Kopi Hitam', 1, 10000),
-- TRX-1780852345058: 2 Sate Ayam, 1 Kopi Hitam = 60000
('TRX-1780852345058', 'Sate Ayam', 2, 25000),
('TRX-1780852345058', 'Kopi Hitam', 1, 10000),
-- TRX-1780893456059: 1 Sate Ayam, 2 Kopi Hitam = 45000
('TRX-1780893456059', 'Sate Ayam', 1, 25000),
('TRX-1780893456059', 'Kopi Hitam', 2, 10000),
-- TRX-1780934567060: 3 Sate Ayam = 75000
('TRX-1780934567060', 'Sate Ayam', 3, 25000),
-- TRX-1780975678061: 1 Sate Ayam = 25000
('TRX-1780975678061', 'Sate Ayam', 1, 25000),
-- TRX-1781016789062: 2 Sate Ayam, 3 Kopi Hitam = 80000
('TRX-1781016789062', 'Sate Ayam', 2, 25000),
('TRX-1781016789062', 'Kopi Hitam', 3, 10000);
