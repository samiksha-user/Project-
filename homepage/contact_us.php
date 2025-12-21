<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us | FindIt</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="homepage.css" />
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
            <a class="nav-link" href="browse_items.php">Browse Items</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="contact_us.php">Contact Us</a>
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

  <!-- Contact Section -->
  <section class="contact container mt-5 pt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <h1 class="text-center fw-bold mb-4">Contact Us</h1>
        <p class="text-center mb-4">Have questions or need help? Send us a message and we'll get back to you.</p>

        <form action="contact_process.php" method="post" class="p-4 border rounded shadow-sm bg-light">
          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Your message..." required></textarea>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary rounded-pill px-4">Send Message</button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-light text-center py-3 mt-5">
    <p class="mb-0 text-muted">© 2025 FindIt - Lost & Found System | Designed by Samiksha</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
