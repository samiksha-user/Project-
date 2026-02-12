<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

include "../config.php";


/* TOTAL REPORTS */
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM items");
if ($totalResult === false) {
    $totalReports = 0;
    error_log("Dashboard Error: " . $conn->error);
} else {
    $totalReports = $totalResult->fetch_assoc()['total'];
}

/* LOST ITEMS */
// Use status column (from database schema)
$lostResult = $conn->query("SELECT COUNT(*) AS total FROM items WHERE status='lost'");
if ($lostResult === false) {
    // Fallback: try report_type if status doesn't exist
    $lostResult = $conn->query("SELECT COUNT(*) AS total FROM items WHERE report_type='lost'");
}
if ($lostResult === false) {
    $lostCount = 0;
    error_log("Dashboard Error (Lost): " . $conn->error);
} else {
    $lostCount = $lostResult->fetch_assoc()['total'];
}

/* FOUND ITEMS */
$foundResult = $conn->query("SELECT COUNT(*) AS total FROM items WHERE status='found'");
if ($foundResult === false) {
    // Fallback: try report_type if status doesn't exist
    $foundResult = $conn->query("SELECT COUNT(*) AS total FROM items WHERE report_type='found'");
}
if ($foundResult === false) {
    $foundCount = 0;
    error_log("Dashboard Error (Found): " . $conn->error);
} else {
    $foundCount = $foundResult->fetch_assoc()['total'];
}

/* POTENTIAL MATCH COUNT */
// Use status and title columns (from database schema)
$matchSql = "
SELECT COUNT(*) AS total
FROM items l
JOIN items f
ON (
    LOWER(l.title) LIKE CONCAT('%', SUBSTRING_INDEX(LOWER(f.title), ' ', -1), '%')
    OR
    LOWER(f.title) LIKE CONCAT('%', SUBSTRING_INDEX(LOWER(l.title), ' ', -1), '%')
)
WHERE l.status = 'lost'
  AND f.status = 'found'
  AND l.id <> f.id
";

$matchResult = $conn->query($matchSql);
if ($matchResult === false) {
    // Fallback: try with item_name and report_type
    $matchSql = "
    SELECT COUNT(*) AS total
    FROM items l
    JOIN items f
    ON (
        LOWER(l.item_name) LIKE CONCAT('%', SUBSTRING_INDEX(LOWER(f.item_name), ' ', -1), '%')
        OR
        LOWER(f.item_name) LIKE CONCAT('%', SUBSTRING_INDEX(LOWER(l.item_name), ' ', -1), '%')
    )
    WHERE l.report_type = 'lost'
      AND f.report_type = 'found'
      AND l.id <> f.id
    ";
    $matchResult = $conn->query($matchSql);
}
if ($matchResult === false) {
    $matchCount = 0;
    error_log("Dashboard Error (Match): " . $conn->error);
} else {
    $matchCount = $matchResult->fetch_assoc()['total'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lost & Found Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="dashboard">

  <!-- SIDEBAR -->
<!-- INCLUDE SIDEBAR -->
  <?php require_once "sidebar.php"; ?>

  <!-- MAIN CONTENT -->
  <main class="content">
    <h1>Admin Dashboard</h1>
    <br>
    <br>

    <!-- STATS -->
    <div class="stats">
      <div class="card" data-page="dashboard">
        <p>Total Reports</p>
      <h2><?= $totalReports ?></h2>

      </div>
      <div class="card" data-page="lost">
        <p>Lost Items</p>
       <h2><?= $lostCount ?></h2>

      </div>
      <div class="card" data-page="found">
        <p>Found Items</p>
        <h2><?= $foundCount ?></h2>

      </div>
      <div class="card" data-page="match">
        <p>Potential Matches</p>
        <h2><?= $matchCount ?></h2>


      </div>
    </div>

    <!-- RECENT ACTIVITIES -->
 <?php include __DIR__ . "/recent_activities.php"; ?>


    <!-- DYNAMIC CONTENT -->
    <div id="content"></div>
  </main>
  

</div>

<script src="aa.js"></script>
</body>
</html>
