-- ====================================================================
-- KASIR PINTAR - SUPABASE SCHEMA (PostgreSQL)
-- Jalankan script ini SATU KALI di Supabase SQL Editor
-- Dashboard: https://supabase.com/dashboard → SQL Editor
-- ====================================================================

-- 1. PRODUCTS TABLE
CREATE TABLE IF NOT EXISTS products (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    icon VARCHAR(512) NOT NULL,
    price INTEGER NOT NULL,
    cost INTEGER NOT NULL
);

-- 2. TRANSACTIONS TABLE
CREATE TABLE IF NOT EXISTS transactions (
    id VARCHAR(50) PRIMARY KEY,
    date TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    total_revenue INTEGER NOT NULL,
    total_cost INTEGER NOT NULL,
    profit INTEGER NOT NULL,
    cash_received INTEGER NOT NULL,
    change_amount INTEGER NOT NULL
);

-- 3. TRANSACTION ITEMS TABLE
CREATE TABLE IF NOT EXISTS transaction_items (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    transaction_id VARCHAR(50) NOT NULL REFERENCES transactions(id) ON DELETE CASCADE,
    product_name VARCHAR(255) NOT NULL,
    quantity INTEGER NOT NULL,
    price INTEGER NOT NULL
);

-- 4. CAPITAL RECORDS TABLE
CREATE TABLE IF NOT EXISTS capital_records (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    amount INTEGER NOT NULL,
    date TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- 5. MONTHLY RECORDS TABLE
CREATE TABLE IF NOT EXISTS monthly_records (
    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    month VARCHAR(7) NOT NULL,
    type TEXT NOT NULL CHECK (type IN ('income', 'expense')),
    description VARCHAR(255) NOT NULL,
    amount INTEGER NOT NULL,
    date TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ====================================================================
-- ROW LEVEL SECURITY (RLS) — Allow All untuk Demo
-- Untuk produksi, ganti dengan policy berbasis auth.uid()
-- ====================================================================

ALTER TABLE products ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Allow all on products" ON products FOR ALL USING (true) WITH CHECK (true);

ALTER TABLE transactions ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Allow all on transactions" ON transactions FOR ALL USING (true) WITH CHECK (true);

ALTER TABLE transaction_items ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Allow all on transaction_items" ON transaction_items FOR ALL USING (true) WITH CHECK (true);

ALTER TABLE capital_records ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Allow all on capital_records" ON capital_records FOR ALL USING (true) WITH CHECK (true);

ALTER TABLE monthly_records ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Allow all on monthly_records" ON monthly_records FOR ALL USING (true) WITH CHECK (true);

-- ====================================================================
-- SUPABASE STORAGE BUCKET — untuk upload gambar produk
-- Jalankan ini di SQL Editor juga, atau buat manual via Dashboard:
-- Storage → New Bucket → Name: "product-images" → Public: ON
-- ====================================================================

INSERT INTO storage.buckets (id, name, public)
VALUES ('product-images', 'product-images', true)
ON CONFLICT (id) DO NOTHING;

-- Policy: siapa saja bisa upload dan read (demo)
CREATE POLICY "Public read product-images" ON storage.objects
    FOR SELECT USING (bucket_id = 'product-images');

CREATE POLICY "Public upload product-images" ON storage.objects
    FOR INSERT WITH CHECK (bucket_id = 'product-images');

CREATE POLICY "Public delete product-images" ON storage.objects
    FOR DELETE USING (bucket_id = 'product-images');
