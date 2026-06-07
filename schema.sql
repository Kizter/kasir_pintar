CREATE DATABASE IF NOT EXISTS pos_kasir;
USE pos_kasir;

CREATE TABLE IF NOT EXISTS products (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    icon VARCHAR(255) NOT NULL,
    price INT NOT NULL,
    cost INT NOT NULL
);

CREATE TABLE IF NOT EXISTS transactions (
    id VARCHAR(50) PRIMARY KEY,
    date DATETIME NOT NULL,
    total_revenue INT NOT NULL,
    total_cost INT NOT NULL,
    profit INT NOT NULL,
    cash_received INT NOT NULL,
    change_amount INT NOT NULL
);

CREATE TABLE IF NOT EXISTS transaction_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id VARCHAR(50) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price INT NOT NULL,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS capital_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    amount INT NOT NULL,
    date DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS monthly_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    month VARCHAR(7) NOT NULL,
    type ENUM('income','expense') NOT NULL,
    description VARCHAR(255) NOT NULL,
    amount INT NOT NULL,
    date DATETIME NOT NULL
);

-- Memasukkan Data Dummy (Menu Awal)
INSERT INTO products (id, name, icon, price, cost) VALUES
(1700000000001, 'Es Kopi Susu', 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 18000, 8000),
(1700000000002, 'Nasi Goreng', 'https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 25000, 15000),
(1700000000003, 'Es Teh Manis', 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80', 5000, 2000);
