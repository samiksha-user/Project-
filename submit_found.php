<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $status = "Returned";

    $image_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $folder = "uploads/" . $image_name;
    move_uploaded_file($tmp_name, $folder);

    $sql = "INSERT INTO items (item_name, description, location, image, status, type)
            VALUES ('$item_name', '$description', '$location', '$folder', '$status', 'Found')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Found item reported successfully!'); window.location='browse_items.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
