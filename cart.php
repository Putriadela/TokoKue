<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';

// Cek user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data keranjang
$sql = "SELECT c.id, c.jumlah, p.nama_produk, p.harga, p.gambar 
        FROM cart c 
        JOIN produk p ON c.produk_id = p.id
        WHERE c.user_id = $user_id";
$result = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - ChocoLove</title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; background:#f7e7ce; }
        h2 { background:#8b4513; color:white; padding:15px; margin:0; }

        .container { width:90%; margin:20px auto; }
        .cart-item {
            background:white; border-radius:12px; padding:15px; 
            display:flex; align-items:center; gap:15px;
            margin-bottom:12px; box-shadow:0 3px 6px rgba(0,0,0,0.2);
        }
        .cart-item img {
            width:90px; height:90px; border-radius:8px; object-fit:cover;
        }
        .item-info { flex:1; }
        .item-info h3 { margin:0 0 5px; }
        .price { color:#a52a2a; font-weight:bold; }

        .qty-control { display:flex; align-items:center; gap:8px; }
        .qty-control button {
            padding:4px 10px; background:#8b4513; color:white;
            border:none; border-radius:5px; cursor:pointer;
        }
        .qty-control input {
            width:40px; text-align:center;
            padding:5px; border-radius:6px; border:1px solid #bbb;
        }
        .delete-btn {
            cursor:pointer; background:none; border:none; color:red;
            font-size:18px; font-weight:bold;
        }

        /* Checkout Bar */
        .checkout-bar {
            position:fixed; bottom:0; left:0; width:100%;
            background:white; padding:12px 20px;
            display:flex; justify-content:space-between;
            align-items:center;
            box-shadow:0 -3px 6px rgba(0,0,0,0.2);
        }
        .checkout-btn {
            background:#8b4513; color:white; padding:12px 18px;
            border-radius:8px; font-size:16px; font-weight:bold;
            text-decoration:none;
        }
        .checkout-btn:disabled { background:#b8b8b8; cursor:not-allowed; }
    </style>
</head>
<body>

<h2>🛒 Keranjang Belanja</h2>
<div class="container" id="cart-list">

    <?php if (mysqli_num_rows($result) > 0) { 
        while($row = mysqli_fetch_assoc($result)) { ?>
        <div class="cart-item" data-id="<?= $row['id'] ?>">
            <input type="checkbox" class="select-item" checked>

            <img src="assets/img/<?= $row['gambar'] ?>" alt="<?= $row['nama_produk'] ?>">

            <div class="item-info">
                <h3><?= $row['nama_produk'] ?></h3>
                <p class="price">Rp <?= number_format($row['harga'],0,',','.') ?></p>
            </div>

            <div class="qty-control">
                <button class="minus">-</button>
                <input type="text" value="<?= $row['jumlah'] ?>" class="qty" readonly>
                <button class="plus">+</button>
            </div>

            <button class="delete-btn">&times;</button>
        </div>
    <?php }} else { ?>
        <p>Keranjang kamu masih kosong 😢</p>
    <?php } ?>

</div>

<!-- Checkout Bar -->
<div class="checkout-bar">
    <div>
        Total: <span id="total-harga" class="price">Rp 0</span>
    </div>
    <a href="checkout.php" class="checkout-btn" id="btn-checkout">Checkout</a>
</div>

<script>
// Hitung Total Harga
function updateTotal() {
    let items = document.querySelectorAll(".cart-item");
    let total = 0;

    items.forEach(item => {
        let checkbox = item.querySelector(".select-item");
        if (checkbox.checked) {
            let price = parseInt(item.querySelector(".price").innerText.replace(/[^\d]/g, ""));
            let qty = parseInt(item.querySelector(".qty").value);
            total += price * qty;
        }
    });
    document.getElementById("total-harga").innerText = "Rp " + total.toLocaleString();
}

updateTotal();

// Delete Item
document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.onclick = function() {
        this.closest(".cart-item").remove();
        updateTotal();
    }
});

// Qty + -
document.querySelectorAll(".plus").forEach(btn => {
    btn.onclick = function() {
        let qty = this.previousElementSibling;
        qty.value = parseInt(qty.value) + 1;
        updateTotal();
    }
});
document.querySelectorAll(".minus").forEach(btn => {
    btn.onclick = function() {
        let qty = this.nextElementSibling;
        if (qty.value > 1) qty.value = parseInt(qty.value) - 1;
        updateTotal();
    }
});
</script>

</body>
</html>
