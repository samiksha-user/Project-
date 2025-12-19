<?php
include '../config.php';


$error = "";

// Run only when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Server-side validation
    if (
        empty($fullname) ||
        empty($email) ||
        empty($phone) ||
        empty($password) ||
        empty($confirm_password)
    ) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {

        // Check if email already exists
        $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already registered.";
        } else {

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $insert = $conn->prepare(
                "INSERT INTO users (fullname, email, phone, password) VALUES (?, ?, ?, ?)"
            );
            $insert->bind_param(
                "ssss",
                $fullname,
                $email,
                $phone,
                $hashed_password
            );

            if ($insert->execute()) {
                // Redirect after successful signup
                header("Location: ../login/login.php");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

$conn->close();
?>

<!-- Error Message -->
<?php if (!empty($error)) : ?>
    <p style="color:red; text-align:center;">
        <?php echo $error; ?>
    </p>
<?php endif; ?>
