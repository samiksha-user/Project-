<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Report Item</title>
  <link rel="stylesheet" href="report.css">
</head>
<body>

<?php require_once "../homepage/navbar.php"; ?>

<div class="report-container">
  <h2>Report Lost / Found Item</h2>

  <form action="report_item_action.php" method="POST" enctype="multipart/form-data">

    <!-- Report Type -->
    <select name="report_type" required>
      <option value="">Select Report Type</option>
      <option value="lost">Lost</option>
      <option value="found">Found</option>
    </select>

    <!-- Item Name -->
    <input type="text" name="item_name" placeholder="Item Name" required>

    <!-- Description -->
    <textarea name="description" placeholder="Description" required></textarea>

    <!-- Color -->
    <input type="text" name="color" placeholder="Color" required>

    <!-- Image -->
    <input type="file" name="image" accept="image/*">

    <button type="submit">Submit Report</button>
  </form>
</div>

</body>
</html>
