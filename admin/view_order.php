<?php
session_start();
require_once "../config/koneksi.php"; // Sesuaikan path koneksi DB

// Cek login admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Ambil ID pesanan dari query string
if(!isset($_GET['id'])){
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['id']);

// Ambil data pesanan dari DB (contoh tabel `pesanan`)
$sql = $koneksi->query("SELECT * FROM pesanan WHERE id = $order_id");
$pesanan = $sql->fetch_assoc();

// Jika pakai json/array items tersimpan di DB, decode
$pesanan['items'] = json_decode($pesanan['items'], true);

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pesanan #<?= $pesanan['id'] ?> — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
:root{
  --bg: #f4f4f9; 
  --card: #FFFFFF; 
  --accent: #C49A6C; 
  --accent-dark: #8B5E34;
  --text: #3b2f2a; 
  --shadow: rgba(139,94,52,0.12);
}
*{box-sizing:border-box;}
body{margin:0; font-family:'Poppins',sans-serif; background:var(--bg); color:var(--text);}
header.topbar{
  background: linear-gradient(90deg,var(--card),#F7EFE6);
  padding:14px 24px; box-shadow:0 6px 18px var(--shadow);
}
header h1{margin:0; font-size:20px; color:var(--accent-dark);}
.container{max-width:900px; margin:20px auto; padding:0 20px;}
.card{background:var(--card); border-radius:14px; padding:25px; box-shadow:0 12px 30px var(--shadow);}
h2{color:#333; text-align:center; margin-bottom:25px;}
.section{margin-bottom:25px;}
.section h3{margin-bottom:12px; color:var(--accent-dark);}

/* Data Pembeli vertical */
.info-row{
    display:flex;
    flex-direction: column;
    margin-bottom:12px;
}
.info-row strong{
    color: var(--accent-dark);
    margin-bottom:4px;
}
.info-row span{
    color: var(--text);
}

/* Tabel pesanan */
.table-cart{width:100%; border-collapse:collapse; margin-top:10px;}
.table-cart th, .table-cart td{padding:10px; border-bottom:1px solid #eee; text-align:left;}
.table-cart th{background: #f7efe6; color:var(--accent-dark);}
.total{font-weight:700; font-size:18px; text-align:right; margin-top:10px; color:var(--accent-dark);}

/* Tombol aksi */
.btn{
  display:inline-block; padding:10px 20px; border-radius:12px; text-decoration:none; font-weight:600; margin-right:10px;
  transition: transform .15s, box-shadow .15s;
  color:#fff;
}
.btn-confirm{background: #28a745;}
.btn-confirm:hover{transform: translateY(-2px); box-shadow:0 8px 20px var(--shadow);}
.btn-delete{background: #dc3545;}
.btn-delete:hover{transform: translateY(-2px); box-shadow:0 8px 20px var(--shadow);}
@media(max-width:480px){
    .container{padding:15px;}
    h2{font-size:22px;}
}
</style>
</head>
<body>

<header class="topbar">
  <h1>Admin Dashboard — ChocoLove</h1>
</header>

<div class="container">
    <div class="card">
        <h2>Detail Pesanan #<?= $pesanan['id'] ?></h2>

        <div class="section info">
            <h3>Data Pembeli</h3>
            <div class="info-row"><strong>Nama:</strong><span><?= htmlspecialchars($pesanan['nama']) ?></span></div>
            <div class="info-row"><strong>No HP:</strong><span><?= htmlspecialchars($pesanan['nohp']) ?></span></div>
            <div class="info-row"><strong>Alamat:</strong><span><?= nl2br(htmlspecialchars($pesanan['alamat'])) ?></span></div>
            <div class="info-row"><strong>Catatan:</strong><span><?= nl2br(htmlspecialchars($pesanan['catatan'])) ?></span></div>
            <div class="info-row"><strong>Metode:</strong><span><?= htmlspecialchars($pesanan['metode']) ?></span></div>
            <div class="info-row"><strong>Status:</strong><span><?= htmlspecialchars($pesanan['status']) ?></span></div>
        </div>

        <div class="section">
            <h3>Ringkasan Pesanan</h3>
            <table class="table-cart">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pesanan['items'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nama']) ?></td>
                        <td><?= intval($item['qty']) ?></td>
                        <td>Rp <?= number_format($item['harga'],0,',','.') ?></td>
                        <td>Rp <?= number_format($item['qty'] * $item['harga'],0,',','.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total">Total: Rp <?= number_format($pesanan['total'],0,',','.') ?></div>
        </div>

        <div class="section">
            <h3>Aksi Admin</h3>
            <a href="orders.php?confirm=<?= $pesanan['id'] ?>" class="btn btn-confirm">Konfirmasi</a>
            <a href="orders.php?delete=<?= $pesanan['id'] ?>" class="btn btn-delete" onclick="return confirm('Hapus pesanan ini?')">Hapus</a>
        </div>
    </div>
</div>

</body>
</html>
