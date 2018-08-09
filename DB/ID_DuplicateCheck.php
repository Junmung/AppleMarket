<?php

$User_ID = $_POST['user_id'];
$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
$sql = "SELECT _id From User Where ID='$User_ID'";

$result = mysqli_query($conn, $sql);

if(mysqli_fetch_array($result) == null){
  echo "New";
}
else{
  echo "Duplicate";
}
?>
