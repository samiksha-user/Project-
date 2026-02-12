<?php
session_start();
include '../config.php';

/* ===============================
   Fetch Items (Latest First)
================================ */
$sql = "SELECT items.*, users.name
        FROM items 
        JOIN users ON items.user_id = users.id
        ORDER BY created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Browse Items | FindIt</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5 mb-5">

    <h2 class="mb-4 text-primary">Browse Lost & Found Items</h2>

    <div class="row g-4">

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">

                        <!-- Item Image -->
                        <?php if (!empty($row['image'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($row['image']) ?>"
                                 class="card-img-top"
                                 style="height:220px; object-fit:cover;">
                        <?php else: ?>
                            <img src="../assets/no-image.png"
                                 class="card-img-top"
                                 style="height:220px; object-fit:cover;">
                        <?php endif; ?>

                        <div class="card-body">

                            <!-- Status Badge -->
                            <span class="badge <?= $row['status'] === 'lost' ? 'bg-danger' : 'bg-success' ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>

                            <h5 class="card-title mt-2">
                                <?= htmlspecialchars($row['title']) ?>
                            </h5>

                            <p class="card-text text-muted">
                                <?= htmlspecialchars(substr($row['description'], 0, 90)) ?>...
                            </p>

                            <ul class="list-unstyled small">
                                <li><i class="bi bi-tag"></i> <?= htmlspecialchars($row['category']) ?></li>
                                <li><i class="bi bi-person"></i> <?= htmlspecialchars($row['title']) ?></li>
                                <li><i class="bi bi-clock"></i> <?= date("d M Y", strtotime($row['created_at'])) ?></li>
                            </ul>

                        </div>

                        

                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">No items found.</div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
