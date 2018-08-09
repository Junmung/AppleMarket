<?php

// 이미지를 제외한 내용들 DB에 업로드
$product_index = $_POST['product_index'];
$category = $_POST['selectedCategory'];
$title = $_POST['title'];
$content = $_POST['content'];
$price = $_POST['price'];
$trade_Type = $_POST['trade_type'];

error_log($title."\n", 3, "/usr/local/apache2/htdocs/phpLog.log");
error_log($price."\n", 3, "/usr/local/apache2/htdocs/phpLog.log");
error_log("$trade_Type\n", 3, "/usr/local/apache2/htdocs/phpLog.log");
$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
$sql = "UPDATE Product SET Category='$category', Title='$title', Content='$content', Price=$price, TradeType=$trade_Type Where _id=$product_index";
$result = mysqli_query($conn, $sql);
if($result === false ){
  error_log("쿼리실패", 3, "/usr/local/apache2/htdocs/phpLog.log");

}
else{
  // error_log("", 3, "/usr/local/apache2/htdocs/phpLog.log");

}
// 이미지 수정
// $imageKind = array ('image/pjpeg', 'image/jpeg', 'image/JPG',
//  										'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
// $dir = "./uploadImgs/";
//
// for($i=0; $i<$_POST['image_count']; $i++) {
// 	$image_id = "image_".$i;
// 	$image_file = time().$i.".jpg";
//
// 	if(isset($_FILES[$image_id]) && !$_FILES[$image_id]['error']) {
// 		if(in_array($_FILES[$image_id]['type'], $imageKind)) {
// 			if(move_uploaded_file($_FILES[$image_id]['tmp_name'], $dir.$image_file)) {
//         echo "console.log('Success Upload Image ')";
// 				$sql = "INSERT INTO Image Values(null, {$product_index}, '{$dir}', '{$image_file}')";
// 	      mysqli_query($conn, $sql);
// 			} else {
// 				echo "console.log('Error Upload Image ')";
// 			}
//
// 		} else {
//       echo "console.log('Not Image Type ')";
// 		}
// 	} else {
//     echo "console.log('Image Upload Fail ')";
// 	}
// }
?>
