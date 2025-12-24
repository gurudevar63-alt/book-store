<nav class="navbar navbar-expand-lg navbar-light bg-info">
  <div class="container-fluid">
    <a class="navbar-brand ms-3" href="index.php">Vkart</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
      <ul class="navbar-nav pe-5">
        <li class="nav-item">
         <b> <a class="nav-link " href="index.php">Home</a></b>
        </li>
        <li class="nav-item">
          <?php 
          if(!empty($_SESSION["username"])){
            echo '<a class="nav-link" href="orders.php">Orders</a>';
          }
          ?>
        </li>
        <li class="nav-item">
         <?php 
         if(!empty($_SESSION["id"])){
          echo '<a class="nav-link" href="logout.php">Logout</a>';
        }else if(empty($_SESSION['id'])){
          echo '<a class="nav-link" href="login.php">Login</a>';
        };?>
        </li>
        <li class="nav-item">
          <?php 
          if(!empty($_SESSION["username"])){
            // echo '<a class="nav-link text-dark">'.$_SESSION["username"].'</a>';
          }else if(empty($_SESSION["username"])){

          echo '<a class="nav-link" href="register.php">Register</a>';
          }
          ?>
        </li>
        <li class="nav-item">
          <?php 
          if(!empty($_SESSION["username"])){
            echo '<a class="nav-link position-relative" href="cart.php">Cart <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
    '.count($_SESSION["cart"]).'
    <span class="visually-hidden">unread messages</span>
  </span></a>';
          }else if(empty($_SESSION["username"])){
            
          };
          ?>
        </li>
      </ul>
      <form class="d-flex" action="search.php" method="get">
        <input class="form-control me-2" type="search" name="searchword" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit" name="search">Search</button>
      </form>
      <ul class="navbar-nav">
        <?php if(!empty($_SESSION["username"])){
          echo '<li class="nav-item">
          <a class="nav-link text-success">Welcome '.$_SESSION["username"].'</a>
          </li>';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>