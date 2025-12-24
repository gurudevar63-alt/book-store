<?php
require_once('./config.php');
session_start();
if (empty($_SESSION["id"])) {
  header("Location: login.php");
}
$name = $_SESSION['username'];

$_SESSION['username'] = $name;

$uid = $_SESSION['id'];

if(!isset($_GET['cart'])){
  if(empty($_SESSION['cart'])){
    $_SESSION['cart']=array();
  }
}

if(!isset($_GET['dbp'])){
  if(empty($_SESSION['dbp'])){
    $_SESSION['dbp'] = array();
  }
}

if(isset($_GET['search'])){
    $search = $_GET['searchword'];
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Online Book Store</title>
  <?php include "./includes/header-scripts.php"; ?>
  <?php include "./includes/navbar.php"; ?>
</head>

<body>

  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <h3 class="text-center my-3 text-success">Search Results</h3>
      </div>
    </div>
  </div>


  <div class="container">
    <div class="row">
      <?php
    
      $sql = "SELECT * FROM books where booktitle like '%$search%' OR bookauthor like '%$search%'";
      if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
          $i = 1;
          while ($row = mysqli_fetch_array($result)) {
      ?>
            <div class="col-sm-12 col-md-4 my-5">
              <div class="card" style="width: 20rem;">
                <img src="<?php echo $row['bookimagelocation'];?>" class="card-img-top" alt="Books">
                <div class="card-body">
                  <h5 class="card-title"><?php echo $row['booktitle'];?> </h5>
                  <p class="card-text">By <?php echo $row['bookauthor'];?></p>
                  <h4 class="text-success">&#8377; <?php echo $row['discountbookprice'];?></h4>
                  <p class="text-danger text-decoration-line-through">&#8377; <?php echo $row['bookprice'];?></p>
                  <div class="d-flex justify-content-evenly">
                    <form action="" method="get">
                    <a href="singleproduct.php?price=<?php echo $row['discountbookprice'];?>&valid=200" class="btn btn-outline-success">Buy Now</a>
                    <a type="submit" href="addtocart.php?id=<?php echo $row['id'];?>&dbp=<?php echo $row['discountbookprice'];?>&valid=200" class="btn btn-outline-primary" name="cart">Add to cart</a>
                    </form>
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
      } else {
        echo "invalid query";
      } ?>



    </div>
  </div>





  <?php include "./includes/footer-scripts.php"; ?>
</body>

</html>