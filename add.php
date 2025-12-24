<?php 
require_once("./config.php");




if(isset($_GET["submit"])){
    $bt = trim($_GET['bt']);
$ba =trim( $_GET['ba']);
$bp = trim($_GET['bp']);
$bil = trim($_GET['bil']);
$dp = trim($_GET['dp']);
        $query = "INSERT INTO books (booktitle,bookauthor,bookprice,bookimagelocation,discountbookprice) VALUES('$bt','$ba','$bp','$bil','$dp')";
        if(mysqli_query($link, $query)){
            echo '<script>alert("added") </script>';
            header("location: add.php");
        }
        
      }
      
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  </head>
  <body>
    <form action="" method="get">
        <input type="text" name="bt" placeholder="enter your book title">
        <input type="text" name="ba" placeholder="book author">
        <input type="text" name="bp" placeholder="book price">
        <input type="text" name="bil" placeholder="book price location">
        <input type="text" name="dp" placeholder="discount price">
        <input type="submit" name="submit">
    </form>
  </body>
  </html>