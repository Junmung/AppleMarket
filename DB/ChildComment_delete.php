<?php
$parent_index = $_POST['parent_Index'];
$child_index = $_POST['child_Index'];
$product_index = $_POST['product_index'];

$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
$sql = "DELETE FROM ChildComment Where _id=$child_index";
mysqli_query($conn, $sql);

$sql = "SELECT _id From ChildComment Where Parent_Index=$parent_index";
$sqlResult = mysqli_query($conn, $sql);
$childCount = mysqli_num_rows($sqlResult);

$sql = "SELECT Content From Comment Where _id=$parent_index";
$sqlResult = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($sqlResult);
$content = nl2br($row['Content']);



// 대댓글이 0개 이고 삭제된 상태일 경우에만 원 댓글까지 지운다.
if($childCount == 0 && $content == "--- 삭제된 댓글입니다 ---"){
  $sql = "DELETE FROM Comment Where _id=$parent_index";
  mysqli_query($conn, $sql);
  $WithParent = "WithParent";
  $result['response'] = $WithParent;
}
else if($childCount == 0 && $content != "--- 삭제된 댓글입니다 ---"){
  $sql = "UPDATE Comment SET hasChild=0 Where _id=$parent_index";
  mysqli_query($conn, $sql);


  $OnlyChild = "OnlyChild";
  $result['response'] = $OnlyChild;
}
else{
  $OnlyChild = "OnlyChild";
  $result['response'] = $OnlyChild;
}

$sql = "UPDATE Product Set Comment_Count = Comment_Count - 1 Where _id=$product_index";
mysqli_query($conn, $sql);

echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

?>
