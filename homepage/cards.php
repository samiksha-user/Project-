<?php
include '../config.php';

// Fetch items from database
$sql = "SELECT * FROM items ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Homepage | Lost & Found Items</title>
  
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card img {
      height: 200px;
      object-fit: cover;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
      transition: 0.3s;
    }
    .card-body p {
      min-height: 50px;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="mb-4 fw-bold text-center">Recently Lost & Found Items</h2>
    
    <div class="row g-4">
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100">
              <img src="uploads/<?php echo $row['image'] ?: 'default.png'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['item_name']); ?>">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo htmlspecialchars($row['item_name']); ?></h5>
                <p class="card-text">
                  <?php 
                  $desc = htmlspecialchars($row['description']);
                  echo strlen($desc) > 100 ? substr($desc,0,100).'...' : $desc;
                  ?>
                </p>
                <p class="mb-1"><strong>Category:</strong> <?php echo htmlspecialchars($row['report_type']); ?></p>
                <?php if ($row['color']): ?>
                  <p class="mb-1"><strong>Color:</strong> <?php echo htmlspecialchars($row['color']); ?></p>
                <?php endif; ?>
                <small class="text-muted mt-auto"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">No items found.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
