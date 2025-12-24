<?php
require_once('./config.php');
session_start();
if (empty($_SESSION["id"])) {
  header("Location: login.php");
}
$name = $_SESSION['username'];

$_SESSION['username'] = $name;

$uid = $_SESSION['id'];

$price = $_GET['price'];

if(empty($_GET['valid'])){
  header("location:index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <?php include "./includes/header-scripts.php"; ?>
  <?php include "./includes/navbar.php"; ?>
</head>
<body>
  

<div class="container my-5">
  <div class="row d-flex justify-content-center">
  <div class="card" style="width: 20rem;">
  <div class="card-body">
    <h5 class="card-title">Check Out</h5>
    <h3>Price : &#8377; <?php echo $price;?></h3>
    <form action="removecart.php" method="get">
      <input type="text" class="form-control my-2" placeholder="Name" required>
      <input type="text" class="form-control my-2" placeholder="Phone number" required>
      <input type="email" class="form-control my-2" placeholder="Email" required>
      <textarea type="text" class="form-control my-2" cols="30" rows="2" placeholder="Address" required></textarea>
    <button type="submit" class="btn btn-primary" name="checkout">Place Order</button>
    </form>
  </div>
</div>
  </div>
</div>



<?php include "./includes/footer-scripts.php"; ?>
</body>
</html>