<?php

$id = $_POST['user_id'];
$email = $_POST['email'];
$password = $_POST['password'];
$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
$sql = "INSERT Into User Values (null, '$id', '$email', '$password', Now())";
$result = mysqli_query($conn, $sql);

if($result === false){
  echo '저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의하세요';
  error_log(mysqli_error($conn));
}
else{
  header("Location: ../login.php?login_id=$id");
}
?>
