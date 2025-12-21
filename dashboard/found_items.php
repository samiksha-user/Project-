<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

include "../config.php";

/* DELETE */
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  header("Location: found_items.php");
  exit;
}

/* FETCH FOUND ITEMS */
// Try with status column first (from schema), fallback to report_type
$sql = "SELECT * FROM items WHERE status = 'found' ORDER BY created_at DESC";
$result = $conn->query($sql);

// If query fails, try with report_type
if ($result === false) {
    $sql = "SELECT * FROM items WHERE report_type = 'found' ORDER BY created_at DESC";
    $result = $conn->query($sql);
}

// Handle query errors
if ($result === false) {
    $count = 0;
    $error_message = "Database error: " . $conn->error;
} else {
    $count = $result->num_rows;
    $error_message = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Found Reports</title>



<style>
/* ===== Base ===== */
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  background: #eef2f7;
  font-family: "Inter", "Segoe UI", sans-serif;
  color: #1e293b;
}

/* ===== Layout ===== */
.dashboard {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  padding: 32px;
}

/* ===== Title ===== */
.page-title {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 24px;
}

/* ===== Table Card ===== */
.reports-table {
  width: 100%;
  background: #ffffff;
  border-radius: 14px;
  border-collapse: separate;
  border-spacing: 0;
  box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
  overflow: hidden;
}

/* ===== Header ===== */
.reports-table thead th {
  background: linear-gradient(180deg, #f8fafc, #f1f5f9);
  padding: 16px 14px;
  font-size: 13px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  color: #475569;
  border-bottom: 1px solid #e2e8f0;
  text-align: left;
}

/* ===== Rows ===== */
.reports-table tbody tr {
  transition: background 0.2s ease, transform 0.15s ease;
}

.reports-table tbody tr:hover {
  background: #f8fafc;
  transform: scale(1.002);
}

/* ===== Cells ===== */
.reports-table td {
  padding: 18px 14px;
  font-size: 14px;
  border-bottom: 1px solid #e5e7eb;
  vertical-align: middle;
}

/* ===== ID ===== */
.reports-table td:first-child {
  font-weight: 700;
  color: #0f172a;
}

/* ===== Item Name ===== */
.reports-table td:nth-child(2) {
  font-weight: 600;
}

/* ===== Description ===== */
.reports-table td:nth-child(3) {
  max-width: 320px;
  line-height: 1.6;
  color: #475569;
}

/* ===== Color Badge ===== */
.reports-table td:nth-child(4) {
  font-weight: 600;
  color: #334155;
}

/* ===== Image Column ===== */
.reports-table td:nth-child(5) {
  text-align: center;
}

/* ===== Image ===== */
.item-img {
  width: 72px;
  height: 72px;
  object-fit: cover;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  background: #f8fafc;
  box-shadow: 0 6px 16px rgba(0,0,0,0.08);
  transition: transform 0.25s ease;
}

.item-img:hover {
  transform: scale(1.08);
}

/* ===== No Image ===== */
.text-muted {
  font-size: 13px;
  color: #94a3b8;
}

/* ===== Date ===== */
.reports-table td:nth-child(6) {
  font-size: 13px;
  color: #64748b;
  white-space: nowrap;
}

/* ===== Action ===== */
.reports-table td:last-child {
  text-align: center;
}

/* ===== Delete Button ===== */
.btn-danger {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: #fff;
  border: none;
  padding: 7px 14px;
  font-size: 13px;
  font-weight: 600;
  border-radius: 999px;
  text-decoration: none;
  cursor: pointer;
  box-shadow: 0 8px 18px rgba(239, 68, 68, 0.35);
  transition: all 0.2s ease;
}

.btn-danger:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 22px rgba(239, 68, 68, 0.45);
}

/* ===== Empty State ===== */
.alert-info {
  background: #ffffff;
  padding: 22px;
  border-radius: 14px;
  font-size: 15px;
  color: #0369a1;
  box-shadow: 0 10px 25px rgba(0,0,0,0.06);
}

</style>
</head>

<body>
  <div class="dashboard">
    <!-- INCLUDE SIDEBAR -->
  <?php require_once "sidebar.php"; ?>

<div class="main-content">
  <h3 class="page-title">Found Reports (<?= $count ?>)</h3>

  <?php if ($error_message): ?>
    <div class="alert alert-danger">
      <strong>Error:</strong> <?= htmlspecialchars($error_message) ?>
      <br><small>Check if your items table has 'status' or 'report_type' column.</small>
    </div>
  <?php endif; ?>

  <?php if ($count > 0): ?>
  <table class="table reports-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Item Name</th>
        <th>Description</th>
        <th>Color</th>
        <th>Image</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?? $row['item_id'] ?? 'N/A' ?></td>

        <td><?= htmlspecialchars($row['title'] ?? $row['item_name'] ?? 'Unknown') ?></td>

        <td><?= htmlspecialchars($row['description'] ?? 'No description') ?></td>

        <td><?= htmlspecialchars($row['color'] ?? 'N/A') ?></td>

        <td>
          <?php if (!empty($row['image'])): ?>
            <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="item-img">
          <?php else: ?>
            <span class="text-muted">No image</span>
          <?php endif; ?>
        </td>

        <td><?= date("Y-m-d", strtotime($row['created_at'])) ?></td>

        <td>
          <a href="?delete=<?= $row['id'] ?? $row['item_id'] ?>"
             onclick="return confirm('Delete this report?')"
             class="btn btn-danger btn-sm">
             Delete
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <div class="alert alert-info">No found reports found.</div>
  <?php endif; ?>
</div>

</body>
</html>
