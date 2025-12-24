<?php 
require_once('./config.php');
session_start();
if (empty($_SESSION["id"])) {
  header("Location: login.php");
}
$name = $_SESSION['username'];

$_SESSION['username'] = $name;

$uid = $_SESSION['id'];

// Ensure cart arrays exist
if(empty($_SESSION['cart'])){ $_SESSION['cart'] = array(); }
if(empty($_SESSION['dbp'])){ $_SESSION['dbp'] = array(); }

// Quantity increment
if(isset($_GET['inc']) && isset($_GET['id']) && isset($_GET['price'])){
  $bid = (int)$_GET['id'];
  $bprice = (int)$_GET['price'];
  array_push($_SESSION['cart'], $bid);
  array_push($_SESSION['dbp'], $bprice);
  header("Location: cart.php");
  exit;
}

// Quantity decrement
if(isset($_GET['dec']) && isset($_GET['id']) && isset($_GET['price'])){
  $bid = (int)$_GET['id'];
  $bprice = (int)$_GET['price'];
  $idx = array_search($bid, $_SESSION['cart']);
  if($idx !== false){
    unset($_SESSION['cart'][$idx]);
    $pidx = array_search($bprice, $_SESSION['dbp']);
    if($pidx !== false){ unset($_SESSION['dbp'][$pidx]); }
  }
  header("Location: cart.php");
  exit;
}



$cartitems = implode(',',array_unique($_SESSION['cart']));

$_SESSION['carts'] = $cartitems;


$books = trim($cartitems,',');

$bookprice = implode(',',array_unique($_SESSION['dbp']));




if(isset($_GET['checkout'])){
  header("location: checkout.php");
}
// $cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <?php include "./includes/header-scripts.php"; ?>
  <?php include "./includes/navbar.php"; ?>
</head>
<body>
    
<div class="container">
    <div class="row">
    <?php
    if(!empty($_SESSION['cart'])){
      // quantity per book id
      $qtyMap = array_count_values($_SESSION['cart']);
      $sql = "SELECT * FROM books WHERE id in ($books)";
      if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
          $i = 1;
          $grandTotal = 0;
          while ($row = mysqli_fetch_array($result)) {
            $qty = isset($qtyMap[$row['id']]) ? (int)$qtyMap[$row['id']] : 1;
            $unit = (int)$row['discountbookprice'];
            $subtotal = $unit * $qty;
            $grandTotal += $subtotal;
      ?>
            <div class="col-sm-12 col-md-6 col-lg-4 my-4">
              <div class="card shadow-sm" style="width: 100%;">
                <img src="./images/<?php echo $row['bookimagelocation'];?>" class="card-img-top img-fluid" alt="Books">
                <div class="card-body">
                  <h5 class="card-title mb-1"><?php echo $row['booktitle'];?> </h5>
                  <p class="card-text small">By <?php echo $row['bookauthor'];?></p>
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                      <div class="text-success fw-semibold">&#8377; <?php echo $unit;?><span class="text-muted small"> x <?php echo $qty;?></span></div>
                      <div class="small text-muted">Subtotal: &#8377; <?php echo $subtotal;?></div>
                    </div>
                    <div class="btn-group" role="group">
                      <a href="cart.php?dec=1&id=<?php echo $row['id'];?>&price=<?php echo $unit; ?>" class="btn btn-outline-secondary btn-sm">-</a>
                      <span class="btn btn-light btn-sm disabled"><?php echo $qty;?></span>
                      <a href="cart.php?inc=1&id=<?php echo $row['id'];?>&price=<?php echo $unit; ?>" class="btn btn-outline-secondary btn-sm">+</a>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between">
                    <form action="removecart.php?valid=200" method="get">
                      <input type="text" name="book" value="<?php echo $row['id'];?>" style="display:none" >
                      <input type="text" name="bookprice" value="<?php echo $unit;?>" style="display:none">
                      <button type="submit"  class="btn btn-outline-danger btn-sm" name="remove">Remove</button>
                    </form>
                    <!-- <a href="singleproduct.php?price=<?php echo $unit;?>&valid=200" class="btn btn-outline-success btn-sm">Buy Now</a> -->
                  </div>
                </div>
              </div>
            </div>
      <?php $i++;
          }
          mysqli_free_result($result);
        } else {
          echo '<p class="my-5"><b>No Books</b> </p>';
        }
      }?> 

      <div class="d-flex justify-content-center align-items-center gap-3 my-3">
        <div class="fw-semibold">Total: &#8377; <?php echo isset($grandTotal) ? $grandTotal : 0; ?></div>
       <form class="text-center mx-5" action="checkout.php?" method="get">
        <input type="text" name="valid" value="200" hidden>
        <button class="btn btn-success" type="submit" name="checkout">Checkout</button>
     
    </form>
       <form class="text-center" action="removecart.php" method="get">
       <input type="text" name="valid" value="200" hidden>
      <button class="btn btn-danger" href="removecart.php" type="sumit" name="clearcart" value="clear">Empty Cart</button>
    </form>
      </div>

    <?php 
    $_SESSION['totalprice'] = isset($grandTotal) ? $grandTotal : 0;
    ?>
    
   <?php }else {
        echo "No Items in cart";
      } ?>
      
    </div>
</div>


   








<?php include "./includes/footer-scripts.php"; ?>
</body>
</html>