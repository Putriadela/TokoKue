<?php
include 'config/koneksi.php';
session_start();

// cek login
$loggedIn = isset($_SESSION['user']);

// ambil produk dari database
$q = "SELECT * FROM produk ORDER BY id DESC";
$res = mysqli_query($koneksi, $q);
$products = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $products[] = $row;
}

}

?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ChocoLove — Toko Kue Online</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    /* CSS Style */
    :root {
      --bg: #FFF3E4; --card: #FFFFFF; --muted: #ECD9C6; --accent: #C49A6C;
      --accent-dark: #8B5E34; --text: #3b2f2a; --soft: #F7EFE6; --shadow: rgba(139,94,52,0.12);
      --maxwidth: 1200px;
    }
    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(180deg, var(--bg), #fff 60%);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    /* HEADER */
    header.topbar {
      position: sticky; top: 0; z-index: 100; background: linear-gradient(90deg, var(--card), var(--soft));
      border-bottom: 1px solid rgba(0, 0, 0, 0.04); backdrop-filter: blur(4px); padding: 12px 20px;
      display: flex; align-items: center; justify-content: space-between; gap: 12px;
      box-shadow: 0 6px 18px var(--shadow);
    }
    .brand { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .logo-wrap { width: 45px; height: 45px; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(139,94,52,0.15); flex: 0 0 45px; }
    .logo-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .brand-text h1 { font-size: 17px; margin: 0; color: var(--accent-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .brand-text p { margin: 0; font-size: 11px; color: #6b5346; line-height: 12px; }

    /* Controls */
    .controls { display: flex; gap: 12px; align-items: center; flex: 1; margin-left: 16px; min-width: 0; }
    .searchbox { flex: 1; display: flex; align-items: center; gap: 8px; background: var(--card); border-radius: 12px; padding: 10px 12px; border: 1px solid rgba(0, 0, 0, 0.03); box-shadow: 0 6px 18px var(--shadow); }
    .searchbox input { border: 0; outline: 0; font-size: 14px; background: transparent; width: 100%; }
    .select, .select-sm { background: var(--card); border-radius: 12px; padding: 8px 12px; font-size: 14px; border: 1px solid rgba(0, 0, 0, 0.03); box-shadow: 0 6px 18px var(--shadow); }
    
    .nav-right { display: flex; align-items: center; gap: 10px; margin-left: auto; }
    .icon-btn { display: inline-flex; align-items: center; justify-content: center; background: var(--soft); color: var(--accent-dark); border-radius: 10px; padding: 8px; cursor: pointer; border: none; font-weight: 700; box-shadow: 0 6px 14px rgba(0, 0, 0, 0.04); }
    .icon-btn:active { transform: translateY(1px); }
    .icon-badge { font-size: 12px; margin-left: 6px; }

    /* Layout wrapper */
    .wrap { max-width: var(--maxwidth); margin: 20px auto; padding: 0 16px; }

    /* Product grid */
    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-top: 12px; }
    .card { background: linear-gradient(180deg, var(--card), #fff); border-radius: 16px; padding: 12px; overflow: hidden; border: 1px solid rgba(0, 0, 0, 0.03); transition: transform .18s ease, box-shadow .18s; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04); display: flex; flex-direction: column; }
    .card:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(139, 94, 52, 0.12); }
    .imgwrap { width: 100%; height: 200px; overflow: hidden; background: var(--muted); display: flex; align-items: center; justify-content: center; }
    .imgwrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .meta { padding: 10px 4px; display: flex; flex-direction: column; gap: 8px; flex: 1; }
    .title-row { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
    .meta h3 { margin: 0; font-size: 16px; color: var(--accent-dark); }
    .meta p.desc { margin: 0; color: #7a6352; font-size: 13px; height: 44px; overflow: hidden; }
    .price-row { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: auto; }
    .price { font-weight: 700; color: var(--accent-dark); font-size: 15px; }
    .btn-add { background: linear-gradient(180deg, var(--accent), var(--accent-dark)); color: white; padding: 8px 12px; border-radius: 10px; border: none; cursor: pointer; font-weight: 600; box-shadow: 0 8px 18px rgba(139, 94, 52, 0.14); }
    .btn-add:active { transform: translateY(1px); }

    .empty { text-align: center; padding: 28px; color: #7a6352; }
    #toast { position: fixed; right: 16px; bottom: 16px; background: var(--accent); color: white; padding: 10px 14px; border-radius: 10px; display: none; box-shadow: 0 12px 30px rgba(139, 94, 52, 0.18); z-index: 9999; }
  </style>
</head>
<body>

<header class="topbar" role="banner">
  <div style="display: flex; align-items: center; gap: 12px;">
    <button id="menuBtn" class="icon-btn" aria-label="Open menu" title="Menu" style="padding: 8px 9px; font-size:18px;">☰</button>
    <div class="brand" style="min-width: 0;">
      <div class="logo-wrap"><img src="images/logo.jpg" alt="ChocoLove Logo"></div>
      <div class="brand-text">
        <h1>ChocoLove</h1>
        <p>Premium Chocolate Cakes</p>
      </div>
    </div>
  </div>

  <div class="controls" role="search">
    <div class="searchbox">
      <input id="searchInput" placeholder="Cari kue..." aria-label="Search products">
    </div>
  </div>

  <div class="nav-right" role="navigation" aria-label="Header actions" style="display: flex; align-items: center; gap: 20px;">
    <div id="cartBtn" class="icon-btn" title="Keranjang" aria-label="Keranjang" style="position: relative;">
      🛒 <span id="cartCount" class="icon-badge">0</span>
    </div>

    <nav class="top-menu" style="display: flex; gap: 16px;">
        <a href="index.php" class="nav-link" style="text-decoration:none; color:#333;">Home</a>
      <a href="products.php" class="nav-link" style="text-decoration:none; color:#333;">Produk</a>
      <a href="about.php" class="nav-link" style="text-decoration:none; color:#333;">About Me</a>
      <a href="contact.php" class="nav-link" style="text-decoration:none; color:#333;">Contact</a>
    </nav>

    <?php if($loggedIn): ?>
      <button id="profileBtn" class="icon-btn" aria-label="Profil" title="Profil">👤</button>
    <?php else: ?>
      <a href="login.php" class="icon-btn" title="Login" aria-label="Login">👤</a>
    <?php endif; ?>
  </div>
</header>
<main class="wrap">
  <section id="productsSection" aria-label="Daftar produk">
    <div id="grid" class="grid">
      <?php if(count($products) === 0): ?>
        <div class="empty">Belum ada produk. Silakan tambahkan produk ke database.</div>
      <?php else: ?>
        <?php foreach($products as $p):
          $namaFile = htmlspecialchars($p['nama_produk']);
          $path = "gambar/{$namaFile}.jpeg";

          // Tentukan gambar
          $img = file_exists($path)
            ? $path
            : ((isset($p['gambar']) && file_exists('uploads/'.$p['gambar']))
                ? 'uploads/'.htmlspecialchars($p['gambar'])
                : 'https://via.placeholder.com/400x300');

          $kategori = isset($p['kategori']) ? $p['kategori'] : '';
        ?>
        <article class="card"
          data-name="<?= htmlspecialchars(strtolower($p['nama_produk'])) ?>"
          data-kategori="<?= htmlspecialchars($kategori) ?>"
          data-price="<?= intval($p['harga']) ?>"
        >
          <div class="imgwrap">
            <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
          </div>

          <div class="meta">
            <div class="title-row">
              <h3><?= htmlspecialchars($p['nama_produk']) ?></h3>

              <?php if($kategori): ?>
                <div style="font-size:12px;color:#7a6352;">
                  <?= htmlspecialchars($kategori) ?>
                </div>
              <?php endif; ?>
            </div>

            <p class="desc">
              <?= htmlspecialchars($p['deskripsi']) ?>
            </p>

            <div class="price-row">
              <div>
                <div class="price">
                  Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                </div>
              </div>

              <div>
                <?php if($loggedIn): ?>
                  <button
                    class="btn-add"
                    data-id="<?= intval($p['id']) ?>"
                    data-name="<?= htmlspecialchars($p['nama_produk']) ?>"
                    data-price="<?= intval($p['harga']) ?>"
                    data-img="<?= htmlspecialchars($p['gambar']) ?>"
                  >Tambah</button>
                <?php else: ?>
                  <div class="tooltip">
                    <button disabled class="btn-add">Tambah</button>
                    <span class="tooltiptext">Login dulu</span>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</main>


<?php include 'footer.php'; ?>

</body>
</html>