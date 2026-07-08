<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_sikepal');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}


define('CLOUDINARY_CLOUD_NAME', 'zowgpttd');           
define('CLOUDINARY_API_KEY', '385615215161585');        
define('CLOUDINARY_API_SECRET', 'zRvGY-NjBWclC5PMDyWgOszZI4o'); // ⚠️ GANTI DENGAN API SECRET ANDA!
define('CLOUDINARY_FOLDER', 'sikepal');                 
define('CLOUDINARY_UPLOAD_PRESET', 'sikepal_upload');   
?>