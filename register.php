<?php
require_once("./config.php");
session_start();
if(!empty($_SESSION["id"])){
    header("Location: index.php");
}
if(isset($_POST["submit"])){
  $username = trim($_POST["username"] ?? "");
  $email = trim($_POST["email"] ?? "");
  $password = $_POST["password"] ?? "";
  $confirmpassword = $_POST["cpassword"] ?? "";

  if($password !== $confirmpassword){
    $error = "Passwords do not match.";
  } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $error = "Please enter a valid email.";
  } else if($username === "" || $email === "" || $password === ""){
    $error = "All fields are required.";
  } else {
    $stmt = $link->prepare("SELECT id FROM login WHERE username = ? OR email = ? LIMIT 1");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
      $error = "Username or email already in use.";
    } else {
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);
      $insert = $link->prepare("INSERT INTO login (username, email, password) VALUES (?, ?, ?)");
      $insert->bind_param("sss", $username, $email, $passwordHash);
      if($insert->execute()){
        $success = "Registered successfully. Redirecting to sign in...";
        header("refresh:2; url=login.php");
      } else {
        $error = "Registration failed. Please try again.";
      }
      $insert->close();
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Book Store | Register</title>
    <?php include "./includes/header-scripts.php" ?>
    <?php include "./includes/navbar.php" ?>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row align-items-center g-4">
      <div class="col-12 col-lg-6 order-2 order-lg-1">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4 p-md-5">
            <h2 class="mb-1">Create your account</h2>
            <p class="text-muted mb-4">Join to start shopping your favorite books.</p>
            <?php if(!empty($error)){ ?>
              <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php } ?>
            <?php if(!empty($success)){ ?>
              <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($success); ?></div>
            <?php } ?>
            <form action="" method="post" class="needs-validation" novalidate>
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="johndoe" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter a strong password" required>
              </div>
              <div class="mb-4">
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" id="cpassword" name="cpassword" class="form-control" placeholder="Re-enter your password" required>
              </div>
              <div class="d-grid">
                <button type="submit" name="submit" class="btn btn-primary">Sign up</button>
              </div>
              <p class="mt-3 mb-0">Already have an account? <a href="login.php">Sign in</a></p>
            </form>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 order-1 order-lg-2">
        <img class="img-fluid rounded shadow-sm" src="./images/48153.jpg" alt="Register illustration">
      </div>
    </div>
  </div>
    <?php include "./includes/footer-scripts.php";?>
    <?php include "./includes/footer.php";?>
</body>
</html>