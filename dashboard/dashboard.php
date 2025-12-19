 
 <?php
 session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit;
}

  include'recent_activities.php' ?>

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
        <h2 id="totalReports">0</h2>
      </div>
      <div class="card" data-page="lost">
        <p>Lost Items</p>
        <h2 id="lostCount">0</h2>
      </div>
      <div class="card" data-page="found">
        <p>Found Items</p>
        <h2 id="foundCount">0</h2>
      </div>
      <div class="card" data-page="match">
        <p>Potential Matches</p>
        <h2 id="matchCount">0</h2>
      </div>
    </div>



    
    <!-- RECENT ACTIVITIES -->
      
    <div class="recent-activities">
      <h2>Recent Activities</h2>
      <ul>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <li>
              <span>
                <?php echo htmlspecialchars($row['report_type']); ?> item reported:
                <strong><?php echo htmlspecialchars($row['item_name']); ?></strong>
              </span>
              <span class="time">
                <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?>
              </span>
            </li>
          <?php endwhile; ?>
        <?php else: ?>
          <li>No recent activities found</li>
        <?php endif; ?>
      </ul>
    </div>

    
 






    <!-- DYNAMIC CONTENT -->
    <div id="content"></div>
  </main>
  


  
  

</div>

<script src="aa.js"></script>
</body>
</html>
