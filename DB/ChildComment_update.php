<?php

$product_index = $_POST['product_index'];
$content = $_POST['content'];
$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
$sql = "INSERT Into Comment Values (null, $product_index, '$session_id', '$content', Now(), 0)";
mysqli_query($conn, $sql);
$commentIndex = mysqli_insert_id($conn);

$result['commentIndex'] = $commentIndex;
$result['commentWriterID'] = $session_id;
$result['content'] = $content;
$result['date'] = date("Y-m-d H:i:s");

echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

?>
