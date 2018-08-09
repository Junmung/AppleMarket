<?php

$product_index = $_GET['product_index'];

// 카테고리 설정
function setCategory($product_index){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "SELECT Category From Product Where _id=$product_index";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  $category = $row['Category'];
  $selected = "selected='selected'";
  $categoryList = array("Mac", "iPhone", "iPad", "Acc");

  echo "<select id='categoryList'>";
  for ($i = 0; $i < 4; $i++) {
    if($category == $categoryList[$i]){
      echo "<option value='$categoryList[$i]' $selected>$categoryList[$i]</option>";
    }
    else{
      echo "<option value='$categoryList[$i]'>$categoryList[$i]</option>";
    }
  }
  echo "</select>";
}

// 제목, 가격 설정
function setTitleAndPrice($product_index){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "SELECT Title, Price From Product Where _id=$product_index";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  echo "<div class='form-group'>";
    echo "<h5>제목</h5>";
    echo "<input type='text' class='form-control ' id='title' aria-describedby='emailHelp' placeholder='제목을 입력하세요'value='{$row['Title']}'>";
  echo "</div><hr>";

  echo "<div class='form-group'>";
    echo "<h5>가격</h5>";
    echo "<input type='text' class='form-control' id='price' placeholder='가격을 입력하세요' value='{$row['Price']}'>";
  echo "</div><hr>";
}

// 거래방법 설정
function setTradeType($product_index){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "SELECT TradeType From Product Where _id=$product_index";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  switch ($row['TradeType']) {
    case 0:   $trade_1 = "unchecked"; $trade_2 = "unchecked";   break;
    case 1:   $trade_1 = "checked";   $trade_2 = "unchecked";   break;
    case 2:   $trade_1 = "unchecked"; $trade_2 = "checked";     break;
    case 3:   $trade_1 = "checked";   $trade_2 = "checked";     break;
    default:  break;
  }

  echo "<fieldset class='form-group'>";
    echo "<h5>거래방법</h5>";
    echo "<div class='custom-control custom-checkbox'>";
      echo "<input type='checkbox' class='custom-control-input' id='Trade_1' value='직거래' {$trade_1}>";
      echo "<label class='custom-control-label' for='Trade_1'>직거래</label>";
    echo "</div>";
    echo "<div class='custom-control custom-checkbox'>";
      echo "<input type='checkbox' class='custom-control-input' id='Trade_2' value='택배거래' {$trade_2}>";
      echo "<label class='custom-control-label' for='Trade_2'>택배거래</label>";
    echo "</div>";
  echo "</fieldset><hr>";
}

// 내용 설정
function setContent($product_index){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "SELECT Content From Product Where _id=$product_index";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  echo "<div class='form-group'>";
    echo "<h5>내용</h5>";
    echo "<textarea class='form-control' id='content' rows='5'>{$row['Content']}</textarea>";
  echo "</div><hr>";
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>웹 제목</title>
  <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic" rel="stylesheet">
  <style media="screen">
  <?php include("../backgroundFooterSet.php"); ?>

  .contentWindow{
    top: 50px;
    padding-top: 15px;
    padding-bottom: 15px;
    margin-bottom: 100px;
    /* width: 900px; */
    position: absolute;
    transform: translate(-50%, 0);
    left: 50%;
    /* border: 1px solid #666; */
    border-radius: 15px;
    /* box-shadow:0 0 6px (0,0,0,1); */
    background-color: #ffffff;
  }

  input[type=file]{
    display: none;
  }

  </style>

  <!-- Custom Style Bootstrap -->
  <link rel="stylesheet" href="../css/bootstrap.css">
  <!-- Bootstrap core JavaScript -->
  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js" charset="utf-8"></script>

</head>
<body>
  <?php
  include("../print_navigation.php");
  ?>

  <div class="container">
    <div class="row">
      <div class="col-lg-9 contentWindow">
        <form class="korean">
            <a id=product_index value="<?php echo $product_index; ?>"></a>
            <h3>물품수정</h3>
            <hr>
              <div class="form-group">
                <h5>카테고리</h5>
                  <?php setCategory($product_index); ?>
              </div><hr>

              <?php setTitleAndPrice($product_index); ?>

              <?php setTradeType($product_index); ?>

              <?php setContent($product_index); ?>

              <div class="form-group">
                <h5>사진선택</h5>
                <p class="text-primary">(최대 8장까지 선택 가능합니다)</p>

                <div class="imgs_wrap">
                  <img id="img"/>
                </div>

                <div>
                  <div class="input_wrap">
                    <a class="btn btn-outline-primary" href="javascript:" onclick="fileUpLoadAction();">파일찾기</a>
                    <input type="file"  id="input_imgs" multiple/>
                  </div>
                </div>
              </div><hr>

              <a href="javascript:" class="btn btn-primary btn-lg" onclick="submitAction();">수정</a>

          </form>
        </div>
      </div>
    </div>

  <!-- /.container -->

  <!-- 배경이미지 -->
  <div id="bg"><div class="bg_img"></div></div>

  <!-- Footer -->
  <footer class="py-5 bg-primary">
    <div class="container">
      <p class="m-0 text-center">Copyright &copy; Apple Market 2018</p>
    </div>
    <!-- /.container -->
  </footer>
  <script type="text/javascript">
  var sel_files = [];

  $(document).ready(function(){
    loadImgs(<?php echo $product_index; ?>);
    $("#input_imgs").on("change", handleImgsFilesSelect);
  });

  function fileUpLoadAction(){
    console.log("fileUpLoadAction");
    $("#input_imgs").trigger('click');
  }

  // 선택된 이미지 제거
  function deleteImageAction(index){
    console.log("index : "+index);
    console.log("sel length : "+sel_files.length);

    sel_files.splice(index, 1);

    var img_id = "#img_id_"+index;
    $(img_id).remove();
  }

  function loadImgs(product_index){
    // var imgPaths =
    getImgPathsFromServer(product_index);
    var userFiles = [];
    // var files = e.target.files;

    // files를 Array 형태로 변환시켜준다.  files.slice() 와 같은 의미
    // var filesArr = Array.prototype.slice.call(files);

    var index = 0;

    // var html = "<a href=\"javascript:void(0);\" id=\"img_id_\"><img src=\"" + imgPaths[0]+ "\" class='selProductFile' title='Click to remove'></a>";
    // $(".imgs_wrap").append(html);
    //
    // filesArr.forEach(function(f){
    //   if(!f.type.match("image.*")){
    //     alert("확장자는 이미지 확장자만 가능합니다.");
    //     return;
    //   }
    //
    //   sel_files.push(f);
    //
    //   var reader = new FileReader();
    //   reader.onload = function(e){
    //     var html = "<a href=\"javascript:void(0);\" id=\"img_id_\"><img src=\"" + e.target.result + "\" class='selProductFile' title='Click to remove'></a>";
    //     $(".imgs_wrap").append(html);
    //     index++;
    //   }
    //   reader.readAsDataURL(f);
    // });
  }

  function handleImgsFilesSelect(e){
    //이미지 정보들 초기화
    sel_files = [];
    $(".imgs_wrap").empty();

    var files = e.target.files;

    // files를 Array 형태로 변환시켜준다.  files.slice() 와 같은 의미
    var filesArr = Array.prototype.slice.call(files);

    var index = 0;
    filesArr.forEach(function(f){
      if(!f.type.match("image.*")){
        alert("확장자는 이미지 확장자만 가능합니다.");
        return;
      }

      sel_files.push(f);

      var reader = new FileReader();
      reader.onload = function(e){
        var html = "<a href=\"javascript:void(0);\" onclick=\"deleteImageAction("+index+")\" id=\"img_id_"+index+"\"><img src=\"" + e.target.result + "\" data-file='"+f.name+"' class='selProductFile' title='Click to remove'></a>";
        $(".imgs_wrap").append(html);
        index++;
      }
      reader.readAsDataURL(f);
    });
  }

  function submitAction(){
    console.log("업로드 파일 갯수 : " + sel_files.length);
    var data = new FormData();

    for(var i = 0, len = sel_files.length; i < len; i++) {
        var name = "image_" + i;
        data.append(name, sel_files[i]);
    }
    data.append("image_count", sel_files.length);

    if(sel_files.length < 1) {
        // alert("한개이상의 파일을 선택해주세요.");
        // return;
    }

    var categoryList = document.getElementById("categoryList");
    var selectedCategoryValue = categoryList.options[categoryList.selectedIndex].value;

    data.append("product_index", <?php echo "$product_index"; ?>);
    data.append("selectedCategory", selectedCategoryValue);
    data.append("title", $('#title').val());
    data.append("price", $('#price').val());
    var trade_check_1 = document.getElementById('Trade_1');
    var trade_check_2 = document.getElementById('Trade_2');
    var tradeType = getCheckNumber($(trade_check_1).prop("checked"), $(trade_check_2).prop("checked"));
    data.append("trade_type", tradeType);
    data.append("content", $('#content').val());

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "Product_update.php");
    xhr.onload = function(e) {
        if(this.status == 200) {
            console.log("Result : "+e.currentTarget.responseText);
            page_replace();
        }
    }

    xhr.send(data);

  }

  function getImgPathsFromServer(product_index){

    var data = new FormData();
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "getImages.php");
    xhr.onload = function(e) {
        if(this.status == 200) {

            console.log("Result : "+e.currentTarget.responseText);
            var imgPaths = JSON.parse(e.currentTarget.responseText);

            // var reader = new FileReader();
            // reader.readAsDataURL(new File("/DB/uploadImgs/15274036460.jpg"));
            // reader.onload = function(e){
            //   var html = "<a href=\"javascript:void(0);\"><img src=\"" + reader.result + "\" data-file='"+f.name+"' class='selProductFile' title='Click to remove'></a>";
            //   $(".imgs_wrap").append(html);
            //   console.log(reader.result);
            //   document.write(" dd");
            // }

            // 전역 변수인 sel_files에다가
            // 가져온 Path로 파일을 만들어서 넣어주기만 하면 된다아님?
            // 이걸 하기위해선 경로로 파일을 만드는법만 적용시키면 될듯

            // 서버에서 불러와서 뿌려주기는 가능, 파일로 만드는것이 안됨
            for(var i = 0; i < imgPaths.length; i++){
              var html = "<a href=\"javascript:void(0);\" id=\"img_id_\"><img src=\"" + imgPaths[i]+ "\" class='selProductFile' title='Click to remove'></a>";
              $(".imgs_wrap").append(html);
            }
        }
    }
    data.append("product_index", product_index);
    xhr.send(data);
    return imgPaths;
  }

  function page_replace(){
    location.replace("http://10.211.55.3/home.php");
  }

  function getCheckNumber(check_1, check_2){
    var trade_Type = 0;
    if(check_1 == true && check_2 == false){
      trade_Type = 1;
    }
    else if(check_1 == false && check_2 == true){
      trade_Type = 2;
    }
    else if(check_1 == true && check_2 == true){
      trade_Type = 3;
    }

    return trade_Type;

  }
  </script>
</body>
</html>
