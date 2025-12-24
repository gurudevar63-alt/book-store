<?php
session_start();
if(isset($_GET['valid']) && $_GET['valid'] == 200){
    if(empty($_SESSION['cart'])){
      $_SESSION['cart']=array();
    }
    array_push($_SESSION['cart'],$_GET['id']);
    if(empty($_SESSION['dbp'])){
      $_SESSION['dbp']=array();
    }
    array_push($_SESSION['dbp'],$_GET['dbp']);
header("location:index.php");
  }else{
    header("location:index.php");
  }
  ?>