<?php
include '../config.php';


$error = "";

// Run only when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? ''); // Phone is optional since DB doesn't have it
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Server-side validation
    if (
        empty($name) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if ($check === false) {
            $error = "Database error: " . $conn->error;
        } else {
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "Email already registered.";
            } else {

                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user (note: phone field doesn't exist in DB, so we only insert name, email, password)
                $insert = $conn->prepare(
                    "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
                );
                if ($insert === false) {
                    $error = "Database error: " . $conn->error;
                } else {
                    $insert->bind_param(
                        "sss",
                        $name,
                        $email,
                        $hashed_password
                    );

                    if ($insert->execute()) {
                        // Redirect after successful signup
                        header("Location: ../login/login.php");
                        exit();
                    } else {
                        $error = "Something went wrong. Please try again.";
                    }
                    $insert->close();
                }
            }
            $check->close();
        }
    }
}

// Don't close connection here - it might be needed for error display
// $conn->close();
?>

<!-- Error Message -->
<?php if (!empty($error)) : ?>
    <p style="color:red; text-align:center;">
        <?php echo $error; ?>
    </p>
<?php endif; ?>
