<?php
@session_start();
$session_id = $_SESSION['user_id'];
$commentIndex = $_POST['commentIndex'];
$content = $_POST['content'];
$product_index = $_POST['product_index'];

// 대댓글 Insert
$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
$sql = "INSERT Into ChildComment Values (null, $commentIndex, '$session_id', '$content', Now(), $product_index)";
mysqli_query($conn, $sql);
$currentChildIndex = mysqli_insert_id($conn);

$sql = "UPDATE Product Set Comment_Count = Comment_Count + 1 Where _id=$product_index";
mysqli_query($conn, $sql);

$sql = "UPDATE Comment SET hasChild=1 Where _id=$commentIndex";
mysqli_query($conn, $sql);


$sql = "SELECT _id From ChildComment Where Parent_Index=$commentIndex Order By _id LIMIT 1";
$sqlResult = mysqli_query($conn, $sql);
$row2 = mysqli_fetch_array($sqlResult);

// 등록하려는 댓글이 대댓글을 가지고있지않다면
if($row2['_id'] == $currentChildIndex){
  $result['lastChildIndex'] = $currentChildIndex;
}
else{
  $sql = "SELECT _id From ChildComment Where Parent_Index=$commentIndex Order By _id DESC LIMIT 2";
  $sqlResult = mysqli_query($conn, $sql);
  mysqli_fetch_array($sqlResult);
  $row2 = mysqli_fetch_array($sqlResult);

  //마지막인덱스
  $result['lastChildIndex'] = $row2[0];
}

$result['currentChildIndex'] = $currentChildIndex;
$result['parentIndex'] = $commentIndex;
$result['commentWriterID'] = $session_id;
$result['content'] = $content;
$result['date'] = date("Y-m-d H:i:s");

echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

?>
