<?php
include '../config.php';

$sql = "SELECT 
    l.item_id AS lost_id,
    l.item_name AS lost_item,
    l.description AS lost_desc,
    l.color AS lost_color,
    l.image AS lost_image,
    l.created_at AS lost_date,

    f.item_id AS found_id,
    f.item_name AS found_item,
    f.description AS found_desc,
    f.color AS found_color,
    f.image AS found_image,
    f.created_at AS found_date
FROM items l
JOIN items f
ON (
    LOWER(l.item_name) LIKE CONCAT('%', SUBSTRING_INDEX(LOWER(f.item_name), ' ', -1), '%')
    OR
    LOWER(f.item_name) LIKE CONCAT('%', SUBSTRING_INDEX(LOWER(l.item_name), ' ', -1), '%')
)
WHERE l.report_type = 'lost'
  AND f.report_type = 'found'
  AND l.item_id <> f.item_id
ORDER BY l.item_id DESC";

$result = $conn->query($sql);
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

<?php if($result && $result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()): ?>

<div class="card">
  <div class="score">Match Reason: Common keyword detected</div>

  <div class="items">

    <!-- LOST -->
    <div class="item lost">
      <h3>❌ LOST: <?= htmlspecialchars($row['lost_item']) ?></h3>

      <?php if($row['lost_image'] && file_exists("../uploads/".$row['lost_image'])): ?>
        <img src="../uploads/<?= htmlspecialchars($row['lost_image']) ?>">
      <?php else: ?>
        <img src="../uploads/placeholder.png">
      <?php endif; ?>

      <p><?= htmlspecialchars($row['lost_desc']) ?></p>
      <p><b>Color:</b> <?= htmlspecialchars($row['lost_color']) ?></p>
      <p><b>Date:</b> <?= $row['lost_date'] ?></p>
    </div>

    <!-- FOUND -->
    <div class="item found">
      <h3>✅ FOUND: <?= htmlspecialchars($row['found_item']) ?></h3>

      <?php if($row['found_image'] && file_exists("../uploads/".$row['found_image'])): ?>
        <img src="../uploads/<?= htmlspecialchars($row['found_image']) ?>">
      <?php else: ?>
        <img src="../uploads/placeholder.png">
      <?php endif; ?>

      <p><?= htmlspecialchars($row['found_desc']) ?></p>
      <p><b>Color:</b> <?= htmlspecialchars($row['found_color']) ?></p>
      <p><b>Date:</b> <?= $row['found_date'] ?></p>
    </div>

  </div>

  <form method="post" action="notify.php">
    <input type="hidden" name="lost_id" value="<?= $row['lost_id'] ?>">
    <input type="hidden" name="found_id" value="<?= $row['found_id'] ?>">
    <button class="btn">Notify Lost User</button>
  </form>

</div>

<?php endwhile; ?>
<?php else: ?>
<p>No matches found.</p>
<?php endif; ?>

</div>
</div>

</body>
</html>
