
<?php

function deleteProducts($product_index, $conn){
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

  // Product 삭제
  $sql = "DELETE From Product Where _id=$product_index";
  $result = mysqli_query($conn, $sql);

}

function deleteComments($id, $conn){
  // 댓글 삭제
  $sql = "DELETE From Comment Where Writer_ID='$id'";
  mysqli_query($conn, $sql);

  // 대댓글 삭제
  $sql = "DELETE From ChildComment Where Writer_ID='$id'";
  mysqli_query($conn, $sql);
}

function deleteCommentsForProduct($product_index, $conn){
  // 댓글 삭제
  $sql = "DELETE From Comment Where Product_Index=$product_index";
  mysqli_query($conn, $sql);

  // 대댓글 삭제
  $sql = "DELETE From ChildComment Where Product_Index=$product_index";
  mysqli_query($conn, $sql);
}

$id = $_POST['user_id'];
$pwd = $_POST['password'];
$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
$sql = "DELETE FROM User Where ID='$id' AND Password='$pwd'";
mysqli_query($conn, $sql);

$sql = "SELECT _id FROM User Where ID='$id'";
$result = mysqli_query($conn, $sql);
// $row = mysqli_fetch_array($result);
if(mysqli_fetch_array($result) == null){
  $sql = "SELECT _id FROM Product Where Owner_ID='$id'";
  $result = mysqli_query($conn, $sql);

  while($rows = mysqli_fetch_array($result)){
    $product_index = $rows['_id'];
    deleteProducts($product_index, $conn);
    deleteCommentsForProduct($product_index, $conn);
  }
  deleteComments($id, $conn);
}
else{
  echo "Fail";
}
?>
