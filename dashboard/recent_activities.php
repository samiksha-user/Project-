<?php
// recent_activities.php
include "../config.php";

/* Fetch recent activities */
$sql = "SELECT id, title, category, status, created_at
        FROM items
        ORDER BY created_at DESC
        LIMIT 5";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recent Activities</title>

<style>
/* ===============================
   RECENT ACTIVITIES CARD
================================ */
.recent-activities {
  background: rgba(255, 255, 255, 0.85);
  padding: 25px;
  border-radius: 20px;
  margin-top: 35px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
}

/* Heading */
.recent-activities h2 {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 18px;
  color: #111827;
}

/* Table wrapper */
.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

/* Table */
.activity-table {
  width: 100%;
  border-collapse: collapse;
}

/* Header */
.activity-table thead {
  background: #f9fafb;
}

.activity-table th {
  padding: 14px 18px;
  font-size: 13px;
  font-weight: 600;
  color: #6b7280;
  text-align: left;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

/* Table cells */
.activity-table td {
  padding: 14px 18px;
  font-size: 14px;
  color: #111827;
  border-top: 1px solid #e5e7eb;
  vertical-align: middle;
}

/* Hover */
.activity-table tbody tr:hover {
  background: rgba(99, 102, 241, 0.05);
}

/* Item ID */
.item-id {
  font-weight: 600;
  color: #2563eb;
}

/* Status badge */
.status {
  display: inline-block;
  padding: 6px 16px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  text-transform: capitalize;
}

/* Status colors */
.status.lost {
  background: #fef3c7;
  color: #92400e;
}

.status.found {
  background: #dcfce7;
  color: #15803d;
}

/* Action column */
.action {
  text-align: center;
}

/* Delete button */
.btn-danger {
  background: #ef4444;
  color: #ffffff;
  padding: 6px 16px;
  border-radius: 999px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s ease, transform 0.15s ease;
}

.btn-danger:hover {
  background: #dc2626;
  transform: scale(1.05);
}

/* Responsive */
@media (max-width: 768px) {
  .activity-table th,
  .activity-table td {
    padding: 12px;
    font-size: 13px;
  }
}
</style>
</head>

<body>

<div class="recent-activities">
  <h2>Recent Activities</h2>

  <div class="table-wrapper">
    <table class="activity-table">
      <thead>
        <tr>
          <th>Item ID</th>
          <th>Item Details</th>
          <th>Category</th>
          <th>Date Reported</th>
          <th>Status</th>
          <th style="text-align:center;">Action</th>
        </tr>
      </thead>

      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="item-id">LF-<?= (int)$row['id'] ?></td>

              <td>
                <strong><?= htmlspecialchars($row['title']) ?></strong>
              </td>

              <td><?= htmlspecialchars($row['category']) ?></td>

              <td><?= date("Y-m-d", strtotime($row['created_at'])) ?></td>

              <td>
                <span class="status <?= strtolower($row['status']) ?>">
                  <?= ucfirst($row['status']) ?>
                </span>
              </td>

              <td class="action">
                <a href="?delete=<?= (int)$row['id'] ?>"
                   onclick="return confirm('Delete this report?')"
                   class="btn-danger">
                   Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" style="text-align:center; padding: 20px;">
              No activities found
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
