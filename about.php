<?php 
$loggedIn = $loggedIn ?? false;
?>

<!-- ✅ HEADER -->
<header class="header">
  <div class="header-inner">

    <!-- Left: Hamburger -->
    <button id="openOff" class="icon-btn menu-btn" aria-label="Menu">
      ☰
    </button>

    <!-- Logo + Brand -->
    <a href="index.php" class="brand-box">
      <img src="assets/logo.png" alt="Logo" class="brand-logo">
      <div class="brand-name">
        <h2>ChocoLove</h2>
        <small>Premium Chocolate Cakes</small>
      </div>
    </a>

    <!-- Search -->
    <div class="search-box">
      <input type="text" placeholder="Cari kue..." class="search-input">
    </div>

    <!-- Cart -->
    <div id="cartBtn" class="icon-btn cart-btn">
      <i class="bi bi-cart"></i>
      <span id="cartCount" class="icon-badge">0</span>
    </div>

    <!-- NAV -->
    <nav class="top-menu">
      <a href="index.php">Home</a>
      <a href="products.php">Produk</a>
      <a href="about.php">About Me</a>
      <a href="contact.php">Contact</a>
    </nav>

    <!-- PROFILE -->
    <?php if($loggedIn): ?>
      <button id="profileBtn" class="icon-btn profile-btn">
        <i class="bi bi-person-fill"></i>
      </button>
    <?php else: ?>
      <a href="login.php" class="icon-btn profile-btn">
        <i class="bi bi-person-fill"></i>
      </a>
    <?php endif; ?>

  </div>
</header>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* ==== HEADER PREMIUM ==== */
.header {
  width: 100%;
  background: linear-gradient(
    135deg,
    #fcf6f0 0%,
    #f5e7d9 35%,
    #eed4b8 100%
  );
  box-shadow: 0 1px 6px rgba(0,0,0,0.05);
  position: sticky;
  top: 0;
  z-index: 999;
  padding: 6px 0;
}

.header-inner {
  max-width: 1300px;
  margin: auto;
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 0 16px;
}

/* MENU BUTTON */
.menu-btn {
  font-size: 18px;
  background: rgba(255,255,255,0.7);
  padding: 8px;
  border-radius: 10px;
  border: 1px solid rgba(0,0,0,0.04);
  cursor: pointer;
}

/* BRAND SECTION */
.brand-box {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
}

.brand-logo {
  width: 45px;
  height: 45px;
  border-radius: 10px;
  object-fit: cover;
}

.brand-name h2 {
  font-size: 1.4rem;
  margin: 0;
  font-weight: 600;
  color: #7a4e2a;
}

.brand-name small {
  font-size: 0.8rem;
  color: #8a6a50;
  margin-top: -4px;
  display: block;
}

/* SEARCH BAR */
.search-box {
  flex: 1;
  display: flex;
  justify-content: center;
}

.search-input {
  width: 90%;
  max-width: 550px;
  border-radius: 16px;
  border: 1px solid #e5d2b6;
  padding: 10px 14px;
  font-size: .95rem;
  outline: none;
}

/* CART */
.cart-btn {
  position: relative;
  font-size: 18px;
  cursor: pointer;
}

.icon-badge {
  position: absolute;
  top: -4px;
  right: -6px;
  background: #a56c3b;
  color: #fff;
  font-size: 12px;
  padding: 1px 6px;
  border-radius: 8px;
}

/* MENU NAVIGATION */
.top-menu {
  display: flex;
  gap: 20px;
  align-items: center;
}

.top-menu a {
  text-decoration: none;
  color: #42392f;
  font-size: 1rem;
  font-weight: 500;
  transition: 0.25s ease;
}
.top-menu a:hover {
  color: #a56c3b;
}

/* PROFILE ICON */
.profile-btn {
  font-size: 18px;
  background: rgba(255,255,255,.7);
  padding: 8px;
  border-radius: 12px;
  border: 1px solid rgba(0,0,0,0.05);
  cursor: pointer;
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .top-menu {
    display: none;
  }
  .search-input {
    max-width: 300px;
  }
}
</style>
