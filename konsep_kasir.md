# Konsep Program Website Kasir Sederhana (Point of Sale)

Dokumen ini berisi konsep, alasan, manfaat, dan dokumentasi arsitektur untuk pengembangan aplikasi web kasir sederhana. Aplikasi ini dirancang menggunakan prinsip **Clean Architecture** agar modular, mudah dipelihara, dan dapat dikembangkan lebih lanjut.

---

## 1. Alasan Pengembangan
* **Digitalisasi UMKM:** Banyak usaha kecil (seperti warung, kafe kecil, atau toko kelontong) yang masih menggunakan pencatatan manual berbasis kertas. Hal ini rentan terhadap kehilangan data dan kesalahan hitung.
* **Kebutuhan Pencatatan Real-time:** Membutuhkan sistem yang dapat mencatat transaksi saat itu juga secara akurat tanpa memerlukan spesifikasi perangkat keras (hardware) yang tinggi (cukup menggunakan browser web).
* **Aksesibilitas:** Berbasis website, sehingga dapat diakses melalui berbagai perangkat seperti PC, tablet, maupun smartphone tanpa perlu instalasi aplikasi khusus.

---

## 2. Manfaat
* **Efisiensi dan Kecepatan:** Mempercepat proses pelayanan pelanggan di meja kasir.
* **Akurasi Perhitungan:** Mengurangi *human error* atau kesalahan matematis saat menghitung total belanjaan dan kembalian.
* **Riwayat Tersimpan:** Seluruh data transaksi otomatis tersimpan, sehingga pemilik dapat melihat riwayat pendapatan per hari.
* **Keamanan Data Transaksi:** Meminimalisir risiko hilangnya catatan akibat kertas rusak atau hilang.

---

## 3. Dokumentasi Arsitektur (Clean Architecture)

Aplikasi akan dibagi menjadi beberapa *layer* (lapisan) independen. Perubahan pada UI (tampilan) tidak akan mempengaruhi logika bisnis, dan sebaliknya. Ini memastikan kode terstruktur sesuai dengan panduan dan *clean architecture*.

### A. Pembagian Layer
1. **Entities (Domain Layer):** Aturan bisnis utama / objek fundamental.
2. **Use Cases (Application Layer):** Alur kerja atau logika fungsional aplikasi (Tambah ke keranjang, hitung total, proses pembayaran).
3. **Interface Adapters (Presenter/Controller):** Jembatan antara logika aplikasi dengan UI atau database eksternal.
4. **Frameworks & Drivers (UI & Data Layer):** Antarmuka pengguna (HTML/CSS) dan penyimpanan aktual (LocalStorage/API).

### B. Contoh Konkret Implementasi (JavaScript)

Berikut adalah referensi *source code* inti dengan memisahkan logika aplikasi dari tampilannya (UI):

```javascript
// ==========================================
// 1. ENTITIES (Domain Layer)
// ==========================================
class Product {
    constructor(id, name, price) {
        this.id = id;
        this.name = name;
        this.price = price;
    }
}

class CartItem {
    constructor(product, quantity) {
        this.product = product;
        this.quantity = quantity;
    }

    getSubtotal() {
        return this.product.price * this.quantity;
    }
}

// ==========================================
// 2. USE CASES (Application Layer)
// ==========================================
class CashierUseCase {
    constructor(transactionRepository) {
        this.cart = [];
        this.transactionRepository = transactionRepository; // Dependency Injection
    }

    addProductToCart(product, quantity = 1) {
        const existingItem = this.cart.find(item => item.product.id === product.id);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            this.cart.push(new CartItem(product, quantity));
        }
    }

    calculateTotal() {
        return this.cart.reduce((total, item) => total + item.getSubtotal(), 0);
    }

    processPayment(cashProvided) {
        const total = this.calculateTotal();
        
        if (cashProvided < total) {
            throw new Error("Nominal uang tunai kurang dari total belanja!");
        }

        const transaction = {
            id: 'TRX-' + Date.now(),
            date: new Date().toISOString(),
            items: [...this.cart],
            total: total,
            cash: cashProvided,
            change: cashProvided - total
        };

        // Simpan transaksi melalui repository
        this.transactionRepository.save(transaction);
        
        // Bersihkan keranjang setelah berhasil
        this.cart = []; 
        
        return transaction; // Kembalikan struk/nota
    }
}

// ==========================================
// 3. INTERFACE ADAPTERS (Data & Controller Layer)
// ==========================================

// Repository Interface Adapter (Bisa menggunakan LocalStorage, IndexedDB, atau API)
class LocalStorageTransactionRepo {
    save(transaction) {
        const history = JSON.parse(localStorage.getItem('pos_transactions') || '[]');
        history.push(transaction);
        localStorage.setItem('pos_transactions', JSON.stringify(history));
    }
    
    getAll() {
        return JSON.parse(localStorage.getItem('pos_transactions') || '[]');
    }
}

// ==========================================
// 4. FRAMEWORKS & DRIVERS (UI / View Layer)
// (Contoh pemanggilan di sisi antarmuka HTML/DOM)
// ==========================================
/*
const repo = new LocalStorageTransactionRepo();
const cashier = new CashierUseCase(repo);

// Simulasi Interaksi UI
const mieGoreng = new Product(1, "Mie Goreng", 3000);
const esTeh = new Product(2, "Es Teh Manis", 5000);

cashier.addProductToCart(mieGoreng, 2); // 6000
cashier.addProductToCart(esTeh, 1);     // 5000
// Total = 11000

try {
    const receipt = cashier.processPayment(15000);
    console.log("Transaksi Berhasil!", receipt);
    console.log("Kembalian:", receipt.change); // 4000
} catch (error) {
    alert(error.message);
}
*/
```

---

## 4. Panduan Antarmuka & Ekstensibilitas
Untuk bagian antarmuka web, disarankan menggunakan desain fungsional:
1. **Katalog Produk:** Grid yang berisi kartu produk beserta harga (diambil dari sisi Framework/UI).
2. **Keranjang:** Daftar item yang dipilih beserta subtotal dan interaksi (tambah/kurang stok).
3. **Checkout:** Panel ringkasan untuk memproses pembayaran dan mengkalkulasi kembalian.

> **Manfaat Clean Architecture di Kasir:**
> Jika suatu saat aplikasi butuh perubahan database (misalnya beralih dari *LocalStorage* ke server *MySQL/Node.js API*), Anda **hanya perlu** membuat kelas baru (misal: `ApiTransactionRepo`) yang diinjeksi ke dalam `CashierUseCase`. Logika bisnis perhitungan harga, kembalian, dan manajemen keranjang tidak perlu dirubah sama sekali!

---

## 5. Penanganan Masalah (Error Handling & Proteksi)

Dalam arsitektur *Client-Server* (API PHP & Database MySQL), aplikasi kita dilengkapi dengan lapis perlindungan ganda (Error Handling) sebagai berikut:

* **Koneksi Database Putus / Gagal:** 
  Jika *backend* PHP gagal menyambung ke MySQL (misalnya *driver* MySQLi belum menyala atau XAMPP mati), sistem tidak akan mogok (*crash*). Kode PHP akan secara otomatis menangkap `Exception` dan mengirimkan data penanda kepada antarmuka (UI) dalam format JSON yang berbunyi `{"status": "error", "message": "Koneksi Database Gagal: ..."}`.
  
* **Proteksi Pembekuan Layar (*Anti-Hang Fallback*):** 
  JavaScript di antarmuka utama (`index.php`) tidak akan membabi-buta membaca data dari *backend*. JavaScript akan melakukan validasi format (*contoh: menggunakan metode `Array.isArray()`*). Jika yang diterima ternyata adalah *pesan error* dari database, JavaScript akan menghentikan proses perenderan *grid* menu dan langsung memunculkan **pesan peringatan teks berwarna merah** di layar agar pengguna segera mengetahui sumber masalahnya.

* **Penanganan Gambar Rusak (*Broken Image Link*):** 
  Jika tautan gambar produk tidak valid, file fisik di folder *uploads* terhapus secara tak sengaja, atau koneksi internet lambat, elemen kerangka `<img>` HTML sudah ditanamkan atribut `onerror`. Atribut cerdas ini akan memotong proses muat error dan langsung mengganti gambar asli yang rusak tersebut dengan gambar *Placeholder / No-Image* standar. Hal ini memastikan susunan desain aplikasi kasir tetap rapi meskipun ada data gambar yang hilang.
