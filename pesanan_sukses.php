<?php
session_start();

// Cek data pesanan
if(!isset($_SESSION['pesanan'])){
    header("Location: index.php");
    exit();
}

$pesanan = $_SESSION['pesanan'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan Berhasil — ChocoLove</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
:root{
  --bg: #FFF3E4; 
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
.container{max-width:800px; margin:20px auto; padding:0 20px;}
.card{background:var(--card); border-radius:14px; padding:25px; box-shadow:0 12px 30px var(--shadow);}
h2{color:#28a745; text-align:center; margin-bottom:25px;}
.section{margin-bottom:25px;}
.section h3{margin-bottom:12px; color:var(--accent-dark);}

/* Layout Data Pembeli vertical */
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

.table-cart{width:100%; border-collapse:collapse; margin-top:10px;}
.table-cart th, .table-cart td{padding:10px; border-bottom:1px solid #eee; text-align:left;}
.table-cart th{background: #f7efe6; color:var(--accent-dark);}
.total{font-weight:700; font-size:18px; text-align:right; margin-top:10px; color:var(--accent-dark);}
.btn-home{
  display:block; text-align:center; background: linear-gradient(180deg,var(--accent),var(--accent-dark));
  color:#fff; padding:15px; border-radius:12px; text-decoration:none; font-weight:600; margin-top:20px;
  transition: transform .15s, box-shadow .15s;
}
.btn-home:hover{transform: translateY(-2px); box-shadow:0 12px 25px var(--shadow);}
@media(max-width:480px){
    .container{padding:15px;}
    h2{font-size:22px;}
}
</style>
</head>
<body>

<header class="topbar">
  <h1>ChocoLove</h1>
</header>

<div class="container">
    <div class="card">
        <h2>Pesanan Berhasil!</h2>
        
        <div class="section info">
            <h3>Data Pembeli</h3>
            <div class="info-row"><strong>Nama:</strong><span><?= htmlspecialchars($pesanan['nama']) ?></span></div>
            <div class="info-row"><strong>No HP:</strong><span><?= htmlspecialchars($pesanan['nohp']) ?></span></div>
            <div class="info-row"><strong>Alamat:</strong><span><?= nl2br(htmlspecialchars($pesanan['alamat'])) ?></span></div>
            <div class="info-row"><strong>Catatan:</strong><span><?= nl2br(htmlspecialchars($pesanan['catatan'])) ?></span></div>
            <div class="info-row"><strong>Metode:</strong><span><?= htmlspecialchars($pesanan['metode']) ?></span></div>
        </div>

        <div class="section">
            <h3>Ringkasan Pesanan</h3>
            <table class="table-cart">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pesanan['items'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nama']) ?></td>
                        <td><?= intval($item['qty']) ?></td>
                        <td>Rp <?= number_format($item['qty'] * $item['harga'],0,',','.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total">Total: Rp <?= number_format($pesanan['total'],0,',','.') ?></div>
        </div>

        <a href="index.php" class="btn-home">Kembali ke Dashboard Kue</a>
    </div>
</div>

</body>
</html>
