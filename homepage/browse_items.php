<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Browse Items | FindIt</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="browse_items.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="homepage.php">
        <i class="bi bi-search-heart-fill me-2"></i>FindIt
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link" href="homepage.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="report_item.php">Report Item</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="browse_items.php">Browse Items</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact_us.php">Contact Us</a>
          </li>
          <?php if(empty($_SESSION['user_id'])): ?>
          <!-- Show Sign Up and Log In buttons when user is NOT logged in -->
          <li class="nav-item ms-lg-3">
            <a class="btn btn-outline-primary rounded-pill w-100 mt-2 mt-lg-0" href="../signup/signup.php">
              Sign Up
            </a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-primary rounded-pill w-100 mt-2 mt-lg-0" href="../login/login.php">Log In</a>
          </li>
          <?php else: ?>
          <!-- Show user info and Logout button when user IS logged in -->
          <li class="nav-item ms-lg-3">
            <span class="text-muted me-2">Welcome, <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'User'); ?>!</span>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-danger rounded-pill w-100 mt-2 mt-lg-0" href="../login/logout.php">Logout</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Header -->
  <header class="container mt-5 pt-5 text-center">
    <h1 class="fw-bold">Browse Lost & Found Items</h1>
    <p class="text-muted">Search and filter items to help find what you're looking for.</p>
  </header>

  <!-- Browse Form -->
  <section class="container my-5">
    <form class="row g-3">
      <div class="col-md-4">
        <label for="keyword" class="form-label">Keyword</label>
        <input type="text" class="form-control" id="keyword" placeholder="e.g., wallet, phone">
      </div>
      <div class="col-md-4">
        <label for="category" class="form-label">Category</label>
        <select class="form-select" id="category">
          <option selected>All Categories</option>
          <option>Electronics</option>
          <option>Documents</option>
          <option>Clothing</option>
          <option>Jewelry</option>
          <option>Others</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="location" class="form-label">Location</label>
        <input type="text" class="form-control" id="location" placeholder="City, Area, or Building">
      </div>
      <div class="col-12 text-center mt-3">
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
          <i class="bi bi-search me-2"></i> Search
        </button>
      </div>
    </form>
  </section>

  <!-- Items Display -->
  <section class="container my-5">
    <div class="row g-4">
      <!-- Example item card -->
      <div class="col-md-4">
        <div class="card shadow-sm">
          <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Lost item image">
          <div class="card-body">
            <h5 class="card-title">Lost Wallet</h5>
            <p class="card-text"><strong>Category:</strong> Documents<br><strong>Location:</strong> Kathmandu</p>
            <a href="#" class="btn btn-primary">View Details</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm">
          <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Found item image">
          <div class="card-body">
            <h5 class="card-title">Found Phone</h5>
            <p class="card-text"><strong>Category:</strong> Electronics<br><strong>Location:</strong> Lalitpur</p>
            <a href="#" class="btn btn-primary">View Details</a>
          </div>
        </div>
      </div>
      <!-- Add more items dynamically here -->
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-white text-dark pt-5 pb-3">
    <div class="container text-center">
      <p class="text-muted mb-0">&copy; 2025 FindIt. All rights reserved.</p>
    </div>
  </footer>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
