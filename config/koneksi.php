<?php
$host = "localhost";      // Host server
$user = "root";           // Username MySQL (default XAMPP)
$pass = "";               // Password MySQL (kosong jika pakai XAMPP)
$db   = "tokokue";   // Nama database kamu

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
