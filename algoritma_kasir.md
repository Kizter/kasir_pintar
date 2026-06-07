# Algoritma Use Case Aplikasi Kasir Sederhana

Dokumen ini memuat langkah-langkah logis (algoritma) untuk setiap *Use Case* utama pada aplikasi kasir sesuai dengan prinsip **Clean Architecture**. Algoritma ini dirancang agnostik (tidak terikat pada bahasa pemrograman tertentu).

---

## 1. Algoritma Menambah Produk ke Keranjang (`addProductToCart`)
**Tujuan:** Memasukkan produk pilihan pelanggan ke dalam keranjang belanja atau memperbarui jumlahnya jika sudah ada.
**Input:** Data Produk (`product`), Jumlah (`quantity`).

**Langkah-langkah:**
1. Mulai.
2. Terima *input* `product` dan `quantity`.
3. Lakukan pencarian pada daftar `cart` untuk melihat apakah ID `product` tersebut sudah ada.
4. **Jika YA (Sudah Ada)**:
   - Tambahkan nilai `quantity` baru ke atribut jumlah item yang sudah ada di `cart`.
   - *Opsional:* Jika jumlah menjadi <= 0, hapus item dari keranjang.
5. **Jika TIDAK (Belum Ada)**:
   - Buat entitas `CartItem` baru yang memuat `product` dan `quantity` awal.
   - Masukkan `CartItem` tersebut ke dalam daftar `cart`.
6. Simpan state keranjang terbaru.
7. *Trigger* pembaruan antarmuka (UI).
8. Selesai.

---

## 2. Algoritma Menghitung Total Belanja (`calculateTotal`)
**Tujuan:** Mengetahui jumlah total uang yang harus dibayar pelanggan berdasarkan isi keranjang.
**Output:** Nilai Total (Angka).

**Langkah-langkah:**
1. Mulai.
2. Inisialisasi variabel `total` dengan nilai `0`.
3. Lakukan perulangan (loop) untuk setiap `item` di dalam `cart`:
   - Hitung subtotal item: Harga `item.product.price` dikalikan dengan `item.quantity`.
   - Tambahkan subtotal tersebut ke dalam variabel `total`.
4. Selesai perulangan.
5. Kembalikan nilai `total`.
6. Selesai.

---

## 3. Algoritma Proses Pembayaran (`checkout` / `processPayment`)
**Tujuan:** Menyelesaikan transaksi, menyimpan riwayat, dan menghitung uang kembalian.
**Input:** Uang Tunai Pelanggan (`cashProvided`).
**Output:** Struk Transaksi (`transaction`) atau lempar *Exception* (Pesan Error).

**Langkah-langkah:**
1. Mulai.
2. Cek apakah daftar `cart` kosong. Jika kosong, batalkan dengan pesan "Keranjang kosong!".
3. Jalankan algoritma **Menghitung Total Belanja**. Simpan hasilnya ke variabel `total`.
4. Bandingkan `cashProvided` dengan `total`.
5. **Jika `cashProvided` < `total`**:
   - Batalkan transaksi.
   - Lempar *Error*: "Uang tunai tidak mencukupi!".
6. **Jika `cashProvided` >= `total`**:
   - Hitung kembalian (`change`) = `cashProvided` - `total`.
   - Buat entitas **Transaksi** baru yang berisi data: 
     - `id` (Teks unik, contoh: Timestamp)
     - `date` (Waktu saat ini)
     - `items` (Salinan dari daftar `cart`)
     - `total`
     - `cash` (Uang yang diberikan)
     - `change` (Uang kembalian)
   - Kirim entitas Transaksi ke modul `Repository` untuk disimpan secara permanen (ke database / LocalStorage).
   - Kosongkan daftar `cart` (Reset menjadi array kosong).
   - Kembalikan entitas Transaksi ke Pemanggil (UI) untuk dicetak sebagai struk nota.
7. Selesai.

---

## 4. Algoritma Memuat Riwayat Transaksi (Opsional)
**Tujuan:** Menampilkan daftar transaksi masa lalu yang telah berhasil.

**Langkah-langkah:**
1. Mulai.
2. Panggil metode `getAll()` dari *Interface* `Repository`.
3. Akses basis data (misalnya `localStorage.getItem('transactions')`).
4. **Jika data kosong**, kembalikan Array kosong `[]`.
5. **Jika ada**, *parse* data menjadi bentuk *Object* dan kembalikan ke UI.
6. Selesai.

---

## 5. Algoritma Penanganan Error (Error Handling & Fallbacks)
**Tujuan:** Menjaga antarmuka aplikasi agar tidak macet (*hang/crash*) ketika terjadi anomali pada sisi *backend* (misalnya database mati atau respons server rusak).

**Langkah-langkah Penanganan Koneksi API / Database:**
1. Modul (misalnya `Repository`) memulai proses permintaan asinkronus (*Fetch*) ke Server API.
2. Proses dibungkus dalam blok pelindung `try-catch`.
3. **Jika permintaan gagal di tengah jalan** (koneksi internet terputus / server mati):
   - Blok `catch` menangkap *Error*.
   - Lempar data kembali dalam bentuk struktur kosong atau penanda *error*.
4. **Jika permintaan selesai**, *parsing* nilai kembalian JSON dari Server API.
5. Fungsi perender UI (Tampilan) memverifikasi data tersebut (contoh: dengan memvalidasi `Array.isArray(data)`).
6. **Jika tipe data tidak sesuai (Server memunculkan pesan error)**:
   - Hentikan algoritma perenderan normal.
   - Ambil pesan *error* dari objek JSON (contoh: `data.message`).
   - Tampilkan pesan *error* tersebut secara visual di layar (*UI Alert*).
7. **Jika tipe data sesuai (Valid)**:
   - Lanjutkan perenderan data.
8. Selesai.

**Langkah-langkah Penanganan Gambar Gagal Dimuat (Fallback Image):**
1. Mulai memuat gambar (*render* elemen `<img>` dengan `src` bawaan).
2. **Jika gambar tidak ditemukan** (File korup atau link mati), maka *trigger event* `onerror`.
3. Atribut `onerror` langsung menimpa *source* `src` dengan *link* gambar bawaan (*Placeholder/No-Image*).
4. Selesai.
