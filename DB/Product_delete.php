<?php
$product_index = $_POST['product_index'];
$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");

// 서버에서 이미지 파일삭제
$sql = "SELECT FileName From Image Where Product_Index=$product_index";
$result = mysqli_query($conn, $sql);
while($rows = mysqli_fetch_array($result)){
  $fileName = $rows['FileName'];
  unlink("./uploadImgs/".$fileName);
}


// 사진 삭제
$sql = "DELETE From Image Where Product_Index=$product_index";
mysqli_query($conn, $sql);

// 댓글 삭제
$sql = "DELETE From Comment Where Product_Index=$product_index";
mysqli_query($conn, $sql);

// 대댓글 삭제
$sql = "DELETE From ChildComment Where Product_Index=$product_index";
mysqli_query($conn, $sql);

// Product 삭제
$sql = "DELETE From Product Where _id=$product_index";
$result = mysqli_query($conn, $sql);



?>
