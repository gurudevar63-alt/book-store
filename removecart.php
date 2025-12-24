<?php 
session_start();
if(empty($_GET['valid'])){
    header("location:index.php");
}
    if(isset($_GET['clearcart'])){
    unset($_SESSION['cart']);
    unset($_SESSION['dbp']);
    header("Location: index.php");
    }

    if(isset($_GET['remove'])){
        $book = $_GET['book'];
        $books = array_search($book,$_SESSION['cart']);
        $bookprice = $_GET['bookprice'];
        $bookprices = array_search($bookprice,$_SESSION['dbp']);
        unset($_SESSION['cart'][$books]);
        unset($_SESSION['dbp'][$bookprices]);
        header("location: cart.php");
    }

    if(isset($_GET['checkout'])){
        unset($_SESSION['cart']);
        unset($_SESSION['dbp']);
       header("location:success.php");
    }
?>

