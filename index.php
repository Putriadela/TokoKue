<?php
require_once __DIR__ . '/config/koneksi.php';
session_start();

// Status login
$loggedIn = isset($_SESSION['user']);
$username = $loggedIn ? $_SESSION['user'] : '';

// Ambil produk
$q = "SELECT * FROM produk";
$res = mysqli_query($koneksi, $q);
$products = [];
while ($r = mysqli_fetch_assoc($res)) $products[] = $r;

// Periksa kolom kategori 
$hasKategori = false;
$cols = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'kategori'");
if ($cols && mysqli_num_rows($cols) > 0) {
    $hasKategori = true;
    $catRes = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM produk WHERE kategori IS NOT NULL AND kategori <> ''");
    $categories = [];
    while ($c = mysqli_fetch_assoc($catRes)) $categories[] = $c['kategori'];
} else { $categories = []; }

// Periksa kolom rating
$hasRating = false;
$cols2 = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'rating'");
if ($cols2 && mysqli_num_rows($cols2) > 0) $hasRating = true;
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ChocoLove — Toko Kue Online</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
:root{
  --bg: #FFF3E4; --card: #FFFFFF; --muted: #ECD9C6; --accent: #C49A6C;
  --accent-dark: #8B5E34; --text: #3b2f2a; --soft: #F7EFE6; --shadow: rgba(139,94,52,0.12);
}
*{box-sizing:border-box;}
body{margin:0;font-family:'Poppins',sans-serif; background: linear-gradient(180deg,var(--bg), #fff 60%); color:var(--text);}
header.topbar{
  position:sticky; top:0; z-index:60; background: linear-gradient(90deg,var(--card),var(--soft));
  border-bottom:1px solid rgba(0,0,0,0.04); backdrop-filter: blur(4px); padding:14px 24px;
  display:flex; align-items:center; justify-content:space-between; gap:20px;
  box-shadow: 0 6px 18px var(--shadow);
}
.brand{ display:flex; align-items:center; gap:12px; }
.brand .logo{
  width:48px; height:48px; border-radius:12px;
  background: linear-gradient(135deg,var(--accent),var(--accent-dark));
  display:flex; align-items:center; justify-content:center;
  color:white; font-weight:700; font-size:20px; box-shadow:0 6px 12px rgba(139,94,52,0.18);
}
.brand h1{font-size:18px;margin:0;color:var(--accent-dark);}
.brand p{margin:0;font-size:12px;color:#6b5346;}

.nav-right{ display:flex; align-items:center; gap:12px; }
.nav-right a, .nav-right div{ font-weight:600; color:var(--accent-dark); text-decoration:none; padding:6px 10px; border-radius:8px; background: var(--soft); transition: background .2s; display:flex; align-items:center; justify-content:center;}
.nav-right a:hover{ background: var(--accent); color:white; cursor:pointer; }

.controls{ display:flex; gap:12px; align-items:center; flex:1; margin-left:20px; }
.searchbox{ flex:1; display:flex; align-items:center; gap:8px; background:var(--card); border-radius:12px; padding:10px 12px; border:1px solid rgba(0,0,0,0.03); box-shadow:0 6px 18px var(--shadow);}
.searchbox input{ border:0; outline:0; font-size:14px; background:transparent; width:100%; }
.select{ background:var(--card); border-radius:12px; padding:8px 12px; font-size:14px; border:1px solid rgba(0,0,0,0.03); box-shadow:0 6px 18px var(--shadow); }

.wrap{ max-width:1200px; margin:28px auto; padding:0 18px;}
.hero{ background: linear-gradient(90deg, rgba(236,217,198,0.35), rgba(255,243,228,0.35)); border-radius:14px; padding:24px; display:flex; gap:20px; align-items:center; margin-bottom:20px; box-shadow:0 12px 30px rgba(0,0,0,0.04);}
.hero .left{ flex:1 }
.hero h2{ margin:0; font-size:22px; color:var(--accent-dark) }
.hero p{ margin:8px 0 0; color:#6b5346 }

.grid{ display:grid; grid-template-columns: repeat(auto-fill,minmax(240px,1fr)); gap:18px; margin-top:16px; }
.card{ background:linear-gradient(180deg,var(--card),#fff); border-radius:14px; padding:12px; overflow:hidden; border:1px solid rgba(0,0,0,0.03); transition: transform .22s cubic-bezier(.2,.9,.3,1), box-shadow .22s; box-shadow: 0 6px 18px rgba(0,0,0,0.04);}
.card:hover{ transform: translateY(-8px) scale(1.01); box-shadow: 0 18px 40px rgba(139,94,52,0.12); }
.imgwrap{ width:100%; height:160px; border-radius:10px; overflow:hidden; background:var(--muted); display:flex; align-items:center; justify-content:center;}
.imgwrap img{ width:100%; height:100%; object-fit:cover; }
.meta{ padding:10px 4px; display:flex; flex-direction:column; gap:8px; }
.meta .title{ display:flex; justify-content:space-between; align-items:center; gap:10px;}
.meta h3{ margin:0; font-size:16px; color:var(--accent-dark) }
.meta p.desc{ margin:0; color:#7a6352; font-size:13px; height:38px; overflow:hidden; }
.price-row{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:6px;}
.price{ font-weight:700; color:var(--accent-dark); font-size:15px; }
.btn-add{ background: linear-gradient(180deg,var(--accent),var(--accent-dark)); color:white; padding:8px 12px; border-radius:10px; border:none; cursor:pointer; font-weight:600; box-shadow: 0 8px 18px rgba(139,94,52,0.14); transition:transform .12s;}
.btn-add:active{ transform: translateY(1px) }
.rating{ display:flex; align-items:center; gap:6px; font-size:13px; color:#b07c4b; }
.stars{ color:#f2c68a; }
.empty{ text-align:center; padding:28px; color:#7a6352; }
.tooltip{ position: relative; display:inline-block; }
.tooltip .tooltiptext{ visibility:hidden; width:120px; background-color:#333; color:#fff; text-align:center; border-radius:6px; padding:5px; position:absolute; z-index:1; bottom:125%; left:50%; margin-left:-60px; opacity:0; transition:opacity 0.3s; font-size:12px; }
.tooltip:hover .tooltiptext{ visibility:visible; opacity:1; }
#toast{position:fixed; right:18px; bottom:18px; background:var(--accent); color:white; padding:10px 14px; border-radius:10px; display:none; box-shadow:0 12px 30px rgba(139,94,52,0.18); z-index:9999;}
@media (max-width:720px){ .hero{ flex-direction:column; align-items:flex-start; gap:12px; } header.topbar{ padding:12px; } }
</style>
</head>
<body>

<header class="topbar">
  <div class="brand">
    <div class="logo">CL</div>
    <div>
      <h1>ChocoLove</h1>
      <p>Premium Homemade Chocolate Cakes</p>
    </div>
  </div>

  <div class="controls">
    <div class="searchbox" role="search">
      <input id="searchInput" placeholder="Cari kue..." aria-label="Search products">
    </div>
    <select id="filterCategory" class="select">
      <option value="">Semua Kategori</option>
      <?php foreach($categories as $cat): ?>
        <option value="<?=htmlspecialchars($cat)?>"><?=htmlspecialchars($cat)?></option>
      <?php endforeach; ?>
    </select>
    <select id="sortBy" class="select">
      <option value="default">Urutkan: Rekomendasi</option>
      <option value="price_asc">Harga: Rendah → Tinggi</option>
      <option value="price_desc">Harga: Tinggi → Rendah</option>
      <option value="rating_desc">Rating: Tertinggi</option>
    </select>
  </div>

  <div class="nav-right">
    <div id="cartBtn" title="Keranjang">
      🛒 <span id="cartCount">0</span>
    </div>
    <?php if($loggedIn): ?>
      <a href="profile.php" title="Profil">
        👤
      </a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </div>
</header>

<div class="wrap">
  <div class="hero">
    <div class="left">
      <h2>Selamat datang di ChocoLove 🍫</h2>
      <p>Temukan kue cokelat rumahan premium, dibuat dengan bahan pilihan. Pilih favoritmu dan pesan online — cepat dan aman.</p>
    </div>
  </div>

  <section id="productsSection">
    <div id="grid" class="grid">
      <?php if(count($products)===0): ?>
        <div class="empty">Belum ada produk. Silakan tambahkan produk ke database.</div>
      <?php else: ?>
        <?php foreach($products as $p):
          // Otomatis ambil gambar dari folder /gambar/ berdasarkan nama produk
          $namaFile = htmlspecialchars($p['nama_produk']);
          $path = "gambar/{$namaFile}.jpeg";
          if(file_exists($path)){
              $img = $path;
          } else {
              $img = 'https://via.placeholder.com/400x300';
          }

          $rating = ($hasRating && isset($p['rating'])) ? floatval($p['rating']) : round(3.5 + (($p['id']%5)*0.4),1);
          $kategori = $hasKategori && !empty($p['kategori']) ? $p['kategori'] : '';
        ?>
        <article class="card" data-name="<?=htmlspecialchars(strtolower($p['nama_produk']))?>" data-kategori="<?=htmlspecialchars($kategori)?>" data-price="<?=intval($p['harga'])?>" data-rating="<?= $rating ?>">
          <div class="imgwrap"><img src="<?= $img ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>"></div>
          <div class="meta">
            <div class="title">
              <h3><?= htmlspecialchars($p['nama_produk']) ?></h3>
              <?php if($kategori): ?>
              <div class="muted"><?= htmlspecialchars($kategori) ?></div>
              <?php endif; ?>
            </div>
            <p class="desc"><?= htmlspecialchars($p['deskripsi']) ?></p>
            <div class="price-row">
              <div>
                <div class="price">Rp <?= number_format($p['harga'],0,',','.') ?></div>
                <div class="rating">
                  <div class="stars"><?php $full=floor($rating); $half=($rating-$full)>=0.5; for($i=0;$i<$full;$i++) echo '★'; if($half) echo '☆'; ?></div>
                  <div style="font-size:12px; margin-left:6px;"><?= $rating ?></div>
                </div>
              </div>
              <div>
              <?php if($loggedIn): ?>
                <button class="btn-add" data-id="<?=intval($p['id'])?>" data-name="<?=htmlspecialchars($p['nama_produk'])?>" data-price="<?=intval($p['harga'])?>" data-img="<?=htmlspecialchars($p['gambar'])?>">Tambah</button>
              <?php else: ?>
                <div class="tooltip"><button disabled class="btn-add">Tambah</button><span class="tooltiptext">Login dulu</span></div>
              <?php endif; ?>
              </div>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</div>

<div id="toast">Ditambahkan ke keranjang ✓</div>

<script>
const products = Array.from(document.querySelectorAll('#grid .card'));
const searchInput = document.getElementById('searchInput');
const filterEl = document.getElementById('filterCategory');
const sortEl = document.getElementById('sortBy');
const cartCountEl = document.getElementById('cartCount');
const cartBtn = document.getElementById('cartBtn');
const toast = document.getElementById('toast');

const CART_KEY = 'chocolove_cart_v1';
function getCart(){ try{ return JSON.parse(localStorage.getItem(CART_KEY))||[] }catch(e){ return [] } }
function saveCart(cart){ localStorage.setItem(CART_KEY,JSON.stringify(cart)); updateCartCount(); }
function updateCartCount(){ const cart=getCart(); const qty=cart.reduce((s,i)=>s+(i.qty||0),0); cartCountEl.innerText=qty; }

function showToast(msg){ toast.innerText=msg; toast.style.display='block'; toast.style.opacity='1'; setTimeout(()=>{ toast.style.opacity='0'; setTimeout(()=>toast.style.display='none',300); },1400); }

document.querySelectorAll('.btn-add').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    const id=btn.dataset.id, name=btn.dataset.name, price=parseInt(btn.dataset.price||0), img=btn.dataset.img||'';
    let cart=getCart();
    const found=cart.find(x=>x.id==id);
    if(found){ found.qty+=1; found.subtotal=found.qty*found.price; }
    else cart.push({id:id,name:name,price:price,qty:1,img:img,subtotal:price});
    saveCart(cart); showToast('Ditambahkan ke keranjang ✓');
  });
});

cartBtn.addEventListener('click', ()=>{
  const cart = getCart();
  if(cart.length===0) alert('Keranjang kosong');
  else {
    let txt='Isi keranjang:\n';
    cart.forEach(i=> txt+=`${i.name} x ${i.qty} — Rp ${i.subtotal}\n`);
    txt+='\nTeruskan ke checkout?';
    if(confirm(txt)) window.location.href='checkout.php';
  }
});

function applyFilters(){
  const q=searchInput.value.toLowerCase(), cat=filterEl.value;
  let visible=products.filter(c=>{
    if(cat && c.dataset.kategori.toLowerCase()!==cat.toLowerCase()) return false;
    if(q && !c.dataset.name.includes(q)) return false;
    return true;
  });
  const sort=sortEl.value;
  if(sort==='price_asc') visible.sort((a,b)=>parseInt(a.dataset.price)-parseInt(b.dataset.price));
  else if(sort==='price_desc') visible.sort((a,b)=>parseInt(b.dataset.price)-parseInt(a.dataset.price));
  else if(sort==='rating_desc') visible.sort((a,b)=>parseFloat(b.dataset.rating)-parseFloat(a.dataset.rating));
  const grid=document.getElementById('grid'); grid.innerHTML='';
  visible.length===0?grid.innerHTML='<div class="empty">Tidak ada produk yang cocok.</div>':visible.forEach(n=>grid.appendChild(n));
}

searchInput.addEventListener('input',applyFilters);
filterEl.addEventListener('change',applyFilters);
sortEl.addEventListener('change',applyFilters);

updateCartCount();
applyFilters();
</script>
</body>
</html>
