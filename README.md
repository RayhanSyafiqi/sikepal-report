Panduan Instalasi Lokal

1. Install XAMPP
Download dan install XAMPP dari situs resmi: https://www.apachefriends.org/
Jalankan XAMPP Control Panel, lalu start Apache dan MySQL.

2. Copy Aplikasi
Pindahkan folder aplikasi ke direktori htdocs: C:\xampp\htdocs\sikepal-report

3. Setup Database
Buka phpMyAdmin melalui browser: http://localhost/phpmyadmin
Buat database baru dengan nama `db_sikepal`.
Import file `db_sikepal.sql` yang ada di folder aplikasi ke database tersebut.

4. Setup Cloudinary
Daftar akun Cloudinary di: https://cloudinary.com/
Setelah login, catat credentials berikut:
- Cloud Name
- API Key
- API Secret

Buat Upload Preset dengan pengaturan:
- Name: `sikepal_upload`
- Folder: `sikepal`
- Signing Mode: `Unsigned`

5. Konfigurasi Aplikasi
Buka file `app/config/database.php` dan isi credentials Cloudinary:
```php
define('CLOUDINARY_CLOUD_NAME', 'your_cloud_name');
define('CLOUDINARY_API_KEY', 'your_api_key');
define('CLOUDINARY_API_SECRET', 'your_api_secret');
 
6. Jalankan aplikasi xampp
nyalakan Apache

7. Jalankan Aplikasi
Buka browser dan akses : http://localhost/sikepal-report/

**Akun default Admin
Email : admin@onigiri.com
Password : password

**Akun default Sales
Email : sales@onigiri.com
Password : password
