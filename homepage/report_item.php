<?php
session_start();
include '../config.php';


//    Allow ONLY logged-in users

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$success = "";
$error = "";


//    Handle Form Submission

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = $_POST['category'] ?? '';
    $status = $_POST['status'] ?? '';
    $image_name = NULL;

    // Validation
    if (empty($title) || empty($description) || empty($category) || empty($status)) {
        $error = "All fields except image are required.";
    } else {

    
        //    Image Upload (Optional)
        
        if (!empty($_FILES['image']['name'])) {

            $upload_dir = "../uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_name = time() . "_" . basename($_FILES["image"]["name"]);
            $target_file = $upload_dir . $image_name;

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $error = "Failed to upload image.";
            }
        }

        
        //    Insert into Database
        
        if (empty($error)) {

            $stmt = $conn->prepare(
                "INSERT INTO items 
                (user_id, title, description, category, status, image, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())"
            );

            $stmt->bind_param(
                "isssss",
                $user_id,
                $title,
                $description,
                $category,
                $status,
                $image_name
            );

            if ($stmt->execute()) {
                $success = "Item reported successfully!";
            } else {
                $error = "Database error. Please try again.";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Report Item | FindIt</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg p-4">

        <h3 class="mb-4 text-primary">Report Lost / Found Item</h3>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Item Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    <option value="Documents">Documents</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="">Select Status</option>
                    <option value="lost">Lost</option>
                    <option value="found">Found</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Item Image (Optional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">
                Submit Report
            </button>

        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
