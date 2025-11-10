<?php
require_once __DIR__ . '/config/koneksi.php';
session_start();

$loggedIn = isset($_SESSION['user']);
$username = $loggedIn ? $_SESSION['user'] : '';

$q = "SELECT * FROM produk";
$res = mysqli_query($koneksi, $q);
$products = [];
while ($r = mysqli_fetch_assoc($res)) $products[] = $r;

$hasKategori = false;
$cols = mysqli_query($koneksi, "SHOW COLUMNS FROM produk LIKE 'kategori'");
if ($cols && mysqli_num_rows($cols) > 0) {
    $hasKategori = true;
    $catRes = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM produk WHERE kategori IS NOT NULL AND kategori <> ''");
    $categories = [];
    while ($c = mysqli_fetch_assoc($catRes)) $categories[] = $c['kategori'];
} else { $categories = []; }

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
  --maxwidth: 1200px;
}
*{box-sizing:border-box;}
html,body{height:100%;}
body{margin:0;font-family:'Poppins',sans-serif; background: linear-gradient(180deg,var(--bg), #fff 60%); color:var(--text); -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;}

/* HEADER */
header.topbar{
  position:sticky; top:0; z-index:100; background: linear-gradient(90deg,var(--card),var(--soft));
  border-bottom:1px solid rgba(0,0,0,0.04); backdrop-filter: blur(4px); padding:12px 20px;
  display:flex; align-items:center; justify-content:space-between; gap:12px;
  box-shadow: 0 6px 18px var(--shadow);
}
.brand{ display:flex; align-items:center; gap:12px; min-width:0; }
.logo-wrap{ width:45px; height:45px; border-radius:10px; overflow:hidden; box-shadow: 0 4px 10px rgba(139,94,52,0.15); flex:0 0 45px; }
.logo-wrap img{ width:100%; height:100%; object-fit:cover; display:block; }
.brand-text h1{font-size:17px;margin:0;color:var(--accent-dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;}
.brand-text p{margin:0;font-size:11px;color:#6b5346; line-height:12px;}

/* Controls */
.controls{ display:flex; gap:12px; align-items:center; flex:1; margin-left:16px; min-width:0; }
.searchbox{ flex:1; display:flex; align-items:center; gap:8px; background:var(--card); border-radius:12px; padding:10px 12px; border:1px solid rgba(0,0,0,0.03); box-shadow:0 6px 18px var(--shadow); }
.searchbox input{ border:0; outline:0; font-size:14px; background:transparent; width:100%; }
.select, .select-sm{ background:var(--card); border-radius:12px; padding:8px 12px; font-size:14px; border:1px solid rgba(0,0,0,0.03); box-shadow:0 6px 18px var(--shadow); }

.nav-right{ display:flex; align-items:center; gap:10px; margin-left:auto; }
.icon-btn{ display:inline-flex; align-items:center; justify-content:center; background:var(--soft); color:var(--accent-dark); border-radius:10px; padding:8px; cursor:pointer; border:none; font-weight:700; box-shadow:0 6px 14px rgba(0,0,0,0.04); }
.icon-btn:active{ transform:translateY(1px); }
.icon-badge{ font-size:12px; margin-left:6px; }

/* Layout wrapper */
.wrap{ max-width:var(--maxwidth); margin:20px auto; padding:0 16px; }

/* --- IKLAN SLIDER (baru) --- */
.slider {
  width: 100%;
  border-radius: 14px;
  overflow: hidden;
  position: relative;
  box-shadow: 0 12px 30px rgba(0,0,0,0.06);
  background: linear-gradient(90deg, rgba(236,217,198,0.12), rgba(255,243,228,0.06));
  margin-bottom: 18px;
}
.slider-track { display:flex; transition: transform 0.6s cubic-bezier(.22,.9,.31,1); }
.slide { 
  min-width:100%; 
  position:relative; 
  height:480px; /* sebelumnya 320px → sekarang lebih tinggi */
  display:flex; 
  align-items:center; 
  justify-content:center; 
}

.slide img { 
  width:100%; 
  height:100%; 
  object-fit:cover; 
  display:block; 
  filter:brightness(0.7);
  transition: transform 1s ease;
}

/* tambahkan efek halus supaya tidak terlalu "zoom" */
.slide img:hover {
  transform: scale(0.50);
}

/* Slider overlay text (optional) */
.slide .overlay {
  position:absolute; left:24px; bottom:24px; z-index:5; color:#fff;
  background: rgba(0,0,0,0.35); padding:16px 20px; border-radius:12px;
  max-width:70%;
}
.slide .overlay h3{ margin:0 0 6px 0; font-size:20px; }
.slide .overlay p{ margin:0; font-size:14px; opacity:0.95; }

/* Prev/next buttons */
.slider .nav {
  position:absolute; top:50%; transform:translateY(-50%); z-index:6;
  display:flex; gap:8px; width:100%; justify-content:space-between; padding:0 12px;
}
.slider .nav button {
  background: rgba(0,0,0,0.45); color:#fff; border:0; width:44px; height:44px; border-radius:10px; cursor:pointer;
  display:inline-flex; align-items:center; justify-content:center; font-size:18px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.25);
}
.slider .dots { position:absolute; left:50%; transform:translateX(-50%); bottom:10px; display:flex; gap:8px; z-index:6; }
.slider .dots button { width:10px; height:10px; border-radius:50%; border:0; background:rgba(255,255,255,0.5); cursor:pointer; }
.slider .dots button.active { background:#FF4D6D; box-shadow: 0 4px 10px rgba(255,77,109,0.25); }

/* Hero */
.hero{ background: linear-gradient(90deg, rgba(236,217,198,0.35), rgba(255,243,228,0.35)); border-radius:14px; padding:20px; display:flex; gap:18px; align-items:center; margin-bottom:18px; box-shadow:0 12px 30px rgba(0,0,0,0.04); }
.hero .left{ flex:1 }
.hero h2{ margin:0; font-size:20px; color:var(--accent-dark) }
.hero p{ margin:8px 0 0; color:#6b5346 }

/* Product grid */
.grid{ display:grid; grid-template-columns: repeat(3, 1fr); gap:18px; margin-top:12px; }
.card{ background:linear-gradient(180deg,var(--card),#fff); border-radius:14px; padding:12px; overflow:hidden; border:1px solid rgba(0,0,0,0.03); transition: transform .18s ease, box-shadow .18s; box-shadow: 0 6px 18px rgba(0,0,0,0.04); display:flex; flex-direction:column; }
.card:hover{ transform: translateY(-6px); box-shadow: 0 18px 40px rgba(139,94,52,0.12); }
.imgwrap{ width:100%; height:200px; border-radius:10px; overflow:hidden; background:var(--muted); display:flex; align-items:center; justify-content:center; }
.imgwrap img{ width:100%; height:100%; object-fit:cover; display:block; }
.meta{ padding:10px 4px; display:flex; flex-direction:column; gap:8px; flex:1; }
.title-row{ display:flex; justify-content:space-between; align-items:center; gap:8px; }
.meta h3{ margin:0; font-size:16px; color:var(--accent-dark); }
.meta p.desc{ margin:0; color:#7a6352; font-size:13px; height:44px; overflow:hidden; }
.price-row{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:auto; }
.price{ font-weight:700; color:var(--accent-dark); font-size:15px; }
.btn-add{ background: linear-gradient(180deg,var(--accent),var(--accent-dark)); color:white; padding:8px 12px; border-radius:10px; border:none; cursor:pointer; font-weight:600; box-shadow: 0 8px 18px rgba(139,94,52,0.14); }
.btn-add:active{ transform: translateY(1px); }

.empty{ text-align:center; padding:28px; color:#7a6352; }

#toast{position:fixed; right:16px; bottom:16px; background:var(--accent); color:white; padding:10px 14px; border-radius:10px; display:none; box-shadow:0 12px 30px rgba(139,94,52,0.18); z-index:9999;}

/* Offcanvas & profile sheet (unchanged) */
.offcanvas { position: fixed; top: 0; right: -100%; width: 320px; max-width: 90%; height: 100%; background: var(--card); box-shadow: -8px 0 30px rgba(0,0,0,0.12); transition: right .28s ease; z-index: 200; padding: 18px; overflow-y: auto; }
.offcanvas.open { right: 0; }
.profile-sheet { position: fixed; left: 0; right: 0; bottom: -100%; background: var(--soft); box-shadow: 0 -8px 30px rgba(0,0,0,0.12); transition: bottom .28s ease; z-index: 300; padding: 16px; border-top-left-radius: 12px; border-top-right-radius: 12px; }
.profile-sheet.open { bottom: 0; }

/* Responsive */
@media (max-width: 900px){
  .grid{ grid-template-columns: 1fr; gap:14px; }
  .imgwrap{ height:220px; }
  .hero{ padding:16px; }
}
@media (min-width:901px) and (max-width:1199px){
  .grid{ grid-template-columns: repeat(2,1fr); }
}
@media (min-width:1200px){
  .grid{ grid-template-columns: repeat(3,1fr); }
}
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
    <!-- Hapus kategori dan urutkan rekomendasi -->
    <select id="filterCategory" class="select" aria-label="Filter kategori" style="display:none;">
      <!-- Tidak ada lagi opsi kategori -->
    </select>
    <select id="sortBy" class="select" aria-label="Urutkan" style="display:none;">
      <!-- Tidak ada lagi urutan rekomendasi -->
    </select>
  </div>

  <!-- Bagian kanan header -->
  <div class="nav-right" role="navigation" aria-label="Header actions" style="display: flex; align-items: center; gap: 20px;">

    <!-- Ikon keranjang -->
    <div id="cartBtn" class="icon-btn" title="Keranjang" aria-label="Keranjang" style="position: relative;">
      🛒 <span id="cartCount" class="icon-badge">0</span>
    </div>

    <!-- Menu tambahan di samping keranjang -->
    <nav class="top-menu" style="display: flex; gap: 16px;">
      <a href="index.php" class="nav-link" style="text-decoration:none; color:#333;">Home</a>
      <a href="produk.php" class="nav-link" style="text-decoration:none; color:#333;">Produk</a>
      <a href="about.php" class="nav-link" style="text-decoration:none; color:#333;">About Me</a>
      <a href="contact.php" class="nav-link" style="text-decoration:none; color:#333;">Contact</a>
    </nav>

    <!-- Ikon profil/login -->
    <?php if($loggedIn): ?>
      <button id="profileBtn" class="icon-btn" aria-label="Profil" title="Profil">👤</button>
    <?php else: ?>
      <a href="login.php" class="icon-btn" title="Login" aria-label="Login">👤</a>
    <?php endif; ?>
  </div>
</header>


<!-- ✅ OFFCANVAS NAVIGATION -->
<aside id="offcanvas" class="offcanvas" aria-hidden="true" aria-label="Navigation panel">
  <div class="close-btn">
    <button id="closeOff" class="icon-btn" aria-label="Close">&times;</button>
  </div>

  <nav class="offcanvas-nav">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="produk.php">Produk</a></li>
      <li><a href="about.php">About Me</a></li>
      <li><a href="contact.php">Contact</a></li>

      <?php if($loggedIn): ?>
        <li><a href="logout.php" class="logout-btn">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php" class="login-btn">Login</a></li>
      <?php endif; ?>

    </ul>
  </nav>
</aside>


<style>
/* =============================
       ✅ OFFCANVAS PANEL
   ============================= */
.offcanvas {
  position: fixed;
  top: 0;
  right: -280px;
  width: 280px;
  height: 100vh;
  background: var(--bg2, #fdf8f4);
  box-shadow: -4px 0 15px rgba(0,0,0,0.15);
  padding: 20px;
  transition: right .35s ease;
  z-index: 2000;
}

.offcanvas.show {
  right: 0;
}

.close-btn {
  display: flex;
  justify-content: flex-end;
}

.icon-btn {
  background: transparent;
  border: none;
  font-size: 26px;
  cursor: pointer;
  transition: 0.3s;
  color: var(--text, #3c2f2f);
}

.icon-btn:hover {
  transform: scale(1.1);
}

/* =============================
         ✅ NAV LIST
   ============================= */
.offcanvas-nav ul {
  list-style: none;
  padding: 0;
  margin: 25px 0 0;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.offcanvas-nav a {
  text-decoration: none;
  font-size: 18px;
  font-weight: 600;
  letter-spacing: .3px;
  color: var(--text, #4a342e);
  transition: .25s ease;
}

.offcanvas-nav a:hover {
  color: var(--accent, #b88a5e);
  transform: translateX(4px);
}

/* ✅ LOGIN / LOGOUT COLOR */
.logout-btn {
  color: #c5473a;
}
.logout-btn:hover {
  color: #a1342b;
}

.login-btn {
  color: var(--accent, #b88a5e);
}

/* =============================
      ✅ COLOR VARIABLES
   ============================= */
:root {
  --bg: #fff5eb;
  --bg2: #fcf9f6;
  --text: #4a342e;
  --accent: #c9a063;   /* gold-ish premium */
}
</style>



<!-- Profile bottom sheet -->
<div id="profileSheet" class="profile-sheet" aria-hidden="true">
  <span class="handle" aria-hidden="true"></span>
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <strong style="color:var(--accent-dark)">Akun</strong>
    <button id="closeProfile" class="icon-btn" aria-label="Tutup" style="padding:6px 8px;">&times;</button>
  </div>
  <div class="items">
    <form id="logoutForm" method="post" action="logout.php" style="margin:0;">
      <button type="submit">Logout</button>
    </form>
  </div>
</div>

<div class="wrap" role="main">

  <!-<!-- ===== IKLAN SLIDER (muncul di dashboard) ===== -->
<div class="slider" aria-label="Produk ChocoLove" id="ProdukSlider">
  <div class="slider-track" id="sliderTrack">

    <div class="slide">
      <img src="img/gambar1.png" alt="Produk 1">
      <div class="overlay"></div>
    </div>

    <div class="slide">
      <img src="img/gambar2.png" alt="Produk 2">
      <div class="overlay"></div>
    </div>

    <div class="slide">
      <img src="img/gambar3.png" alt="Produk 3">
      <div class="overlay"></div>
    </div>

  </div>

  <div class="nav" aria-hidden="false">
    <button id="prevBtn" aria-label="Sebelumnya">‹</button>
    <button id="nextBtn" aria-label="Berikutnya">›</button>
  </div>

  <div class="dots" id="sliderDots" role="tablist" aria-label="Slider indikator"></div>
</div>

  <!-- ===== END IKLAN SLIDER ===== -->

  <div class="hero" aria-hidden="false">
    <div class="left">
      <p>Sambut dunia cokelat istimewa bersama ChocoLove.
Setiap gigitan adalah perpaduan sempurna antara rasa, kualitas, dan kehangatan.</p>
    </div>
  </div>

 <section id="productsSection" aria-label="Daftar produk">
  <div id="grid" class="grid">
    <?php if(count($products)===0): ?>
      <div class="empty">Belum ada produk. Silakan tambahkan produk ke database.</div>
    <?php else: ?>
      <?php foreach($products as $p):
        $namaFile = htmlspecialchars($p['nama_produk']);
        $path = "gambar/{$namaFile}.jpeg";

        if(file_exists($path)){
            $img = $path;
        } else {
            // fallback ke kolom gambar jika tersedia
            $img = (!empty($p['gambar']) && file_exists('uploads/'.$p['gambar']))
                  ? 'uploads/'.htmlspecialchars($p['gambar'])
                  : 'https://via.placeholder.com/400x300';
        }

        $kategori = $hasKategori && !empty($p['kategori']) ? $p['kategori'] : '';
      ?>
      <article class="card"
        data-name="<?= htmlspecialchars(strtolower($p['nama_produk']))?>"
        data-kategori="<?= htmlspecialchars($kategori)?>"
        data-price="<?= intval($p['harga'])?>"
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

          <p class="desc"><?= htmlspecialchars($p['deskripsi']) ?></p>

          <div class="price-row">
            <div>
              <div class="price">
                Rp <?= number_format($p['harga'],0,',','.') ?>
              </div>
            </div>

            <div>
              <?php if($loggedIn): ?>
                <button
                  class="btn-add"
                  data-id="<?= intval($p['id'])?>"
                  data-name="<?= htmlspecialchars($p['nama_produk'])?>"
                  data-price="<?= intval($p['harga'])?>"
                  data-img="<?= htmlspecialchars($p['gambar'])?>"
                >
                  Tambah
                </button>
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


<!-- Include Footer -->
<?php include('footer.php'); ?>

<script>
// Slider and JavaScript functionality
</script>

<div id="toast">Ditambahkan ke keranjang ✓</div>

<script>
/* --- Slider logic --- */
(function(){
  const track = document.getElementById('sliderTrack');
  const slides = Array.from(track.children);
  const dotsWrap = document.getElementById('sliderDots');
  const prev = document.getElementById('prevBtn');
  const next = document.getElementById('nextBtn');
  let index = 0;
  let timer = null;
  const INTERVAL = 2000; // 5s

  // build dots
  slides.forEach((s,i)=>{
    const btn = document.createElement('button');
    btn.setAttribute('aria-label','Slide '+(i+1));
    btn.dataset.index = i;
    if(i===0) btn.classList.add('active');
    btn.addEventListener('click', ()=>{ goTo(parseInt(btn.dataset.index)); resetTimer(); });
    dotsWrap.appendChild(btn);
  });

  function update(){
    const w = track.parentElement.clientWidth;
    track.style.transform = `translateX(-${index * w}px)`;
    Array.from(dotsWrap.children).forEach((d,i)=> d.classList.toggle('active', i===index));
  }

  function goTo(i){
    index = (i + slides.length) % slides.length;
    update();
  }

  next.addEventListener('click', ()=>{ goTo(index+1); resetTimer(); });
  prev.addEventListener('click', ()=>{ goTo(index-1); resetTimer(); });

  function startTimer(){ timer = setInterval(()=>{ goTo(index+1); }, INTERVAL); }
  function resetTimer(){ clearInterval(timer); startTimer(); }

  // responsive: recalc on resize
  window.addEventListener('resize', update);

  // init
  update();
  startTimer();
})();

/* --- UI controls: offcanvas, profile sheet, menu --- */
const offcanvas = document.getElementById('offcanvas');
const menuBtn = document.getElementById('menuBtn');
const closeOff = document.getElementById('closeOff');

menuBtn.addEventListener('click', ()=>{ 
  offcanvas.classList.toggle('open'); 
  offcanvas.setAttribute('aria-hidden', offcanvas.classList.contains('open') ? 'false' : 'true'); 
});
if(closeOff) closeOff.addEventListener('click', ()=>{ offcanvas.classList.remove('open'); offcanvas.setAttribute('aria-hidden','true'); });
document.addEventListener('keydown', (e)=>{ if(e.key==='Escape'){ offcanvas.classList.remove('open'); offcanvas.setAttribute('aria-hidden','true'); profileSheetClose(); } });

/* Profile sheet */
const profileBtn = document.getElementById('profileBtn');
const profileSheet = document.getElementById('profileSheet');
const closeProfile = document.getElementById('closeProfile');

function profileSheetOpen(){
  profileSheet.classList.add('open');
  profileSheet.setAttribute('aria-hidden','false');
}
function profileSheetClose(){
  profileSheet.classList.remove('open');
  profileSheet.setAttribute('aria-hidden','true');
}

if(profileBtn){
  profileBtn.addEventListener('click', ()=>{
    if(profileSheet.classList.contains('open')) profileSheetClose();
    else profileSheetOpen();
  });
}
if(closeProfile) closeProfile.addEventListener('click', profileSheetClose);

/* Close sheet on outside touch (for mobile) */
document.addEventListener('click', (e)=>{
  if(profileSheet.classList.contains('open')){
    const rect = profileSheet.getBoundingClientRect();
    if(e.clientY < rect.top && !e.target.matches('#profileBtn')) profileSheetClose();
  }
});

/* --- Cart + localStorage logic (kept) --- */
const CART_KEY = 'chocolove_cart_v1';
const cartCountEl = document.getElementById('cartCount');
const toast = document.getElementById('toast');

function getCart(){ try{ return JSON.parse(localStorage.getItem(CART_KEY))||[] }catch(e){ return [] } }
function saveCart(cart){ localStorage.setItem(CART_KEY,JSON.stringify(cart)); updateCartCount(); }
function updateCartCount(){ const cart=getCart(); const qty=cart.reduce((s,i)=>s+(i.qty||0),0); cartCountEl.innerText=qty; }

function showToast(msg){ toast.innerText=msg; toast.style.display='block'; toast.style.opacity='1'; setTimeout(()=>{ toast.style.opacity='0'; setTimeout(()=>toast.style.display='none',300); },1400); }

document.addEventListener('click', (e)=>{
  const t = e.target;
  if(t.matches('.btn-add')){
    const id=t.dataset.id, name=t.dataset.name, price=parseInt(t.dataset.price||0), img=t.dataset.img||'';
    let cart=getCart();
    const found=cart.find(x=>x.id==id);
    if(found){ found.qty+=1; found.subtotal=found.qty*found.price; }
    else cart.push({id:id,name:name,price:price,qty:1,img:img,subtotal:price});
    saveCart(cart); showToast('Ditambahkan ke keranjang ✓');
  }
});

document.getElementById('cartBtn').addEventListener('click', ()=>{
  // ambil isi keranjang dari localStorage
  const cart = getCart();

  // jika kosong, beri peringatan
  if (cart.length === 0) {
    alert('Keranjang masih kosong 🍫');
    return;
  }

  // pindah ke halaman keranjang
  window.location.href = 'keranjang.php';
});

/* --- Filters + Search + Sort logic --- */
const products = Array.from(document.querySelectorAll('#grid .card'));
const searchInput = document.getElementById('searchInput');
const filterEl = document.getElementById('filterCategory');
const sortEl = document.getElementById('sortBy');

const offCategory = document.getElementById('offCategory');
const offSort = document.getElementById('offSort');
const applyFilter = document.getElementById('applyFilter');
const resetFilter = document.getElementById('resetFilter');

function applyFilters(){
  const q=searchInput.value.trim().toLowerCase(), cat = (filterEl && filterEl.value) ? filterEl.value : (offCategory ? offCategory.value : '');
  let visible = products.filter(c=>{
    if(cat && c.dataset.kategori.toLowerCase() !== cat.toLowerCase()) return false;
    if(q && !c.dataset.name.includes(q)) return false;
    return true;
  });

  const sort = (sortEl && sortEl.value) ? sortEl.value : (offSort ? offSort.value : 'default');
  if(sort==='price_asc') visible.sort((a,b)=>parseInt(a.dataset.price)-parseInt(b.dataset.price));
  else if(sort==='price_desc') visible.sort((a,b)=>parseInt(b.dataset.price)-parseInt(b.dataset.price));

  const grid = document.getElementById('grid');
  grid.innerHTML='';
  if(visible.length===0) grid.innerHTML = '<div class="empty">Tidak ada produk yang cocok.</div>';
  else visible.forEach(n=> grid.appendChild(n));
}

searchInput.addEventListener('input', applyFilters);
if(filterEl) filterEl.addEventListener('change', ()=>{ if(offCategory) offCategory.value = filterEl.value; applyFilters(); });
if(sortEl) sortEl.addEventListener('change', ()=>{ if(offSort) offSort.value = sortEl.value; applyFilters(); });

applyFilter.addEventListener('click', ()=>{
  if(filterEl) filterEl.value = offCategory.value;
  if(sortEl) sortEl.value = offSort.value;
  offcanvas.classList.remove('open'); offcanvas.setAttribute('aria-hidden','true');
  applyFilters();
});
resetFilter.addEventListener('click', ()=>{
  if(filterEl) filterEl.value = '';
  if(sortEl) sortEl.value = 'default';
  if(offCategory) offCategory.value = '';
  if(offSort) offSort.value = 'default';
  applyFilters();
});

updateCartCount();
applyFilters();

</script>
</body>
</html>
