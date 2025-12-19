<?php
session_start();
include '../config.php'; // Make sure your database connection is correct

$email = $password = "";
$email_err = $password_err = $login_err = "";

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Get POST data
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    // Validate empty fields
    if(empty($email)){
        $email_err = "Email cannot be blank";
    }

    if(empty($password)){
        $password_err = "Password cannot be blank";
    }

    // If no client-side errors, check database
    if(empty($email_err) && empty($password_err)){
        $sql = "SELECT user_id, email, fullname, password, role FROM users WHERE email='$email' LIMIT 1";
$result = mysqli_query($conn, $sql);

if(!$result || mysqli_num_rows($result) === 0){
    $login_err = "Email not found";
} else {
    $user = mysqli_fetch_assoc($result);

    if(!password_verify($password, $user['password'])){
        $login_err = "Password incorrect";
    } else {
        $_SESSION['user_id']  = $user['user_id'];
        $_SESSION['email']    = $user['email'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role']     = $user['role'];

        if($user['role'] === 'admin'){
            header("Location: ../dashboard/dashboard.php");
        } else {
            header("Location: ../homepage/homepage.php");
        }
        exit;
    }
}

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Login</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
  />
 <link rel="stylesheet" href="login.css" />
 
<body>
  
  <div class="container">
    <h1>LOGIN</h1>
    <form id="LoginForm" action="" method="POST">

      <div class="input-name <?php echo (!empty($email_err) ? 'error' : '') ?>">
        <i class="fa fa-envelope"></i>
        <input type="email" name="email" class="text-name" placeholder="Email Address"
               value="<?php echo htmlspecialchars($email); ?>" required>
        <small><?php echo $email_err; ?></small>
      </div>

      <div class="input-name <?php echo (!empty($password_err) || !empty($login_err) ? 'error' : '') ?>">
        <i class="fa fa-lock"></i>
        <input type="password" name="password" class="text-name" placeholder="Password" required>
        <small>
          <?php 
          echo $password_err; 
          if(!empty($login_err)) echo $login_err; 
          ?>
        </small>
      </div>

      <div class="input-name">
        <input type="submit" value="Login" id="loginBtn">
      </div>

      <p class="extra-text">
        Don’t have an account?
        <a href="../signup/signup.html"> Sign Up</a>
      </p>

    </form>
  </div>

  <script>
    // Client-side validation
    const form = document.getElementById("LoginForm");

    form.addEventListener("submit", function(e) {
      let email = form.querySelector("input[name='email']");
      let password = form.querySelector("input[name='password']");
      let valid = true;

      // Reset errors
      email.parentElement.classList.remove('error');
      password.parentElement.classList.remove('error');

      // Email validation
      if(email.value.trim() === ""){
        setError(email, "Email cannot be blank");
        valid = false;
      } else if(!isEmail(email.value.trim())){
        setError(email, "Enter a valid email");
        valid = false;
      } else {
        setSuccess(email);
      }

      // Password validation
      if(password.value.trim() === ""){
        setError(password, "Password cannot be blank");
        valid = false;
      } else if(password.value.length < 6){
        setError(password, "Password must be at least 6 characters");
        valid = false;
      } else {
        setSuccess(password);
      }

      if(!valid) e.preventDefault();
    });

    function isEmail(email){
      let at = email.indexOf("@");
      let dot = email.lastIndexOf(".");
      return at > 0 && dot > at + 2 && dot < email.length - 1;
    }

    function setError(input, message){
      const parent = input.parentElement;
      let small = parent.querySelector("small");
      parent.classList.add('error');
      parent.classList.remove('success');
      if(!small){
        small = document.createElement('small');
        parent.appendChild(small);
      }
      small.innerText = message;
      small.style.display = "block";
    }

    function setSuccess(input){
      const parent = input.parentElement;
      const small = parent.querySelector("small");
      parent.classList.add('success');
      parent.classList.remove('error');
      if(small) small.style.display = "none";
    }
  </script>
</body>
</html>
