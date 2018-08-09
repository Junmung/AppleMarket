<?php

$id = $_POST['login_id'];
$password = $_POST['password'];
$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
$sql = "SELECT * From User Where ID='$id' AND Password='$password'";

$result = mysqli_query($conn, $sql);

if(mysqli_fetch_array($result) == null){
  header('Location: ../login.php?login_check=fail');
}
else{
  session_start();
  $_SESSION['user_id'] = $id;
  header("Location: ../home.php?login_id=$id");
}
?>
