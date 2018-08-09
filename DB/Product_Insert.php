<?php

// 이미지를 제외한 내용들 DB에 업로드
$trade_check_1 =  $_POST['trade_check_1'];
$trade_check_2 =  $_POST['trade_check_2'];
$trade_Type = 0;
if($trade_check_1 == true && $trade_check_2 == false){
  $trade_Type = 1;
}
else if($trade_check_1 == false && $trade_check_2 == true){
  $trade_Type = 2;
}
else if($trade_check_1 == true && $trade_check_2 == true){
  $trade_Type = 3;
}
$conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");


$sql = "INSERT Into Product Values
        (null, 'test_User', '{$_POST['selectedCategory']}', '{$_POST['title']}',
          '{$_POST['content']}', {$_POST['price']}, $trade_Type, true, Now())";
$result = mysqli_query($conn, $sql);
header('Location: ../home.php');

//
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
// 				echo "Success Upload Image <br/>";
// 				$sql = "INSERT INTO Image Values(null, 101, '$dir', '$image_file')";
// 	      mysqli_query($conn, $sql);
// 			} else {
// 				echo "Error Upload Image <br/>";
// 			}
//
// 		} else {
// 			echo "Not Image Type <br/>";
// 		}
// 	} else {
// 		echo "Image Upload Fail <br/>";
// 	}
// }
?>
