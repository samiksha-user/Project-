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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Signup</title>

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />

  <!-- Notyf -->
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css"/>

  <link rel="stylesheet" href="signup.css" />
</head>
<body>




<div class="container">
  <h1>Create Account</h1>

  <form id="Form" action="signup.php" method="POST">

    <div class="input-name">
      <i class="fa fa-user"></i>
      <input type="text" name="name" id="name" class="text-name"
        placeholder="Full Name" required />
      <small></small>
    </div>

    <div class="input-name">
      <i class="fa fa-envelope"></i>
      <input type="email" name="email" id="email" class="text-name"
        placeholder="Email Address" required />
      <small></small>
    </div>

    <div class="input-name">
      <i class="fa fa-phone"></i>
      <input type="tel" name="phone" id="phone" class="text-name"
        placeholder="Phone Number" required />
      <small></small>
    </div>

    <div class="input-name">
      <i class="fa fa-lock"></i>
      <input type="password" name="password" id="password"
        class="text-name" placeholder="Password" required />
      <small></small>
    </div>

    <div class="input-name">
      <i class="fa fa-lock"></i>
      <input type="password" name="confirm_password" id="cpassword"
        class="text-name" placeholder="Confirm Password" required />
      <small></small>
    </div>

    <input type="submit" value="Sign Up" />

    <p class="extra-text">
      Already have an account?
      <a href="../login/login.php">Login</a>
    </p>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
<script src="./signup.js"></script>
</body>
</html>

<!-- Error Message -->
<?php if (!empty($error)) : ?>
    <p style="color:red; text-align:center;">
        <?php echo $error; ?>
    </p>
<?php endif; ?>
