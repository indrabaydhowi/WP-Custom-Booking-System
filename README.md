# WP Custom Booking System 📅⚙️

![WordPress](https://img.shields.io/badge/WordPress-%23117B85.svg?style=for-the-badge&logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%234479A1.svg?style=for-the-badge&logo=mysql&logoColor=white)

> Sistem pemesanan (booking) kustom yang dirancang khusus untuk ekosistem WordPress. Mengutamakan performa, keamanan, dan kemudahan manajemen data.

## 🎯 Latar Belakang Proyek
Proyek ini dikembangkan sebagai portofolio untuk mendemonstrasikan kemampuan pengembangan *backend* pada WordPress. Fokus utama dari repositori ini adalah implementasi *Clean Architecture* pada PHP, interaksi database yang aman menggunakan `$wpdb`, dan integrasi *hook* (Action/Filter) WordPress yang efisien.

## ✨ Fitur Utama
* **Manajemen Pemesanan Terpusat:** Mengelola data *booking* langsung dari *dashboard* admin WordPress.
* **Keamanan Data:** Implementasi sanitasi input dan *nonce* untuk mencegah eksploitasi.
* **Integrasi Frontend:** *User interface* yang responsif untuk pengguna melakukan pemesanan lengkap dengan pemilihan kursi interaktif.

## 🛠️ Teknologi & Tools
* **Core:** PHP & WordPress Core API
* **Database:** MySQL / MariaDB
* **Frontend:** HTML, Vanilla CSS, Vanilla JavaScript

## 📂 Arsitektur Direktori
```text
📦 WP-Custom-Booking-System
 ┣ 📂 admin               # File backend UI/logic (admin-dashboard.php)
 ┣ 📂 assets              # File statis (CSS, JS, Images)
 ┣ 📂 includes            # File PHP core/logic (cpt-destinasi.php, ajax-handler.php)
 ┣ 📂 public              # File frontend UI/logic (frontend-form.php)
 ┣ 📂 templates           # File HTML/PHP views
 ┣ 📜 wp-custom-booking-system.php  # File utama plugin
 ┗ 📜 README.md
```

## 🚀 Cara Instalasi
1. Unduh atau clone repositori ini ke dalam direktori WordPress Anda di `wp-content/plugins/WP-Custom-Booking-System`.
2. Masuk ke halaman **Plugins** di *dashboard* admin WordPress.
3. Cari **WP Custom Booking System** dan klik **Activate**.
4. Gunakan shortcode `[form_booking_travel]` pada halaman detail destinasi untuk menampilkan formulir.
