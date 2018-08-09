<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>웹 제목</title>

  <style media="screen">
  <?php include("backgroundFooterSet.php"); ?>


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
  <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic" rel="stylesheet">
  <link rel="stylesheet" href="/css/bootstrap.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>

</head>

<body>
  <?php include("print_navigation.php"); ?>

  <div class="container">
    <div class="row">

      <div class="col-lg-9 contentWindow">
        <form class="korean">

            <h2>물품등록</h3><hr>

              <div class="form-group">
                <h5>카테고리</h5>
                <select class="" name="" id="categoryList">
                  <option value="Mac" selected="selected">Mac</option>
                  <option value="iPhone">iPhone</option>
                  <option value="iPad">iPad</option>
                  <option value="Acc">Acc</option>
                </select>
              </div><hr>

              <div class="form-group">
                <h5>제목</h5>
                <input type="text" class="form-control " id="title" aria-describedby="emailHelp" placeholder="제목을 입력하세요">
              </div><hr>

              <div class="form-group">
                <h5>가격</h5>
                <input type="text" class="form-control" id="price" placeholder="가격을 입력하세요">
              </div><hr>

              <fieldset class="form-group">
                <h5>거래방법</h5>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="Trade_1" value="직거래" checked="">
                  <label class="custom-control-label" for="Trade_1">직거래</label>
                </div>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="Trade_2" value="택배거래" checked="">
                  <label class="custom-control-label" for="Trade_2">택배거래</label>
                </div>
              </fieldset><hr>

              <div class="form-group">
                <h5>내용</h5>
                <textarea class="form-control" id="content" rows="5">직거래 지역 :

구매시기 :

연락처 :

특이사항 :

물품설명 :
                </textarea>
              </div><hr>

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

              <a href="javascript:" class="btn btn-primary btn-lg" onclick="submitAction();">등록</a>

          </form>
        </div>
      </div>
    </div>

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

  function handleImgsFilesSelect(e){
    //이미지 정보들 초기화
    sel_files = [];

    // imgs_wrap 클래스 안의 내용들 초기화
    $(".imgs_wrap").empty();

    var files = e.target.files;
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
    console.log("업로드 파일 갯수 : "+sel_files.length);
    var data = new FormData();

    for(var i = 0, len = sel_files.length; i < len; i++) {
        var name = "image_" + i;
        data.append(name, sel_files[i]);
    }
    data.append("image_count", sel_files.length);

    if(sel_files.length < 1) {
        alert("한개이상의 파일을 선택해주세요.");
        return;
    }


    var categoryList = document.getElementById("categoryList");
    var value = categoryList.options[categoryList.selectedIndex].value;
    data.append("selectedCategory", value);
    data.append("title", $('#title').val());
    data.append("price", $('#price').val());

    var trade_check_1 = document.getElementById('Trade_1');
    var trade_check_2 = document.getElementById('Trade_2');
    var tradeType = getCheckNumber($(trade_check_1).prop("checked"), $(trade_check_2).prop("checked"));
    data.append("trade_type", tradeType);
    data.append("trade_check_1", $(trade_check_1).prop("checked"));
    data.append("trade_check_2", $(trade_check_2).prop("checked"));
    data.append("content", $('#content').val());


    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://10.211.55.3/DB/Product_register.php");
    xhr.onload = function(e) {
        if(this.status == 200) {
            console.log("Result : "+e.currentTarget.responseText);
            page_replace();
        }
    }

    xhr.send(data);
  }
  function page_replace(){
    location.replace("home.php");
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
