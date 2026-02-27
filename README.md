HEAD

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

# The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

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
2. **Seller (Dashboard)**: Manajemen produk toko sendiri, mengunggah galeri, dan memantau status pengiriman (_Shipping Status_).
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
    ```
2. **Konfigurasi Environment**:
   Salin .env.example menjadi .env dan masukkan konfigurasi database serta Midtrans Server Key Anda.
3. **Migrate & Seed**:
    ```bash
    - php artisan migrate --seed
    ```
4. **Jalankan Server**:
    ```bash
    - php artisan serve
    ```

12774860bcf23a95231cef89413a08400fc6ef35
