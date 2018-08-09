<?php
$isParent = $_POST['isParent'];
$commentIndex = $_POST['commentIndex'];
$content = $_POST['content'];

$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
if($isParent){
  $sql = "UPDATE Comment SET Content='$content' Where _id=$commentIndex";
  $result['type'] = "comment";
}
else{
  $sql = "UPDATE ChildComment SET Content='$content' Where _id=$commentIndex";
  $result['type'] = "child";
}

mysqli_query($conn, $sql);

$result['commentIndex'] = $commentIndex;
$result['modifiedContent'] = $content;

echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

?>
