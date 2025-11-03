<?php
session_start();
require_once "../config/koneksi.php";

// Cek login admin
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Handle konfirmasi / hapus pesanan
if(isset($_GET['confirm'])){
    $id = intval($_GET['confirm']);
    $koneksi->query("UPDATE pesanan SET status='Terkonfirmasi' WHERE id=$id");
    header("Location: orders.php");
    exit();
}
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $koneksi->query("DELETE FROM pesanan WHERE id=$id");
    header("Location: orders.php");
    exit();
}

// Ambil semua pesanan
$result = $koneksi->query("SELECT * FROM pesanan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Pesanan — Admin</title>
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
.container{max-width:1000px; margin:20px auto; padding:0 20px;}
.card{background:var(--card); border-radius:14px; padding:20px; box-shadow:0 12px 30px var(--shadow);}
h2{color:var(--accent-dark); text-align:center; margin-bottom:20px;}
.table-orders{width:100%; border-collapse:collapse;}
.table-orders th, .table-orders td{padding:12px; border-bottom:1px solid #eee; text-align:left;}
.table-orders th{background:#f7efe6; color:var(--accent-dark);}
.status{
    padding:4px 10px; border-radius:12px; font-weight:600; font-size:14px;
}
.status-pending{background:#ffc107; color:#fff;}
.status-confirmed{background:#28a745; color:#fff;}
.btn{
    display:inline-block; padding:6px 12px; border-radius:8px; text-decoration:none; font-weight:600; margin-right:5px; color:#fff;
    transition: transform .15s, box-shadow .15s;
}
.btn-view{background:#17a2b8;}
.btn-view:hover{transform: translateY(-2px); box-shadow:0 8px 20px var(--shadow);}
.btn-confirm{background:#28a745;}
.btn-confirm:hover{transform: translateY(-2px); box-shadow:0 8px 20px var(--shadow);}
.btn-delete{background:#dc3545;}
.btn-delete:hover{transform: translateY(-2px); box-shadow:0 8px 20px var(--shadow);}
@media(max-width:768px){
    .table-orders th, .table-orders td{font-size:14px; padding:8px;}
}
</style>
</head>
<body>

<header class="topbar">
    <h1>Admin Dashboard — Daftar Pesanan</h1>
</header>

<div class="container">
    <div class="card">
        <h2>Semua Pesanan</h2>
        <table class="table-orders">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pembeli</th>
                    <th>Metode</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['metode']) ?></td>
                    <td>Rp <?= number_format($row['total'],0,',','.') ?></td>
                    <td>
                        <?php 
                        $status = $row['status'];
                        $class = $status=='Terkonfirmasi' ? 'status-confirmed' : 'status-pending';
                        ?>
                        <span class="status <?= $class ?>"><?= $status ?></span>
                    </td>
                    <td>
                        <a href="view_order.php?id=<?= $row['id'] ?>" class="btn btn-view">Lihat</a>
                        <?php if($status != 'Terkonfirmasi'): ?>
                        <a href="orders.php?confirm=<?= $row['id'] ?>" class="btn btn-confirm">Konfirmasi</a>
                        <?php endif; ?>
                        <a href="orders.php?delete=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Hapus pesanan ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
