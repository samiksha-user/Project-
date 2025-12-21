<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lost & Found Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="sidebar.css">
</head>

<body>

<div class="dashboard">

  <!-- SIDEBAR -->
  <aside class="sidebar">

    <div>
      <div class="profile">
        <div class="avatar"><?= strtoupper(substr($_SESSION['fullname'] ?? 'A', 0, 1)) ?></div>
        <div>
          <h4><?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin User') ?></h4>
          <p><?= htmlspecialchars($_SESSION['email'] ?? 'admin@findit.com') ?></p>
        </div>
      </div>

      <nav>
        <a class="nav-link <?= $currentPage=='dashboard.php'?'active':'' ?>" href="dashboard.php">
          <i class="bi bi-grid"></i> Dashboard
        </a>

        <a class="nav-link <?= $currentPage=='lost_item.php'?'active':'' ?>" href="lost_item.php">
          <i class="bi bi-x-circle"></i> Lost Reports
        </a>

        <a class="nav-link <?= $currentPage=='found_items.php'?'active':'' ?>" href="found_items.php">
          <i class="bi bi-check-circle"></i> Found Reports
        </a>

        <a class="nav-link <?= ($currentPage=='match.php' || $currentPage=='match')?'active':'' ?>" href="match.php">
          <i class="bi bi-search"></i> Match Reports
        </a>

        <a class="nav-link <?= ($currentPage=='user_manage.php' || $currentPage=='users.php')?'active':'' ?>" href="user_manage.php">
          <i class="bi bi-people"></i> User Management
        </a>
      </nav>
    </div>

    <div class="logout">
      <a href="../login/logout.php" onclick="return confirm('Are you sure you want to logout?');">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>

  </aside>

</div>

</body>
</html>
