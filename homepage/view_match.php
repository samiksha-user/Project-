<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

include '../config.php';

// Get item_id from URL
$item_id = intval($_GET['item_id'] ?? 0);
if ($item_id <= 0) {
    die("Invalid item.");
}

// Fetch the item details with user info
$stmt = $conn->prepare("SELECT i.*, u.name AS user_name, u.email AS user_email
                        FROM items i
                        JOIN users u ON i.user_id = u.id
                        WHERE i.id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$item_result = $stmt->get_result();
$item = $item_result->fetch_assoc();
$stmt->close();

if (!$item) {
    die("Item not found.");
}

// Fetch potential matches for this item with user info
$title = $item['title'];
$status_opposite = ($item['status'] === 'lost') ? 'found' : 'lost';

$stmt = $conn->prepare("SELECT i.*, u.name AS user_name, u.email AS user_email
                        FROM items i
                        JOIN users u ON i.user_id = u.id
                        WHERE i.id <> ? AND status = ? 
                          AND (LOWER(i.title) LIKE CONCAT('%', ?, '%') OR ? LIKE CONCAT('%', LOWER(i.title), '%'))");
$stmt->bind_param("isss", $item_id, $status_opposite, $title, $title);
$stmt->execute();
$matches_result = $stmt->get_result();
$matches = [];
while ($row = $matches_result->fetch_assoc()) {
    $matches[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Matched Item | FindIt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f8f9fa; padding-top: 70px; font-family: Arial, sans-serif; }
.card { margin-bottom: 20px; }
img { max-height: 200px; width: auto; border-radius: 6px; }
.lost { border-left: 5px solid #ef4444; }
.found { border-left: 5px solid #22c55e; }
.contact-info { margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ccc; }

</style>
</head>
<body>
<?php require_once "navbar.php"; ?>

<div class="container mt-5">
    <h2>🔍 Matched Items for: <?= htmlspecialchars($item['title']) ?></h2>
    

    <!-- Matched Items -->
    <h4>Potential Matches</h4>
    <?php if(count($matches) > 0): ?>
        <?php foreach($matches as $match): ?>
           <div class="card p-3 <?= $match['status'] === 'lost' ? 'lost' : 'found' ?>">
  <div class="d-flex align-items-start gap-3 flex-wrap">

    <!-- LEFT: TEXT -->
    <div style="flex:1; min-width:250px;">
      <p><b>Title:</b> <?= htmlspecialchars($match['title']) ?></p>
      <p><b>Description:</b> <?= htmlspecialchars($match['description']) ?></p>
      <p><b>Status:</b> <?= ucfirst($match['status']) ?></p>
      <p><b>Reported by:</b> <?= htmlspecialchars($match['user_name']) ?></p>
      <p><b>Contact Email:</b>
        <a href="mailto:<?= htmlspecialchars($match['user_email']) ?>">
          <?= htmlspecialchars($match['user_email']) ?>
        </a>
      </p>

      <p class="text-primary fw-semibold">
        💡 You can contact this person via the above email to get your item.
      </p>
    </div>

    <!-- RIGHT: IMAGE -->
    <?php if($match['image'] && file_exists("../uploads/".$match['image'])): ?>
      <div>
        <img src="../uploads/<?= htmlspecialchars($match['image']) ?>"
             class="img-fluid rounded"
             style="max-width:220px; max-height:180px; object-fit:cover;">
      </div>
    <?php endif; ?>

  </div>
</div>

        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">
            No matched items found yet.
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
