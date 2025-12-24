<?php 
require_once("./config.php");
session_start();
if(!empty($_SESSION["id"])){
  header("Location: index.php");
}

$loginError = "";
if(isset($_POST["submit"])){
  $usernameOrEmail = trim($_POST["username"] ?? "");
  $password = $_POST["password"] ?? "";

  if($usernameOrEmail === "" || $password === ""){
    $loginError = "Please enter username/email and password.";
  } else {
    $stmt = $link->prepare("SELECT id, username, password FROM login WHERE username = ? OR email = ? LIMIT 1");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()){
      if(password_verify($password, $row['password'])){
        $_SESSION["login"] = true;
        $_SESSION["id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        header("Location: index.php");
        exit;
      } else {
        $loginError = "Invalid credentials.";
      }
    } else {
      $loginError = "Account not found.";
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
    <title>Online Book Store | Login</title>
    <?php include "./includes/header-scripts.php" ?>
    <?php include "./includes/navbar.php" ?>
</head>

<body class="bg-light">
  <div class="container py-5">
    <div class="row align-items-center g-4">
      <div class="col-12 col-lg-6 order-2 order-lg-1">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4 p-md-5">
            <h2 class="mb-1">Welcome back</h2>
            <p class="text-muted mb-4">Sign in to continue.</p>
            <?php if(!empty($loginError)){ ?>
              <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($loginError); ?></div>
            <?php } ?>
            <form action="" method="post">
              <div class="mb-3">
                <label for="username" class="form-label">Username or Email</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="you@example.com" required>
              </div>
              <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Your password" required>
              </div>
              <div class="d-grid">
                <button type="submit" name="submit" class="btn btn-primary">Sign in</button>
              </div>
              <p class="mt-3 mb-0">Don't have an account? <a href="register.php">Create one</a></p>
            </form>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 order-1 order-lg-2">
        <img class="img-fluid rounded shadow-sm" src="./images/48153.jpg" alt="Login illustration">
      </div>
    </div>
  </div>

    <?php include "./includes/footer-scripts.php";?>
    <?php include "./includes/footer.php";?>
</body>


</html>