
<?php

$categoryItem = isset($_GET['selectedList']) ? $_GET['selectedList'] : "All";
$searchItem =   isset($_GET['searchItem']) ? $_GET['searchItem'] : "Empty";
$pageIndex =    isset($_GET['pageIndex']) ? $_GET['pageIndex'] : 1;

function setCategoryList($categoryItem){
  $categoryList = array('All', 'Mac', 'iPhone', 'iPad', 'Acc');
  $listCount = array();
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");

  $sql = "SELECT Count(*) From Product";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  $listCount[0] = $row[0];

  for($i = 1; $i < sizeof($categoryList); $i++){
    $sql = "SELECT Count(*) From Product Where Category='{$categoryList[$i]}'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $listCount[$i] = $row[0];
  }

  print "<h2 class='text-center text-white'><p><hr>$categoryItem List<hr></p></h2>";
  print "<div class='list-group'>";

  for ($i=0; $i < sizeof($categoryList); $i++) {
    if($categoryItem == $categoryList[$i]) $active = 'active';
    else  $active = '';
    print "<a href='home.php?selectedList=$categoryList[$i]' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center $active'>$categoryList[$i]<span class='badge badge-light badge-pill'>$listCount[$i]</span></a>";
  }
  print "</div>";
}

function setProductList($categoryItem, $searchItem, $pageIndex){
  $tradeType = array("",
  "<span class='badge badge-pill badge-primary'>직거래</span>",
  "<span class='badge badge-pill badge-primary'>택배거래</span>",
  "<span class='badge badge-pill badge-primary'>직거래</span><span class='badge badge-pill badge-primary'>택배거래</span>");

  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");


  $start = 6*($pageIndex-1); // 6
  $end = 6;       // 12 

  if($categoryItem == 'All' && $searchItem == 'Empty'){
    // 전체 목록이며, 검색어가 없을경우
    $sql = "SELECT Count(*) as TotalCount From Product";
    $result = mysqli_query($conn, $sql);
    $totalCount = mysqli_fetch_array($result)['TotalCount'];

    $sql = "SELECT * From Product Order By RegisterDate DESC Limit $start, $end";
  }
  else if($searchItem != 'Empty'){
    // 사용자가 검색어를 입력한 경우
    $sql = "SELECT Count(*) as TotalCount From Product Where Title Like '%{$searchItem}%'";
    $result = mysqli_query($conn, $sql);
    $totalCount = mysqli_fetch_array($result)['TotalCount'];

    $sql = "SELECT * From Product Where Title Like '%{$searchItem}%' Order By RegisterDate DESC Limit $start, $end";
  }
  else{
    // 카테고리를 선택한 경우
    $sql = "SELECT Count(*) as TotalCount From Product Where Category='{$categoryItem}'";
    $result = mysqli_query($conn, $sql);
    $totalCount = mysqli_fetch_array($result)['TotalCount'];

    $sql = "SELECT * From Product Where Category='{$categoryItem}' Order By RegisterDate DESC Limit $start, $end";
  }

  $result = mysqli_query($conn, $sql);

  // 찾는 물품이 없는 경우 없다는 메세지 띄우기
  $rows = mysqli_fetch_array($result);
  if($rows == null && $searchItem != 'Empty'){
    $sql = "SELECT * From Product Order By RegisterDate DESC";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_array($result);
    echo "<script language='javascript'>";
    echo "alert('검색어에 맞는 물품이 없습니다')";
    echo "</script>";
  }

  do {
    echo "<div class='col-lg-4 col-md-6 mb-4'>";
      echo "<div class='card mb-3'>";
        echo "<h4 class='card-header text-center korean'>[ {$rows['Category']} ]</h4>";
        $imageSrc = getMainImage($rows['_id'], $conn);
        echo "<a href='detail.php?product_index={$rows['_id']}'><img class='card-img-top'  src='$imageSrc' alt=''></a>";
        echo "<div class='card-body'>";
        echo "<h5 class='' style='font-size:large; color:#476077; font-family:sans-serif;'>&#9679;&nbsp;{$rows['Owner_ID']}</h5><hr>";
          echo "<h5 class='card-title korean'>";
            echo "<a href='detail.php?product_index={$rows['_id']}'><strong>{$rows['Title']}</strong></a>";
          echo "</h5>";
          $currency = number_format($rows['Price']);
          echo "<br><h5 class='korean'>{$currency}원</h5>";
        echo "</div>";

        echo "<div class='card-footer'>";

          echo "<span class='badge badge-pill badge-light korean'>";
          echo passing_time($rows['RegisterDate']);
          echo "</span>";
          echo $tradeType[$rows['TradeType']];
        echo "</div>";
      echo "</div>";
    echo "</div>";
  }while ($rows = mysqli_fetch_array($result));

  return $totalCount;
}

function setPageList($categoryItem, $searchItem, $pageIndex, $totalCount){
  // totalcount 가 0이라면 표시해주지 않기
  $countInBlock = 5;
  $currentBlockPosition = ceil($pageIndex / $countInBlock);

  $b_start_page = ( ($currentBlockPosition - 1) * $countInBlock ) + 1; //현재 블럭에서 시작페이지 번호
  $b_end_page = $b_start_page + $countInBlock - 1; //현재 블럭에서 마지막 페이지 번호
  $totalPage =  ceil($totalCount/6); //총 페이지 수

  if ($b_end_page > $totalPage)
    $b_end_page = $totalPage;


  echo "<div class='form-group' ><div class='row'><div class='col-lg-3'></div><div class='col-lg-4'><div><ul class='pagination ' style='font-size:large;'>";
  echo "<li class='page-item '><a class='page-link' href='/home.php?pageIndex=1&searchItem=$searchItem&selectedList=$categoryItem' >&#9664;&#9664;</a></li>";

  if($currentBlockPosition > 1){
    $previous = $b_start_page-1;
    echo "<li class='page-item '><a class='page-link' href='/home.php?pageIndex=$previous&searchItem=$searchItem&selectedList=$categoryItem' >&#9664;</a></li>";
  }

  $totalBlock = ceil($totalPage / $countInBlock);


  for($i = $b_start_page; $i <= $b_end_page; $i++){
    if($pageIndex == $i)
      echo "<li class='page-item active'><a class='page-link' href='/home.php?pageIndex=$i&searchItem=$searchItem&selectedList=$categoryItem' >$i</a></li>";
    else
      echo "<li class='page-item '><a class='page-link' href='/home.php?pageIndex=$i&searchItem=$searchItem&selectedList=$categoryItem' >$i</a></li>";

  }
  if($currentBlockPosition < $totalBlock){
    $next = $b_end_page + 1;
    echo "<li class='page-item '><a class='page-link' href='/home.php?pageIndex=$next&searchItem=$searchItem&selectedList=$categoryItem' >&#9654;</a></li>";
  }


  echo "<li class='page-item '><a class='page-link' href='/home.php?pageIndex=$totalPage&searchItem=$searchItem&selectedList=$categoryItem' >&#9654;&#9654;</a></li>";
  echo "</ul></div></div></div></div>";
}

function getMainImage($product_index, $conn){
  $sql = "SELECT FileName From Image Where Product_Index={$product_index} Limit 1";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  return "/DB/uploadImgs/".$row[0];
}

// 시간값을 입력받아 언제올렸는지 글자형태로 리턴해준다.
function passing_time($datetime) {
    $time_lag = time() - strtotime($datetime);

    if($time_lag < 60) {
        $posting_time = "방금";
    } elseif($time_lag >= 60 && $time_lag < 3600) {
        $posting_time = floor($time_lag/60)."분 전";
    } elseif($time_lag >= 3600 && $time_lag < 86400) {
        $posting_time = floor($time_lag/3600)."시간 전";
    } elseif($time_lag >= 86400 && $time_lag < 2419200) {
        $posting_time = floor($time_lag/86400)."일 전";
    } else {
        $posting_time = date("y-m-d", strtotime($datetime));
    }

    return $posting_time;
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>웹 제목</title>
  <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic" rel="stylesheet">
  <style media="screen">

  <?php include("backgroundFooterSet.php"); ?>
  .card{
    font-family: 'Nanum Gothic', sans-serif;
    text-transform: none;
  }


  </style>

  <link rel="stylesheet" href="/css/bootstrap.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <?php
  include("print_navigation.php");
  ?>


  <!-- Page Content -->
  <div class="container">
    <div class="row">
      <!-- 카테고리 리스트 -->
      <div class="col-lg-3">
        <?php setCategoryList($categoryItem); ?>
      </div>

      <!-- 본문 -->
      <div class="col-lg-9 ">
        <br>

        <div class="row">
          <?php $totalCount = setProductList($categoryItem, $searchItem, $pageIndex); ?>
        </div>

        <!-- 페이지 부분 -->
        <?php setPageList($categoryItem, $searchItem, $pageIndex, $totalCount); ?>

      </div>
    </div>
  </div>

   <!-- 배경이미지  -->
  <div id="bg"><div class="bg_img"></div></div>

  <!-- Footer -->
  <footer class="py-5 bg-primary">
    <div class="container">
      <p class="m-0 text-center">Copyright &copy; Apple Market 2018</p>
    </div>
  </footer>

</body>
</html>
