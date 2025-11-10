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
$sql = "SELECT k.produk_id, k.jumlah, p.nama_produk, p.harga, p.gambar 
        FROM keranjang k
        JOIN produk p ON k.produk_id = p.id
        WHERE k.session_id = '".session_id()."'";
$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Keranjang - ChocoLove</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
/* =================== Gaya tetap sama =================== */
:root{--coklat:#8b4513;--coklat-muda:#d7b89e;--bg:#fff8f2;--merah:#e74c3c;--gradasi:linear-gradient(135deg,#a86b4f,#8b4513);}
body{margin:0;font-family:'Poppins',sans-serif;background:var(--bg);}
header{background:var(--gradasi);color:white;padding:16px;text-align:center;font-size:19px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:10px;box-shadow:0 2px 8px rgba(0,0,0,0.15);}
header i{font-size:22px;}
.container{width:90%;max-width:900px;margin:20px auto 90px;}
.cart-item{background:#fff;border-radius:14px;padding:12px;display:flex;align-items:center;gap:14px;box-shadow:0 3px 10px rgba(0,0,0,0.08);margin-bottom:14px;transition: transform 0.2s ease, box-shadow 0.3s;}
.cart-item:hover{transform: scale(1.01);box-shadow: 0 4px 12px rgba(0,0,0,0.15);}
.cart-item img{width:85px;height:85px;border-radius:10px;object-fit:cover;}
.item-info{flex:1;}
.item-info h3{margin:0;font-size:15px;font-weight:600;}
.item-info p{margin:4px 0;color:var(--coklat);font-weight:600;}
.qty-control{display:flex;align-items:center;gap:6px;}
.qty-control button{width:28px;height:28px;background:var(--coklat);color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:bold;transition:background 0.3s;}
.qty-control button:hover{background:#a86b4f;}
.qty-control input{width:36px;text-align:center;border:1px solid #ccc;border-radius:6px;padding:4px;}
.delete-btn{background:none;border:none;color:var(--merah);font-size:22px;font-weight:bold;cursor:pointer;transition:transform 0.2s;}
.delete-btn:hover{transform:scale(1.2);}
.checkout-bar{position:fixed;bottom:0;left:0;width:100%;background:#fff;border-top:1px solid #ddd;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 -2px 8px rgba(0,0,0,0.15);}
.checkout-bar .total{font-weight:600;color:var(--coklat);font-size:16px;}
.checkout-bar button{background:var(--gradasi);color:white;border:none;border-radius:10px;padding:12px 22px;font-size:15px;font-weight:bold;cursor:pointer;transition:transform 0.2s ease, opacity 0.3s;}
.checkout-bar button:hover{transform:scale(1.05);opacity:0.9;}
@media (max-width:600px){.cart-item{flex-direction:column;align-items:flex-start;}.cart-item img{width:100%;height:180px;}.qty-control{margin-top:8px;}.checkout-bar{flex-direction:column;gap:8px;}}
</style>
</head>
<body>

<header><i>🛒</i> Keranjang Belanja</header>

<main class="container" id="cart-list">
<?php if (mysqli_num_rows($result) > 0): ?>
  <?php while($row = mysqli_fetch_assoc($result)): ?>
  <div class="cart-item" data-id="<?= $row['produk_id'] ?>">
    <input type="checkbox" class="select-item" checked>
    <img src="assets/img/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_produk']) ?>">
    <div class="item-info">
      <h3><?= htmlspecialchars($row['nama_produk']) ?></h3>
      <p class="price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
    </div>
    <div class="qty-control">
      <button class="minus">−</button>
      <input type="text" value="<?= $row['jumlah'] ?>" class="qty" readonly>
      <button class="plus">+</button>
    </div>
    <button class="delete-btn">&times;</button>
  </div>
  <?php endwhile; ?>
<?php else: ?>
  <p>Keranjang kamu masih kosong 😢</p>
<?php endif; ?>
</main>

<div class="checkout-bar">
  <div class="total">Total: <span id="total-harga">Rp 0</span></div>
  <button id="checkout-btn">Checkout</button>
</div>

<script>
// Hitung total harga
function updateTotal(){
  let total = 0;
  document.querySelectorAll('.cart-item').forEach(item=>{
    if(item.querySelector('.select-item').checked){
      let price = parseInt(item.querySelector('.price').innerText.replace(/[^\d]/g,""));
      let qty = parseInt(item.querySelector('.qty').value);
      total += price * qty;
    }
  });
  document.getElementById('total-harga').innerText = "Rp "+total.toLocaleString();
}
updateTotal();

// AJAX ke update_cart.php
function ajaxUpdateCart(action, produk_id, jumlah=1){
  fetch('update_cart.php',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:`action=${action}&produk_id=${produk_id}&jumlah=${jumlah}`
  }).then(res=>res.json()).then(data=>{
    if(data.status==='success') updateTotal();
    else alert(data.msg);
  });
}

// Tombol plus
document.querySelectorAll('.plus').forEach(btn=>{
  btn.onclick = ()=>{
    let qty = btn.previousElementSibling;
    qty.value = parseInt(qty.value)+1;
    let produkId = btn.closest('.cart-item').dataset.id;
    ajaxUpdateCart('update', produkId, parseInt(qty.value));
  };
});

// Tombol minus
document.querySelectorAll('.minus').forEach(btn=>{
  btn.onclick = ()=>{
    let qty = btn.nextElementSibling;
    if(qty.value>1) qty.value = parseInt(qty.value)-1;
    let produkId = btn.closest('.cart-item').dataset.id;
    ajaxUpdateCart('update', produkId, parseInt(qty.value));
  };
});

// Tombol delete
document.querySelectorAll('.delete-btn').forEach(btn=>{
  btn.onclick = ()=>{
    let item = btn.closest('.cart-item');
    let produkId = item.dataset.id;
    ajaxUpdateCart('delete', produkId);
    item.remove();
    updateTotal();
  };
});

// Checkbox update total
document.querySelectorAll('.select-item').forEach(chk=>chk.onchange=updateTotal);

// Checkout
document.getElementById('checkout-btn').onclick = ()=>{
  alert('Fitur checkout menyusul 😉');
};
</script>
</body>
</html>
