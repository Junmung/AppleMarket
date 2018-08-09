<?php
$product_index = $_POST['product_index'];
$filePath = "http://10.211.55.3/DB/uploadImgs/";

$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");

// Product_index 에 해당하는 파일이름을 가져온다.
$sql = "SELECT FileName From Image Where Product_Index=$product_index";
$result = mysqli_query($conn, $sql);

$pathArray = array();
$i = 0;
while($rows = mysqli_fetch_array($result)){
  $fileName = $rows['FileName'];
  $imagePath = $filePath.$fileName;
  array_push($pathArray, $imagePath);
  $i++;
}

echo json_encode($pathArray);

?>
