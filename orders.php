<?php
require_once('./config.php');
session_start();
if (empty($_SESSION["id"])) {
  header("Location: login.php");
}
$uid = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders</title>
  <?php include "./includes/header-scripts.php"; ?>
  <?php include "./includes/navbar.php"; ?>
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">My Orders</h2>
    <?php
      $ordersStmt = $link->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE user_id = ? ORDER BY id DESC");
      $ordersStmt->bind_param("i", $uid);
      $ordersStmt->execute();
      $ordersResult = $ordersStmt->get_result();
      if($ordersResult->num_rows === 0){
        echo '<div class="alert alert-info">No orders yet.</div>';
      }
      while($order = $ordersResult->fetch_assoc()){
        $orderId = (int)$order['id'];
    ?>
      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h5 class="mb-0">Order #<?php echo $orderId;?></h5>
              <small class="text-muted">Status: <?php echo htmlspecialchars($order['status']);?> · Placed on <?php echo htmlspecialchars($order['created_at']);?></small>
            </div>
            <div class="text-end">
              <span class="h5 mb-0">&#8377; <?php echo htmlspecialchars((string)$order['total_amount']);?></span>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-sm align-middle">
              <thead>
                <tr>
                  <th>Book</th>
                  <th class="text-center" style="width: 100px;">Qty</th>
                  <th class="text-end" style="width: 140px;">Price</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $itemsStmt = $link->prepare(
                    "SELECT oi.book_id, oi.price, oi.quantity, b.booktitle, b.bookauthor, b.bookimagelocation
                     FROM order_items oi
                     JOIN books b ON b.id = oi.book_id
                     WHERE oi.order_id = ?"
                  );
                  $itemsStmt->bind_param("i", $orderId);
                  $itemsStmt->execute();
                  $itemsResult = $itemsStmt->get_result();
                  while($item = $itemsResult->fetch_assoc()){
                ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="./images/<?php echo htmlspecialchars($item['bookimagelocation']);?>" alt="" class="rounded me-2" style="width:48px;height:48px;object-fit:cover;">
                        <div>
                          <div class="fw-semibold"><?php echo htmlspecialchars($item['booktitle']);?></div>
                          <small class="text-muted">by <?php echo htmlspecialchars($item['bookauthor']);?></small>
                        </div>
                      </div>
                    </td>
                    <td class="text-center"><?php echo (int)$item['quantity'];?></td>
                    <td class="text-end">&#8377; <?php echo htmlspecialchars((string)$item['price']);?></td>
                  </tr>
                <?php }
                  $itemsStmt->close();
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php }
      $ordersStmt->close();
    ?>
  </div>
  <?php include "./includes/footer-scripts.php"; ?>
  <?php include "./includes/footer.php"; ?>
</body>
</html>


