<?php
require_once('./config.php');
session_start();
if (empty($_SESSION["id"])) {
  header("Location: login.php");
}
$name = $_SESSION['username'];

$_SESSION['username'] = $name;

$uid = $_SESSION['id'];

if(empty($_GET['valid'])){
  header("location:index.php");
}

// Calculate total from session cart prices
$totalPrice = 0;
$cartItems = [];
if(!empty($_SESSION['cart']) && !empty($_SESSION['dbp'])){
  // Aggregate by book id to compute quantities
  $aggregated = [];
  for($i=0; $i<count($_SESSION['cart']); $i++){
    $bookId = (int)$_SESSION['cart'][$i];
    $price = (int)$_SESSION['dbp'][$i];
    if(!isset($aggregated[$bookId])){
      $aggregated[$bookId] = ['qty' => 0, 'price' => $price];
    }
    $aggregated[$bookId]['qty'] += 1;
    $totalPrice += $price;
  }
  // Fetch book details for each unique id
  foreach($aggregated as $bookId => $meta){
    $bq = $link->prepare("SELECT id, booktitle, bookauthor, bookimagelocation FROM books WHERE id = ? LIMIT 1");
    $bq->bind_param("i", $bookId);
    $bq->execute();
    $res = $bq->get_result();
    if($row = $res->fetch_assoc()){
      $cartItems[] = [
        'id' => $row['id'],
        'title' => $row['booktitle'],
        'author' => $row['bookauthor'],
        'image' => $row['bookimagelocation'],
        'price' => (int)$meta['price'], // unit price
        'qty' => (int)$meta['qty']
      ];
    }
    $bq->close();
  }
}

// Handle placing order
if(isset($_POST['place_order'])){
  $custName = trim($_POST['cust_name'] ?? '');
  $custPhone = trim($_POST['cust_phone'] ?? '');
  $custEmail = trim($_POST['cust_email'] ?? '');
  $custAddress = trim($_POST['cust_address'] ?? '');

  if(!empty($_SESSION['cart']) && !empty($_SESSION['dbp'])){
    $link->begin_transaction();
    try{
      $orderStmt = $link->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'PLACED')");
      $orderStmt->bind_param("id", $uid, $totalPrice);
      $orderStmt->execute();
      $orderId = $orderStmt->insert_id;
      $orderStmt->close();

      // Insert items
      // Prepare grouped items from current $cartItems (already aggregated)
      $itemStmt = $link->prepare("INSERT INTO order_items (order_id, book_id, price, quantity) VALUES (?, ?, ?, ?)");
      foreach($cartItems as $it){
        $bookId = (int)$it['id'];
        $price = (int)$it['price'];
        $qty = (int)$it['qty'];
        $itemStmt->bind_param("iidi", $orderId, $bookId, $price, $qty);
        $itemStmt->execute();
      }
      $itemStmt->close();

      $link->commit();

      // clear cart
      unset($_SESSION['cart']);
      unset($_SESSION['dbp']);
      header("Location: success.php");
      exit;
    } catch (Exception $e){
      $link->rollback();
      $error = "Failed to place order. Please try again.";
    }
  } else {
    $error = "Your cart is empty.";
  }
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
<body class="bg-soft-gradient position-relative" style="min-height:100vh;">
  <div class="floating-bubbles"></div>
  

<div class="container my-5">
  <div class="row checkout-hero">
    <div class="col-12 col-lg-7 mb-4">
      <div class="card glass-card border-0 card-elevated h-100">
        <div class="card-body p-4 p-md-5">
          <h4 class="mb-4">Shipping details</h4>
          <?php if(!empty($error)){ ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
          <?php } ?>
          <form action="" method="post">
            <div class="row g-3">
              <div class="col-12">
                <input type="text" name="cust_name" class="form-control glass-input" placeholder="Full name" required>
              </div>
              <div class="col-12 col-md-6">
                <input type="text" name="cust_phone" class="form-control glass-input" placeholder="Phone number" required>
              </div>
              <div class="col-12 col-md-6">
                <input type="email" name="cust_email" class="form-control glass-input" placeholder="Email address" required>
              </div>
              <div class="col-12">
                <textarea name="cust_address" class="form-control glass-input" rows="4" placeholder="Shipping address" required></textarea>
              </div>
              <div class="col-12 d-grid d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-glass-primary px-4 btn-primary" name="place_order">Place Order</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-5">
      <div class="card glass-card border-0 card-elevated">
        <div class="card-body p-4 p-md-5">
          <h4 class="mb-3">Order summary</h4>
          <?php if(empty($cartItems)){ ?>
            <div class="alert alert-warning">Your cart is empty.</div>
          <?php } else { ?>
            <div class="row g-3 mb-3">
              <?php foreach($cartItems as $it){ ?>
                <div class="col-6 col-md-6 col-lg-6">
                  <div class="summary-card h-100">
                    <img src="./images/<?php echo htmlspecialchars($it['image']);?>" class="summary-cover mb-2 img-fluid" alt="">
                    <div class="summary-title text-truncate-2 mb-1"><?php echo htmlspecialchars($it['title']);?></div>
                    <div class="text-muted-small mb-1">by <?php echo htmlspecialchars($it['author']);?></div>
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="text-muted-small">Qty: <?php echo (int)$it['qty'];?></span>
                      <span class="summary-price">&#8377; <?php echo htmlspecialchars((string)$it['price']);?></span>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
            <div class="d-flex justify-content-between pt-2 border-top">
              <div class="fw-semibold">Total</div>
              <div class="h5 mb-0">&#8377; <?php echo htmlspecialchars((string)$totalPrice);?></div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>



<?php include "./includes/footer-scripts.php"; ?>
</body>
</html>