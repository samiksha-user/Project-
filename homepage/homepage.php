<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FindIt | Lost & Found System</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="homepage.css" />
</head>
<body>

  <!-- Navbar -->
    <?php require_once "navbar.php"; ?>
 

  <!-- Hero Section -->
  <section class="hero container mt-5 pt-5">
    <div class="row align-items-center justify-content-center py-5">
      <div class="col-md-6 order-md-1 order-2 text-center text-md-start">
        <h1 class="fw-bold">
          Welcome to <span class="text-primary">FindIt</span><br />
          Lost & Found System
        </h1>
        <p class="lead mt-3 mb-4 text-muted">
          Lost an item? Sign up, log in, and report it. Our admin team will handle the matching process and send you a response directly.
        </p>

        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-md-start">
          <!-- <a class="btn btn-lg btn-primary rounded-pill px-5 shadow-sm" href="../signup/signup.html">
            <i class="bi bi-person-add me-2"></i> Sign Up First
          </a> -->
          <a class="btn btn-lg btn-outline-primary rounded-pill px-5" href="../login/login.php">
            <i class="bi bi-box-arrow-in-right me-2"></i> Report Item
          </a>
        </div>

        <div class="input-group mt-5 hero-search shadow-sm">
          <input type="text" class="form-control form-control-lg rounded-start-pill border-0" placeholder="Search existing found items..." aria-label="Search lost items" />
          <button class="btn btn-primary btn-lg px-4 rounded-end-pill" type="button">
            <i class="bi bi-search me-2"></i> Search
          </button>
        </div>
      </div>
      <div class="col-md-6 order-md-2 order-1 mt-4 mt-md-0 text-center">
        <img src="https://plus.unsplash.com/premium_photo-1661427252330-5581ac8cf8de?q=80&w=1332&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid hero-image" alt="people cheering holding found items" />
      </div>
    </div>
  </section>

  <hr class="my-5" />

  <!-- Features Section -->
  <section class="features container text-center py-5">
    <h2 class="mb-2 fw-bold section-title">Our 4-Step Recovery Process</h2>
    <p class="lead mb-5 text-muted">A secure and efficient journey to reunion.</p>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="feature-card p-4">
          <div class="icon-circle mb-3 mx-auto">
            <i class="bi bi-person-check display-5 text-primary"></i>
          </div>
          <h5>1. Register & Log In</h5>
          <p>Start by creating a secure account or logging in to access the full system features.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="feature-card p-4">
          <div class="icon-circle mb-3 mx-auto">
            <i class="bi bi-file-earmark-text display-5 text-primary"></i>
          </div>
          <h5>2. Report Your Item</h5>
          <p>Submit a detailed report (lost or found) including images and location data.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="feature-card p-4">
          <div class="icon-circle mb-3 mx-auto">
            <i class="bi bi-people display-5 text-primary"></i>
          </div>
          <h5>3. Admin Matching</h5>
          <p>Our dedicated team reviews reports for potential matches and verification.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="feature-card p-4">
          <div class="icon-circle mb-3 mx-auto">
            <i class="bi bi-envelope-open display-5 text-primary"></i>
          </div>
          <h5>4. Receive Response</h5>
          <p>We notify you directly via email or your dashboard once a match is confirmed.</p>
        </div>
      </div>
    </div>
  </section>
 

  <hr class="my-5" />

 <!-- cards-recently lost and found items -->
<?php include'cards.php'?>




  <!-- Footer -->
  <footer class="bg-white text-dark pt-5 pb-3">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <h5 class="fw-bold mb-3 footer-brand">
            <i class="bi bi-search-heart-fill me-2"></i>FindIt
          </h5>
          <p class="text-muted">The secure platform dedicated to matching reported lost items with administratively verified found items.</p>
        </div>
        <div class="col-md-2 col-6 mb-4">
          <h5 class="fw-bold mb-3">Quick Links</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-decoration-none text-muted">Home</a></li>
            <li><a href="browse_items.php" class="text-decoration-none text-muted">Browse Items</a></li>
            <li><a href="../login/login.html" class="text-decoration-none text-muted">Log In</a></li>
          </ul>
        </div>
        <div class="col-md-2 col-6 mb-4">
          <h5 class="fw-bold mb-3">Support</h5>
          <ul class="list-unstyled">
            <li><a href="contact_us.html" class="text-decoration-none text-muted">Contact Us</a></li>
            <li><a href="#" class="text-decoration-none text-muted">FAQ</a></li>
            <li><a href="#" class="text-decoration-none text-muted">Terms of Service</a></li>
          </ul>
        </div>
        <div class="col-md-4 mb-4">
          <h5 class="fw-bold mb-3">Follow Us</h5>
          <p class="text-muted">Stay up to date with our community.</p>
          <div class="d-flex gap-3 fs-4 social-icons">
            <a href="#" class="text-primary"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-primary"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-primary"><i class="bi bi-instagram"></i></a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
