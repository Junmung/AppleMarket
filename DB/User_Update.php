<?php
function isCollectPwd($id, $pwd){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "Select _id From User Where ID='$id' AND Password='$pwd'";
  $result = mysqli_query($conn, $sql);

  if(mysqli_num_rows($result) == 0){
      return false;
  }
  else{
      return true;
  }
}
$id = $_POST['user'];
$email = $_POST['email'];
$currentPwd = $_POST['current_pwd'];
$newPassword = $_POST['password'];

if(isCollectPwd($id, $currentPwd)){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "UPDATE User SET Email='$email', Password='$newPassword' Where ID='$id'";
  $result = mysqli_query($conn, $sql);
  header("Location: ../mypage.php");
}
else{
  header("Location: ../mypage.php?password_check=fail&categoryItem=myInfo");
}

?>
