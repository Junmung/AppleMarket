<?php

$comment_index = $_POST['comment_index'];
$product_index = $_POST['product_index'];

$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
$sql = "SELECT hasChild From Comment Where _id=$comment_index";
$sqlResult = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($sqlResult);

if($row['hasChild'] == 1){
  $sql = "UPDATE Comment SET Content='--- 삭제된 댓글입니다 ---' Where _id=$comment_index";
  mysqli_query($conn, $sql);
  $result['response'] = "Modify";
}
else{
  $sql = "DELETE FROM Comment Where _id=$comment_index";
  mysqli_query($conn, $sql);
  $result['response'] = "Delete";
}

$sql = "UPDATE Product Set Comment_Count = Comment_Count - 1 Where _id=$product_index";
mysqli_query($conn, $sql);

echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

?>
