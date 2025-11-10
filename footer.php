<footer class="footer text-dark">
  <div class="footer-inner container">
    <div class="footer-content text-center">
      <!-- Quick Links -->
      <h5 class="footer-title">Quick Links</h5>
      <ul class="list-unstyled footer-list">
        <li><a href="index.php" class="footer-link">Home</a></li>
        <li><a href="about.php" class="footer-link">About Us</a></li>
        <li><a href="produk.php" class="footer-link">Products</a></li>
        <li><a href="contact.php" class="footer-link">Contact</a></li>
      </ul>

      <!-- Social -->
      <h5 class="footer-title mt-3">Follow Us</h5>
      <div class="footer-social-wrapper">
        <a href="#" class="footer-social-btn"><i class="bi bi-facebook"></i> Facebook</a>
        <a href="#" class="footer-social-btn"><i class="bi bi-instagram"></i> Instagram</a>
        <a href="#" class="footer-social-btn"><i class="bi bi-twitter"></i> Twitter</a>
      </div>
    </div>

    <!-- Copyright -->
    <div class="text-center mt-3 small">
      <p>&copy; <?= date("Y") ?> ChocoLove. All Rights Reserved.</p>
    </div>
  </div>
</footer>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* === FOOTER STYLE === */
.footer {
  width: 100%;
  background: linear-gradient(135deg, #FFF3E4 0%, #EFD8BF 40%, #DDBB96 100%);
  padding: 32px 0;
  color: #3b2f2a;
  font-family: "Poppins", sans-serif;
  border-top: 1px solid rgba(0,0,0,0.05);
}

.footer-inner {
  max-width: 850px;
  margin: 0 auto;
}

.footer-content {
  text-align: center;
}

/* Title */
.footer-title {
  font-size: 1.2rem;
  font-weight: 600;
  color: #8B5E34;
  margin-bottom: 15px;
}

/* Quick Links */
.footer-list {
  padding: 0;
  margin: 0 0 18px 0;
  list-style: none;
}
.footer-list li {
  margin: 8px 0;
}
.footer-link {
  color: #3b2f2a;
  font-size: 1rem;
  text-decoration: none;
  transition: color 0.25s ease;
}
.footer-link:hover {
  color: #C49A6C;
}

/* Social Buttons */
.footer-social-wrapper {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 10px;
}
.footer-social-btn {
  border: 1px solid #8B5E34;
  padding: 8px 12px;
  border-radius: 25px;
  font-size: 0.9rem;
  color: #3b2f2a;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: all 0.25s ease;
}
.footer-social-btn:hover {
  background-color: #C49A6C;
  border-color: #C49A6C;
  color: #fff;
  transform: scale(1.05);
}

/* Copyright */
.footer p {
  color: #6c5a4c;
  margin-bottom: 0;
  font-size: 0.85rem;
}

/* Mobile */
@media (max-width: 768px) {
  .footer {
    padding: 24px 0;
  }
  .footer-title {
    font-size: 1.1rem;
  }
  .footer-link {
    font-size: 0.9rem;
  }
}
</style>