<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

include '../config.php';

// First, detect which columns exist
$columns_result = $conn->query("SHOW COLUMNS FROM items");
$columns = [];
while ($col = $columns_result->fetch_assoc()) {
    $columns[] = $col['Field'];
}

$has_status = in_array('status', $columns);
$has_report_type = in_array('report_type', $columns);
$has_title = in_array('title', $columns);
$has_item_name = in_array('item_name', $columns);
$has_id = in_array('id', $columns);
$has_item_id = in_array('item_id', $columns);
$has_color = in_array('color', $columns);

$result = false;

// Build query based on actual columns
if ($has_status && $has_title && $has_id) {
    // Use status, title, id (from your schema)
    $color_lost = $has_color ? "l.color AS lost_color," : "NULL AS lost_color,";
    $color_found = $has_color ? "f.color AS found_color," : "NULL AS found_color,";
    
    $sql = "SELECT 
        l.id AS lost_id,
        l.title AS lost_item,
        l.description AS lost_desc,
        " . $color_lost . "
        l.image AS lost_image,
        l.created_at AS lost_date,
        l.user_id AS lost_user_id,
        lu.name AS lost_user_name,
        lu.email AS lost_user_email,

        f.id AS found_id,
        f.title AS found_item,
        f.description AS found_desc,
        " . $color_found . "
        f.image AS found_image,
        f.created_at AS found_date,
        f.user_id AS found_user_id,
        fu.name AS found_user_name,
        fu.email AS found_user_email
    FROM items l
    CROSS JOIN items f
    LEFT JOIN users lu ON l.user_id = lu.id
    LEFT JOIN users fu ON f.user_id = fu.id
    WHERE l.status = 'lost'
      AND f.status = 'found'
      AND l.id <> f.id
      AND (
        LOWER(l.title) LIKE CONCAT('%', LOWER(f.title), '%')
        OR LOWER(f.title) LIKE CONCAT('%', LOWER(l.title), '%')
        OR LOWER(l.title) LIKE CONCAT('%', SUBSTRING_INDEX(LOWER(f.title), ' ', -1), '%')
        OR LOWER(f.title) LIKE CONCAT('%', SUBSTRING_INDEX(LOWER(l.title), ' ', -1), '%')
      )
    ORDER BY l.id DESC
    LIMIT 50";
    
    $result = $conn->query($sql);
} elseif ($has_report_type && $has_item_name && $has_item_id) {
    // Use report_type, item_name, item_id
    $color_lost = $has_color ? "l.color AS lost_color," : "NULL AS lost_color,";
    $color_found = $has_color ? "f.color AS found_color," : "NULL AS found_color,";
    
    $sql = "SELECT 
        l.item_id AS lost_id,
        l.item_name AS lost_item,
        l.description AS lost_desc,
        " . $color_lost . "
        l.image AS lost_image,
        l.created_at AS lost_date,
        l.user_id AS lost_user_id,
        lu.name AS lost_user_name,
        lu.email AS lost_user_email,

        f.item_id AS found_id,
        f.item_name AS found_item,
        f.description AS found_desc,
        " . $color_found . "
        f.image AS found_image,
        f.created_at AS found_date,
        f.user_id AS found_user_id,
        fu.name AS found_user_name,
        fu.email AS found_user_email
    FROM items l
    CROSS JOIN items f
    LEFT JOIN users lu ON l.user_id = lu.id
    LEFT JOIN users fu ON f.user_id = fu.id
    WHERE l.report_type = 'lost'
      AND f.report_type = 'found'
      AND l.item_id <> f.item_id
      AND (
        LOWER(l.item_name) LIKE CONCAT('%', LOWER(f.item_name), '%')
        OR LOWER(f.item_name) LIKE CONCAT('%', LOWER(l.item_name), '%')
        OR LOWER(l.item_name) LIKE CONCAT('%', SUBSTRING_INDEX(LOWER(f.item_name), ' ', -1), '%')
        OR LOWER(f.item_name) LIKE CONCAT('%', SUBSTRING_INDEX(LOWER(l.item_name), ' ', -1), '%')
      )
    ORDER BY l.item_id DESC
    LIMIT 50";
    
    $result = $conn->query($sql);
} else {
    // Build query dynamically based on available columns
    $id_col = $has_id ? 'id' : ($has_item_id ? 'item_id' : 'id');
    $name_col = $has_title ? 'title' : ($has_item_name ? 'item_name' : 'title');
    $status_col = $has_status ? 'status' : ($has_report_type ? 'report_type' : 'status');
    
    $color_select = $has_color ? "l.color AS lost_color," : "NULL AS lost_color,";
    $color_select_found = $has_color ? "f.color AS found_color," : "NULL AS found_color,";
    
    $sql = "SELECT 
        l.$id_col AS lost_id,
        l.$name_col AS lost_item,
        l.description AS lost_desc,
        $color_select
        l.image AS lost_image,
        l.created_at AS lost_date,
        l.user_id AS lost_user_id,
        lu.name AS lost_user_name,
        lu.email AS lost_user_email,

        f.$id_col AS found_id,
        f.$name_col AS found_item,
        f.description AS found_desc,
        $color_select_found
        f.image AS found_image,
        f.created_at AS found_date,
        f.user_id AS found_user_id,
        fu.name AS found_user_name,
        fu.email AS found_user_email
    FROM items l
    CROSS JOIN items f
    LEFT JOIN users lu ON l.user_id = lu.id
    LEFT JOIN users fu ON f.user_id = fu.id
    WHERE l.$status_col = 'lost'
      AND f.$status_col = 'found'
      AND l.$id_col <> f.$id_col
      AND (
        LOWER(l.$name_col) LIKE CONCAT('%', LOWER(f.$name_col), '%')
        OR LOWER(f.$name_col) LIKE CONCAT('%', LOWER(l.$name_col), '%')
      )
    ORDER BY l.$id_col DESC
    LIMIT 50";
    
    $result = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Matched Items</title>

<style>
body{
  font-family: Arial, sans-serif;
  background:#f3f4f6;
  margin:0;
  padding:0;
}

.dashboard{
  display:flex;
  min-height:100vh;
}

.content{
  flex:1;
  padding:30px;
}

h2{
  margin-top:0;
}
.sub{
  color:#6b7280;
  margin-bottom:25px;
}

.card{
  background:#fff;
  border:2px solid #fbbf24;
  border-radius:10px;
  padding:20px;
  max-width:900px;
  margin-bottom:30px;
}


.score{
  background:#F8DE7E;
  padding:10px;
  font-weight:bold;
  border-radius:6px;
  margin-bottom:15px;
}

.items{
  display:flex;
  gap:20px;
  flex-wrap:wrap;
}

.item{
  flex:1;
  background:#f9fafb;
  padding:15px;
  border-radius:6px;
}

.lost{
  border-left:5px solid #ef4444;
}

.found{
  border-left:5px solid #22c55e;
}

img{
  width:100%;
  max-height:180px;
  object-fit:cover;
  border-radius:6px;
  margin-bottom:10px;
}

.btn{
  margin-top:15px;
  width:100%;
  padding:12px;
  background:#2563eb;
  color:#fff;
  border:none;
  border-radius:6px;
  cursor:pointer;
}

.btn:hover{
  background:#1e40af;
}
</style>
</head>

<body>

<div class="dashboard">

<?php require_once "sidebar.php"; ?>

<div class="content">
<h2>🔍 Potential Item Matches</h2>
<p class="sub">Automatically matched lost and found items</p>

<?php if(isset($_GET['notified']) && $_GET['notified'] == 1): ?>
<div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
  ✅ Notification sent successfully!
</div>
<?php endif; ?>

<?php if($result && $result !== false && $result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()): ?>

<div class="card">
  <div class="score">Match Reason: Common keyword detected</div>

  <div class="items">

    <!-- LOST -->
    <div class="item lost">
      <h3>❌ LOST: <?= htmlspecialchars($row['lost_item'] ?? $row['lost_item_alt'] ?? 'Unknown') ?></h3>

      <?php if($row['lost_image'] && file_exists("../uploads/".$row['lost_image'])): ?>
        <img src="../uploads/<?= htmlspecialchars($row['lost_image']) ?>">
      <?php else: ?>
        <img src="../uploads/placeholder.png">
      <?php endif; ?>

      <p><?= htmlspecialchars($row['lost_desc']) ?></p>
      <p><b>Color:</b> <?= htmlspecialchars($row['lost_color'] ?? 'N/A') ?></p>
      <p><b>Date:</b> <?= date('Y-m-d', strtotime($row['lost_date'])) ?></p>
      
      <!-- User Information -->
      <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #ef4444;">
        <h4 style="margin: 0 0 10px 0; color: #ef4444;">👤 Reported By:</h4>
        <p style="margin: 5px 0;"><b>Name:</b> <?= htmlspecialchars($row['lost_user_name'] ?? 'Unknown') ?></p>
        <p style="margin: 5px 0;"><b>Email:</b> <a href="mailto:<?= htmlspecialchars($row['lost_user_email'] ?? '') ?>"><?= htmlspecialchars($row['lost_user_email'] ?? 'N/A') ?></a></p>
        <p style="margin: 5px 0;"><b>User ID:</b> <?= htmlspecialchars($row['lost_user_id'] ?? 'N/A') ?></p>
      </div>
    </div>

    <!-- FOUND -->
    <div class="item found">
      <h3>✅ FOUND: <?= htmlspecialchars($row['found_item'] ?? $row['found_item_alt'] ?? 'Unknown') ?></h3>

      <?php if($row['found_image'] && file_exists("../uploads/".$row['found_image'])): ?>
        <img src="../uploads/<?= htmlspecialchars($row['found_image']) ?>">
      <?php else: ?>
        <img src="../uploads/placeholder.png">
      <?php endif; ?>

      <p><?= htmlspecialchars($row['found_desc']) ?></p>
      <p><b>Color:</b> <?= htmlspecialchars($row['found_color'] ?? 'N/A') ?></p>
      <p><b>Date:</b> <?= date('Y-m-d', strtotime($row['found_date'])) ?></p>
      
      <!-- User Information -->
      <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #22c55e;">
        <h4 style="margin: 0 0 10px 0; color: #22c55e;">👤 Found By:</h4>
        <p style="margin: 5px 0;"><b>Name:</b> <?= htmlspecialchars($row['found_user_name'] ?? 'Unknown') ?></p>
        <p style="margin: 5px 0;"><b>Email:</b> <a href="mailto:<?= htmlspecialchars($row['found_user_email'] ?? '') ?>"><?= htmlspecialchars($row['found_user_email'] ?? 'N/A') ?></a></p>
        <p style="margin: 5px 0;"><b>User ID:</b> <?= htmlspecialchars($row['found_user_id'] ?? 'N/A') ?></p>
      </div>
    </div>

  </div>

  <div style="display: flex; gap: 10px; margin-top: 15px;">
    <form method="post" action="notify.php" style="flex: 1;">
      <input type="hidden" name="lost_id" value="<?= $row['lost_id'] ?>">
      <input type="hidden" name="found_id" value="<?= $row['found_id'] ?>">
      <input type="hidden" name="lost_user_email" value="<?= htmlspecialchars($row['lost_user_email'] ?? '') ?>">
      <button class="btn" type="submit">📧 Notify Lost User</button>
    </form>
    <form method="post" action="notify.php" style="flex: 1;">
      <input type="hidden" name="lost_id" value="<?= $row['lost_id'] ?>">
      <input type="hidden" name="found_id" value="<?= $row['found_id'] ?>">
      <input type="hidden" name="found_user_email" value="<?= htmlspecialchars($row['found_user_email'] ?? '') ?>">
      <button class="btn" type="submit" style="background: #22c55e;">📧 Notify Found User</button>
    </form>
  </div>

</div>

<?php endwhile; ?>
<?php else: ?>
<div style="background: #fff; padding: 40px; border-radius: 10px; text-align: center; border: 2px dashed #ddd;">
  <h3 style="color: #6b7280; margin-bottom: 10px;">No matches found</h3>
  <p style="color: #9ca3af;">There are currently no potential matches between lost and found items.</p>
  <p style="color: #9ca3af; font-size: 0.9em; margin-top: 10px;">
    Matches are found when lost and found items have similar names or keywords.
  </p>
</div>
<?php endif; ?>

</div>
</div>

</body>
</html>
