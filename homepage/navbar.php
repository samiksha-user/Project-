<?php 
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include'../config.php';
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
 <style>@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');


/* --- General Styles --- */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa; 
    color: #343a40;
    padding-top: 70px; 
    overflow-x: hidden;
}

.section-title {
    color: #0b2e4d; 
    font-size: 2.5rem;
    font-weight: 700;
}

/* --- Navbar --- */
.navbar {
    background: white !important;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}
.navbar-brand {
    font-weight: 700;
    color: #007bff !important;
    font-size: 1.6rem;
}
.navbar-brand i {
    color: #00bfa6; /* Teal accent */
}
.nav-link {
    color: #333 !important;
    font-weight: 500;
    position: relative;
    transition: 0.3s;
}
/* Interactive Hover for Nav Links */
.nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    width: 0;
    height: 3px;
    background: #00bfa6;
    transition: width 0.3s ease-out, left 0.3s ease-out;
}
.nav-link:hover::after, .nav-link.active::after {
    width: 70%;
    left: 15%;
}
.nav-link:hover, .nav-link.active {
    color: #00bfa6 !important;
}

/* --- Buttons --- */
.btn-primary {
    background: linear-gradient(45deg, #007bff, #00bfa6);
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 123, 255, 0.4);
    background: linear-gradient(45deg, #00bfa6, #007bff);
}
.btn-outline-primary {
    border: 2px solid #007bff;
    color: #007bff;
    font-weight: 600;
    transition: all 0.3s;
}
.btn-outline-primary:hover {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

/* --- Hero Section --- */
.hero {
    min-height: 90vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.hero h1 {
    font-weight: 700;
    font-size: 3.5rem;
    line-height: 1.2;
    color: #0b2e4d;
}
.hero span {
    background: linear-gradient(90deg, #007bff, #00bfa6);
    background-clip: text;
    -webkit-text-fill-color: transparent;
}
.hero p {
    color: #6c757d;
    font-size: 1.15rem;
}
.hero-image {
    width: 100%;
    max-height: 600px;
    object-fit: cover;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

/* Hero Search Bar Styles */
.hero-search .form-control-lg {
    padding: 15px 25px;
    font-size: 1.1rem;
    border-right: none;
}
.hero-search button {
    font-size: 1.1rem;
}
@media (max-width: 576px) {
    .hero-search .form-control-lg {
        border-radius: 30px !important;
    }
    .hero-search button {
        margin-top: 10px;
        width: 100%;
        border-radius: 30px !important;
    }
    .hero-search {
        flex-direction: column;
    }
}


/* Scroll Indicator Animation */
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-8px);
    }
    60% {
        transform: translateY(-4px);
    }
}
.animated-scroll {
    animation: bounce 2s infinite;
    display: block;
    font-size: 1.5rem;
}

/* --- Features Section --- */
.features {
    padding: 50px 0;
}
.feature-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    height: 100%;
    border-bottom: 5px solid transparent; 
}
/* Interactive Hover for Feature Cards */
.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    border-bottom-color: #007bff;
}
.feature-card h5 {
    font-weight: 600;
    margin-top: 15px;
    color: #0b2e4d;
}
.feature-card p {
    color: #6c757d;
    font-size: 0.95rem;
}
.icon-circle {
    width: 80px;
    height: 80px;
    background-color: #e9f0ff; 
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.icon-circle i {
    font-size: 2.2rem !important;
    background: linear-gradient(45deg, #007bff, #00bfa6);
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* --- CTA Banner --- */
.cta-banner {
    background: linear-gradient(135deg, #007bff, #00bfa6);
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 123, 255, 0.2);
}
.cta-banner .cta-btn:hover {
    background-color: #f1f1f1;
    transform: scale(1.05);
    color: #007bff !important;
}

/* --- Footer --- */
footer {
    background-color: #fff !important;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
}
.footer-brand {
    color: #0b2e4d;
}
footer a {
    color: #6c757d !important;
    transition: color 0.3s;
}
footer a:hover {
    color: #007bff !important;
}
.social-icons a {
    color: #007bff !important;
    transition: transform 0.2s;
}
.social-icons a:hover {
    transform: translateY(-3px);
}

</style>
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
            <a class="nav-link active" aria-current="page" href="homepage.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="report_item.php">Report Item</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="browse_items.php">Browse Items</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="view_reports.php">View Reports</a>
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
          <!-- Show user info, notifications, and Logout button when user IS logged in -->
          <?php
          // Get unread notification count (config.php is already included above)
          $notification_count = 0;
          if (isset($_SESSION['user_id']) && isset($conn) && file_exists('../dashboard/create_notification.php')) {
              include_once '../dashboard/create_notification.php';
              $notification_count = getUnreadNotificationCount($conn, $_SESSION['user_id']);
          }
          ?>
          <li class="nav-item ms-lg-3">
            <span class="text-muted me-2">Welcome, <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'User'); ?>!</span>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-outline-primary rounded-pill position-relative" href="notifications.php" style="text-decoration: none;">
              <i class="bi bi-bell"></i> Notifications
              <?php if ($notification_count > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  <?= $notification_count ?>
                  <span class="visually-hidden">unread notifications</span>
                </span>
              <?php endif; ?>
            </a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-danger rounded-pill w-100 mt-2 mt-lg-0" href="../login/logout.php">Logout</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
