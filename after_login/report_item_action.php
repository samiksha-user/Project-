<?php
include "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $report_type = $_POST['report_type'];
  $item_name   = trim($_POST['item_name']);
  $description = trim($_POST['description']);
  $color       = trim($_POST['color']);

  // Image upload
  $image_name = NULL;

  if (!empty($_FILES['image']['name'])) {
    $image_name = time() . "_" . $_FILES['image']['name'];
    $upload_path = "../uploads/" . $image_name;
    move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
  }

  // Insert query
  $stmt = $conn->prepare(
    "INSERT INTO items (report_type, item_name, description, color, image)
     VALUES (?, ?, ?, ?, ?)"
  );

  $stmt->bind_param(
    "sssss",
    $report_type,
    $item_name,
    $description,
    $color,
    $image_name
  );

  if ($stmt->execute()) {
    header("Location: report_item.php?success=1");
    exit;
  } else {
    echo "Error saving report.";
  }
}
