<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Ambil data keranjang dari database
$sql = "SELECT k.produk_id, k.jumlah, p.nama_produk, p.harga 
        FROM keranjang k
        JOIN produk p ON k.produk_id = p.id
        WHERE k.session_id = '".session_id()."'";
$result = mysqli_query($koneksi, $sql);

// Hitung total
$cart = [];
$total = 0;
while($row = mysqli_fetch_assoc($result)){
    $cart[] = [
        'nama' => $row['nama_produk'],
        'qty' => $row['jumlah'],
        'harga' => $row['harga']
    ];
    $total += $row['jumlah'] * $row['harga'];
}

// Form submit
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nama = $_POST['nama'];
    $nohp = $_POST['nohp'];
    $alamat = $_POST['alamat'];
    $catatan = $_POST['catatan'];
    $metode = $_POST['metode'];

    // Simpan data pesanan ke session sementara (bisa diganti ke DB)
    $_SESSION['pesanan'] = [
        'nama' => $nama,
        'nohp' => $nohp,
        'alamat' => $alamat,
        'catatan' => $catatan,
        'metode' => $metode,
        'items' => $cart,
        'total' => $total
    ];

    // Kosongkan keranjang setelah checkout
    mysqli_query($koneksi, "DELETE FROM keranjang WHERE session_id='".session_id()."'");

    header("Location: pesanan_sukses.php");
    exit();
}
?>
