![Laravel Version](https://img.shields.io/badge/laravel-v12-red)
![PHP Version](https://img.shields.io/badge/php-v8.4-blue)
![License](https://img.shields.io/badge/license-MIT-green)

# 🛒 Store-Online: Fullstack E-Commerce with Loyalty Points & Midtrans

Store-Online adalah aplikasi e-commerce berbasis web yang dibangun menggunakan **Laravel 12**. Proyek ini tidak hanya menangani transaksi jual-beli biasa, tetapi juga mengintegrasikan sistem pembayaran otomatis dan fitur loyalitas pelanggan melalui poin.

## 🚀 Fitur Utama
- **Sistem Pembayaran Terintegrasi**: Menggunakan Midtrans Snapshot untuk menangani berbagai metode pembayaran (Gopay, Bank Transfer, Virtual Account) secara otomatis.
- **Loyalty Points System**: Pelanggan mendapatkan poin secara otomatis setelah transaksi dinyatakan sukses (`SUCCESS`) oleh Midtrans.
- **Reward Marketplace**: User dapat menukarkan poin yang telah dikumpulkan dengan berbagai reward menarik.

### Multi-User Role:
1. **Admin**: Manajemen kategori, user, produk, dan galeri produk.
2. **Seller (Dashboard)**: Manajemen produk toko sendiri, mengunggah galeri, dan memantau status pengiriman (*Shipping Status*).
3. **Buyer**: Menjelajahi produk, manajemen keranjang, dan melakukan checkout.

- **Dynamic Shipping Management**: Penjual dapat mengubah status pengiriman dari `PENDING` ke `SHIPPING` serta menginput nomor resi secara real-time.
- **Clean Architecture**: Menggunakan Controller yang terorganisir untuk memisahkan logika Dashboard User dan Admin.

## 🛠️ Stack Teknologi
- **Backend**: PHP 8.4.14, Laravel 12.
- **Database**: MySQL.
- **Frontend**: Bootstrap, Vue.js (untuk interaksi dinamis pada dashboard).
- **Payment Gateway**: Midtrans.
- **Tools**: Laragon, Composer, VS Code.

## 🔧 Cara Instalasi 
1. **Install dependencies**:
    ```bash
    - composer install
    - npm install && npm run dev
2. **Konfigurasi Environment**:
Salin .env.example menjadi .env dan masukkan konfigurasi database serta Midtrans Server Key Anda.
3. **Migrate & Seed**:
   ```bash
   - php artisan migrate --seed
4. **Jalankan Server**:
   ```bash
   - php artisan serve

