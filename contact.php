<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ChocoLove — Kontak Kami</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f4f4f9;
      color: #3b2f2a;
      line-height: 1.6;
    }

    /* ✅ HEADER */
    header.topbar {
      position: sticky;
      top: 0;
      z-index: 100;
      background: linear-gradient(90deg, #f5e1c0, #f4d1a5);
      border-bottom: 1px solid rgba(0, 0, 0, 0.04);
      padding: 12px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo-wrap {
      width: 45px;
      height: 45px;
      border-radius: 10px;
      overflow: hidden;
    }

    .logo-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .brand-text h1 {
      font-size: 17px;
      margin: 0;
      color: #5c3d21;
      font-weight: 700;
    }

    .brand-text p {
      font-size: 12px;
      color: #6a4a2f;
      margin-top: -4px;
    }

    nav.top-menu {
      display: flex;
      gap: 18px;
    }

    nav.top-menu a {
      text-decoration: none;
      font-weight: 500;
      color: #3e2d1e;
      transition: 0.2s;
    }

    nav.top-menu a:hover {
      color: #8B5E34;
    }

    .icon-btn {
      cursor: pointer;
      background: rgba(255,255,255,0.7);
      border: none;
      border-radius: 8px;
      padding: 8px 10px;
      font-size: 18px;
      transition: 0.2s;
    }

    .icon-btn:hover {
      background: rgba(255,255,255,0.95);
    }


    /* CONTACT SECTION */
    #contactSection {
      padding: 80px 20px;
      text-align: center;
      background: linear-gradient(to right, #FFF3E4, #F7D8B5);
      border-radius: 20px;
      max-width: 1200px;
      margin: 50px auto;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
    }

    .contact-header h1 {
      font-size: 40px;
      color: #8B5E34;
      margin-bottom: 15px;
      font-weight: 800;
      text-transform: uppercase;
    }

    .contact-header p {
      font-size: 18px;
      color: #6b5346;
      margin-bottom: 40px;
      font-style: italic;
    }

    .contact-content {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      gap: 40px;
      flex-wrap: wrap;
    }

    .contact-form,
    .contact-info {
      flex: 1;
      background-color: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(139, 94, 52, 0.1);
      margin-bottom: 30px;
    }

    .contact-form h2,
    .contact-info h2 {
      font-size: 28px;
      color: #8B5E34;
      margin-bottom: 20px;
      font-weight: 700;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .form-group label {
      display: block;
      font-size: 16px;
      margin-bottom: 8px;
      color: #7a6352;
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      color: #3b2f2a;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-top: 8px;
    }

    .btn-submit {
      background-color: #C49A6C;
      color: white;
      font-weight: 600;
      padding: 12px 30px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
      background-color: #8B5E34;
    }

    .contact-info ul {
      list-style: none;
      padding: 0;
      font-size: 16px;
      color: #7a6352;
    }

    .contact-info ul li {
      margin-bottom: 12px;
    }

    .contact-info a {
      color: #8B5E34;
      font-weight: 600;
      text-decoration: none;
    }

    .contact-info a:hover {
      color: #6B4A2B;
    }


    /* Responsive */
    @media (max-width: 768px) {
      #contactSection {
        padding: 60px 20px;
      }

      .contact-content {
        flex-direction: column;
        gap: 20px;
      }
    }
  </style>
</head>

<body>

<header class="topbar" role="banner">

  <div style="display: flex; align-items: center; gap: 12px;">
    <button id="menuBtn" class="icon-btn" aria-label="Open menu" title="Menu">☰</button>

    <div class="brand">
      <div class="logo-wrap">
        <img src="images/logo.jpg" alt="ChocoLove Logo">
      </div>

      <div class="brand-text">
        <h1>ChocoLove</h1>
        <p>Premium Chocolate Cakes</p>
      </div>
    </div>
  </div>

  <nav class="top-menu">
    <a href="index.php">Home</a>
    <a href="products.php">Produk</a>
    <a href="about.php">About Me</a>
    <a href="contact.php">Contact</a>
  </nav>

</header>

<main class="wrap">
  <section id="contactSection" class="contact-section">
    <div class="contact-header">
      <h1>Kontak Kami</h1>
      <p>Hubungi kami untuk pertanyaan atau informasi lebih lanjut!</p>
    </div>

    <div class="contact-content">
      <div class="contact-form">
        <h2>Formulir Kontak</h2>

        <form action="submit_contact.php" method="POST">

          <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" placeholder="Masukkan nama Anda" required>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
          </div>

          <div class="form-group">
            <label for="message">Pesan</label>
            <textarea id="message" name="message" rows="5" placeholder="Tulis pesan Anda" required></textarea>
          </div>

          <button type="submit" class="btn-submit">Kirim Pesan</button>
        </form>
      </div>

      <div class="contact-info">
        <h2>Informasi Kontak Lain</h2>
        <ul>
          <li><strong>Alamat:</strong> Jalan Coklat No. 123, Jakarta, Indonesia</li>
          <li><strong>Telepon:</strong> +62 21 1234 5678</li>
          <li><strong>Email:</strong> <a href="mailto:info@chocolove.com">info@chocolove.com</a></li>
          <li><strong>Jam Operasional:</strong> Senin - Jumat: 09.00 - 18.00</li>
        </ul>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
