<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 🏨 Web Joki Hotel

Aplikasi pemesanan kamar hotel berbasis **Laravel**.  
Proyek ini dirancang untuk memudahkan pengelolaan data kamar, pengguna, dan transaksi pemesanan dalam satu sistem terpadu.

---

## 🚀 Fitur Utama

- ✅ Autentikasi pengguna (login & registrasi)
- 🏠 Manajemen data kamar (CRUD)
- 📅 Pemesanan kamar hotel
- 👥 Manajemen pengguna & admin
- 💾 Migrasi & seeding database otomatis
- 💻 Antarmuka berbasis web responsif

---

## ⚙️ Prasyarat

Pastikan sistem kamu sudah terpasang:

| Komponen | Versi Minimum |
|-----------|----------------|
| PHP | 8.0 atau lebih baru |
| Composer | Terinstal |
| MySQL / MariaDB | 10.x / 5.7 |
| Node.js & NPM *(opsional)* | 14+ |
| Web Server | Apache / Nginx / Laravel Built-in Server |

---

## 🧩 Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/ahmad-imtiyaz/web-joki-hotel.git
   cd web-joki-hotel
Install Dependency

composer install


Salin File Konfigurasi

cp .env.example .env


Atur Konfigurasi Database
Edit file .env sesuai pengaturan lokal kamu:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_joki_hotel
DB_USERNAME=root
DB_PASSWORD=


Generate App Key

php artisan key:generate


Migrasi Database
Jalankan perintah berikut untuk membuat tabel:

php artisan migrate


Jika terdapat seeder (contoh data awal), jalankan:

php artisan db:seed


Jalankan Server

php artisan serve


Akses aplikasi di:
👉 http://localhost:8000

🗃️ Migrasi Database
Menjalankan Migrasi Baru
php artisan migrate

Mengembalikan Migrasi Terakhir
php artisan migrate:rollback

Mengulang Semua Migrasi
php artisan migrate:fresh --seed


💡 Pastikan koneksi database di .env benar sebelum menjalankan migrasi.

🔐 Login & Akses
Akun Default (Jika Seeder Aktif)
Email: admin@example.com
Password: password

Langkah Login

Jalankan php artisan serve

Buka browser → http://localhost:8000

Pilih Login

Masukkan kredensial di atas

Setelah login berhasil, pengguna diarahkan ke Dashboard Admin

Jika akun default tidak tersedia, buat pengguna baru melalui fitur Register atau tambahkan manual di database.

📂 Struktur Folder Penting
Folder	Deskripsi
app/	Berisi controller, model, middleware, dan logika aplikasi
database/	File migrasi, seeder, dan factory
resources/views/	Template Blade (UI tampilan web)
routes/web.php	Definisi rute utama aplikasi
public/	Folder akses publik (CSS, JS, gambar)
config/	Pengaturan konfigurasi aplikasi Laravel
👨‍💻 Kontribusi

Ingin berkontribusi pada proyek ini?

Fork repository ini

Buat branch baru:

git checkout -b fitur-baru


Commit perubahan:

git commit -m "Menambahkan fitur baru"


Push ke branch:

git push origin fitur-baru


Buat pull request di GitHub

📜 Lisensi

Proyek ini dirilis dengan lisensi MIT License.
Silakan digunakan dan dimodifikasi secara bebas dengan tetap mencantumkan atribusi pengembang asli.

🧠 Pengembang

Ahmad Imtiyaz
📧 imtiyaznajih8@gmail.com
🌐 https://github.com/ahmad-imtiyaz

🏁 Penutup

Jika kamu mengalami kendala saat instalasi atau migrasi database, jalankan perintah berikut untuk debugging:

php artisan config:clear
php artisan cache:clear
php artisan migrate:fresh --seed


Setelah itu, coba jalankan ulang server dengan:

php artisan serve


Aplikasi Web Joki Hotel kini siap digunakan 🎉
