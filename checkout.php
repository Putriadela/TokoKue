<?php
session_start();

// Dummy cart items (nanti bisa diganti dengan localStorage/cart DB)
$cart = [
    ['nama' => 'Chocolate Fudge Cake', 'qty' => 2, 'harga' => 75000],
    ['nama' => 'Strawberry Shortcake', 'qty' => 1, 'harga' => 65000],
];

// Hitung total
$total = 0;
foreach($cart as $item){
    $total += $item['qty'] * $item['harga'];
}

// Form submit
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nama = $_POST['nama'];
    $nohp = $_POST['nohp'];
    $alamat = $_POST['alamat'];
    $catatan = $_POST['catatan'];
    $metode = $_POST['metode'];

    // Simpan data pesanan (bisa diganti DB)
    $_SESSION['pesanan'] = [
        'nama' => $nama,
        'nohp' => $nohp,
        'alamat' => $alamat,
        'catatan' => $catatan,
        'metode' => $metode,
        'items' => $cart,
        'total' => $total
    ];

    header("Location: pesanan_sukses.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout — ChocoLove</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
:root{
  --bg: #FFF3E4; --card: #FFFFFF; --muted: #ECD9C6; --accent: #C49A6C;
  --accent-dark: #8B5E34; --text: #3b2f2a; --soft: #F7EFE6; --shadow: rgba(139,94,52,0.12);
}
*{box-sizing:border-box;}
body{margin:0;font-family:'Poppins',sans-serif;background: var(--bg); color: var(--text);}
header.topbar{
  background: linear-gradient(90deg,var(--card),var(--soft));
  padding: 14px 24px; display:flex; align-items:center; gap:12px;
  box-shadow:0 6px 18px var(--shadow);
}
header h1{margin:0; font-size:20px; color:var(--accent-dark);}
.container{max-width:800px; margin:20px auto; padding:0 20px;}
h2{margin-bottom:16px; font-size:22px; color:var(--accent-dark);}
form{display:flex; flex-direction: column; gap:20px;}
input, textarea, select{
  padding:12px 14px; border-radius:10px; border:1px solid rgba(0,0,0,0.1); font-size:16px;
  width:100%; background:var(--card); color:var(--text);
}
textarea{resize:vertical;}
.card{background:var(--card); padding:20px; border-radius:14px; box-shadow:0 12px 30px var(--shadow);}
.cart-item{display:flex; justify-content:space-between; margin-bottom:12px; font-size:15px; color:#6b5346;}
.total{font-weight:700; font-size:18px; text-align:right; margin-top:12px; color:var(--accent-dark);}
button{
  margin-top:20px; padding:16px; background: linear-gradient(180deg,var(--accent),var(--accent-dark));
  color:white; border:none; border-radius:12px; font-size:18px; font-weight:600; cursor:pointer;
  transition: transform .15s, box-shadow .15s;
}
button:hover{transform: translateY(-2px); box-shadow: 0 12px 25px var(--shadow);}
</style>
</head>
<body>

<header class="topbar">
  <h1>ChocoLove — Checkout</h1>
</header>

<div class="container">
    <h2>Data Pembeli</h2>
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="text" name="nohp" placeholder="No HP" required>
        <textarea name="alamat" rows="3" placeholder="Alamat Lengkap" required></textarea>
        <textarea name="catatan" rows="2" placeholder="Catatan Pesanan (opsional)"></textarea>

        <select name="metode" required>
            <option value="">Pilih Metode Pembayaran</option>
            <option value="E-WALLET">E-Wallet</option>
            <option value="DANA">Dana</option>
        </select>

        <div class="card">
            <h3>Ringkasan Pesanan</h3>
            <?php foreach($cart as $item): ?>
                <div class="cart-item">
                    <span><?= htmlspecialchars($item['nama']) ?> x<?= intval($item['qty']) ?></span>
                    <span>Rp <?= number_format($item['qty'] * $item['harga'],0,',','.') ?></span>
                </div>
            <?php endforeach; ?>
            <div class="total">Total: Rp <?= number_format($total,0,',','.') ?></div>
        </div>

        <button type="submit">Buat Pesanan</button>
    </form>
</div>

</body>
</html>
