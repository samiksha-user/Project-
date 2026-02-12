<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

include '../config.php';

$user_id = $_SESSION['user_id'];

/* ================= DELETE REPORT ================= */
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // get image name to delete file
    $stmt = $conn->prepare("SELECT image FROM items WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $item = $res->fetch_assoc();
    $stmt->close();

    if ($item) {
        if (!empty($item['image']) && file_exists("../uploads/" . $item['image'])) {
            unlink("../uploads/" . $item['image']);
        }

        $stmt = $conn->prepare("DELETE FROM items WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $delete_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: view_reports.php");
    exit;
}

/* ================= FETCH USER ITEMS ================= */
$stmt = $conn->prepare("SELECT * FROM items WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Reports | FindIt</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: #f8f9fa;
    padding-top: 70px;
    font-family: Arial, sans-serif;
}
.report-card {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 15px;
    border-radius: 10px;
    background: #fff;
    margin-bottom: 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
.report-text {
    flex: 1;
}
.report-img img {
    width: 220px;
    height: 160px;
    object-fit: cover;
    border-radius: 8px;
}
.lost { border-left: 5px solid #ef4444; }
.found { border-left: 5px solid #22c55e; }
</style>
</head>

<body>

<?php require_once "navbar.php"; ?>

<div class="container mt-4">
    <h2>📋 My Reported Items</h2>
    <p class="text-muted">Items you have reported (lost or found)</p>

    <?php if (count($items) > 0): ?>
        <?php foreach ($items as $item): ?>
            <div class="report-card <?= $item['status'] === 'lost' ? 'lost' : 'found' ?>">
                
                <div class="report-text">
                    <p><b>Title:</b> <?= htmlspecialchars($item['title']) ?></p>
                    <p><b>Description:</b> <?= htmlspecialchars($item['description']) ?></p>
                    <p><b>Category:</b> <?= htmlspecialchars($item['category'] ?? 'N/A') ?></p>
                    <p><b>Status:</b> <?= ucfirst($item['status']) ?></p>
                    <p><b>Reported On:</b> <?= date('F j, Y', strtotime($item['created_at'])) ?></p>

                    <a href="view_match.php?item_id=<?= $item['id'] ?>" class="btn btn-sm btn-success">
                        <i class="bi bi-search"></i> View Matches
                    </a>

                    <a href="view_reports.php?delete_id=<?= $item['id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this report?')">
                        <i class="bi bi-trash"></i> Delete
                    </a>
                </div>

                <div class="report-img">
                    <?php if ($item['image'] && file_exists("../uploads/" . $item['image'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" alt="Item Image">
                    <?php else: ?>
                        <img src="../assets/no-image.png" alt="No Image">
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">You have not reported any items yet.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
