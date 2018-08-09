
<?php
session_start();
$user_id = $_SESSION['user_id'];
$pageIndex =    isset($_GET['pageIndex']) ? $_GET['pageIndex'] : 1;

if(isset($_GET['password_check'])){
  if($_GET['password_check'] == 'fail')
    echo "<script> alert('비밀번호가 틀렸습니다');</script>";
  else
    echo "<script> alert('수정되었습니다');</script>";
}

$categoryItem = isset($_GET['categoryItem']) ? $_GET['categoryItem'] : "sellingProducts";

// 카테고리 세팅
function setCategoryList($categoryItem){
  $englishList = array('myInfo', 'sellingProducts', 'wroteComments', 'memberLeave');
  $koreanList = array(nl2br('내 정보'), nl2br('판매중인 물품'), nl2br('내가 쓴 댓글'), nl2br('회원탈퇴'));

  echo "<h2 class='text-white'><p><hr>My Page<hr></p></h2>";
  echo "<div class='list-group'>";
  for($i = 0; $i < sizeof($englishList); $i++){
    if($categoryItem == $englishList[$i]) $active = 'active';
    else $active = '';
    echo "<a href='/mypage.php?categoryItem=$englishList[$i]' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center $active'>{$koreanList[$i]}</a>";
  }

  echo "</div>";
}

// 내정보 세팅
function setMyInfo($user_id){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "SELECT Email From User Where ID='$user_id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  $user = $user_id;

  echo "<div class='joinWindow'><div class='container text-center'><div class='row'><div class='col-md-2'></div><div class='col-md-8'> <h1>내 정보</h1> </div><div class='col-md-2'></div></div></div><br>";
    echo "<form action='/DB/User_Update.php' method='post'>";
      echo "<div class='form-group'><div class='row'><div class='col-md-2'></div><div class='col-md-8'>";
            echo "<label class='text-primary' style='font-size:large; float:left;' for='user_id'><strong>ID</strong></label>";
            echo "<input readonly type='text' class='form-control ' style='font-size:medium;' name='user' id='user_id' value='$user'  ></input>";
            echo "<div class='invalid-feedback korean' style='font-size:medium;' id='user_id_feedback'></div></div></div></div>";

      // <!-- 이메일 -->
      echo "<div class='form-group'><div class='row'><div class='col-md-2'></div><div class='col-md-8'>";
            echo "<label class='text-primary' style='font-size:large;padding-top:5px;' for='email'><strong>Email</strong></label>";
            echo "<input type='text' class='form-control is-invalid' style='font-size:medium;' name='email' id='email' value='{$row['Email']}' onkeyup='validation(this.name, this.value);' placeholder='이메일을 입력하세요'>";
            echo "<div class='invalid-feedback korean' style='font-size:medium;' id='email_feedback'></div></div></div></div>";

      // <!-- 현재 비밀번호 -->
      echo "<div class='form-group'><div class='row'><div class='col-md-2'></div><div class='col-md-8'>";
            echo "<label class='text-primary' style='font-size:large;padding-top:5px;' for='current_pwd'><strong>Current Password</strong></label>";
            echo "<input type='password' class='form-control ' name='current_pwd' id='current_pwd' value='' placeholder='현재 비밀번호를 입력하세요'></div></div></div>";

      // <!-- 새로운 비밀번호 -->
      echo "<div class='form-group'><div class='row'><div class='col-md-2'></div><div class='col-md-8'>";
            echo "<label class='text-primary' style='font-size:large;padding-top:5px;' for='password'><strong>New - Password</strong></label>";
            echo "<input type='password' class='form-control is-invalid' name='password' id='password' value='' onkeyup='validation(this.name, this.value);' placeholder='새로운 비밀번호를 입력하세요'>";
            echo "<div class='invalid-feedback korean' style='font-size:medium;' id='password_feedback'></div></div></div></div>";

      // <!-- 비밀번호 확인 -->
      echo "<div class='form-group'><div class='row'><div class='col-md-2'></div><div class='col-md-8'>";
            echo "<label class='text-primary' style='font-size:large;padding-top:5px;' for='pwdCheck'><strong>Re - Password</strong></label>";
            echo "<input type='password' class='form-control is-invalid' name='pwdCheck' id='pwdCheck' onkeyup='validation(this.name, this.value);' placeholder='비밀번호 확인'>";
            echo "<div class='invalid-feedback korean' style='font-size:medium;' id='pwdCheck_feedback'></div></div></div></div><br>";

      // <!-- 정보저장 -->
      echo "<div class='form-group'><div class='row' ><div class='col-md-4'></div><div class='col-md-4 text-center'><button type='submit' class='btn btn-outline-primary' style='font-size:15px;' >정보저장</a></div></div></div>";
   echo "</form></div>";
}

// 판매중인 물품들 세팅
function setSellingProducts($user_id, $pageIndex){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "SELECT Count(*) as TotalCount From Product Where Owner_ID='$user_id'";
  $result = mysqli_query($conn, $sql);
  $totalCount = mysqli_fetch_array($result)['TotalCount'];

  $start = 5*($pageIndex-1);
  $end = 5;

  $sql = "SELECT _id, Title, Category, Comment_Count ,Date(RegisterDate) as regiDate From Product Where Owner_ID='$user_id' Order by RegisterDate desc LIMIT $start, $end";
  $result = mysqli_query($conn, $sql);

  echo "<h2 class=' text-white'><p><hr>물품 목록<hr></p></h2>";
  echo "<div class='row'>";
    echo "<table class='text-center table table-hover' >";
      echo "<thead>";
        echo "<tr class='table-dark text-primary '>";
          echo "<th scope='col'witdh='100' ></th>";
          echo "<th scope='col' style='padding-left:0px;'>분류</th>";
          echo "<th scope='col' >제목</th>";
          echo "<th scope='col'>작성일</th>";
       echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
  while($rows = mysqli_fetch_array($result)){

      echo "<tr class='table-secondary'>";
        echo "<td><input type='checkbox'  name='CheckBox' id='check{$rows['_id']}' value='{$rows['_id']}'/></td>";
        echo "<td style='padding-left:0px;'>{$rows['Category']}</td>";
        echo "<td style='padding-left:0px;padding-right:0px; '><a href='/detail.php?product_index={$rows['_id']}'>{$rows['Title']}";
        echo "<strong style='color:red;'>&nbsp;[{$rows['Comment_Count']}]</strong></a></td>";
        echo "<td>{$rows['regiDate']}</td>";
      echo "</tr>";
  }
  echo "</tbody>";
  echo "</table>";

  echo "<a href='javascript:' onclick='selectedDelete();' id='selectedDeleteBtn' class='btn btn-primary btn-lg'>선택삭제</a>";
  print("
  <script type='text/javascript'>
  function selectedDelete(){
    var data = new FormData();
    var productIndexs = new Array();

    var checkbox = $('input[name=CheckBox]:checked');
    checkbox.each(function(){
      productIndexs.push(this.value);
    });

    for(var i = 0; i < productIndexs.length; i++){
      $.ajax({
        url:'./DB/Product_delete.php',
        type:'post',
        data:{
          product_index: productIndexs[i]
        },
        dataType: 'json',
        success: function(){
          // 태그에서 댓글을 빼준다.
          console.log(''+productIndexs[i]+'번호 삭제');
        },
        error: function(errormsg){
          console.log(arguments);
          console.log(errormsg);
        }
      });
    }
    page_replace();
  }
  function page_replace(){
    location.replace('mypage.php');
  }
  </script>
  ");
  setPageList($totalCount, $pageIndex, "sellingProducts");
}

// 내가 쓴 댓글 세팅
function setComments($user_id, $pageIndex){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "SELECT Count(*) as TotalCount From Comment
          Where Writer_ID = '$user_id' AND  Content != '--- 삭제된 댓글입니다 ---'
          Union
          Select Count(*) as TotalCount From ChildComment
          Where Writer_ID = '$user_id' AND  Content != '--- 삭제된 댓글입니다 ---'
          ";
  $result = mysqli_query($conn, $sql);
  $totalCount = mysqli_fetch_array($result)['TotalCount'];

  $start = 5*($pageIndex-1);
  $end = 5;


  $sql = "SELECT * From (SELECT Product_Index, Content, RegisterDate
          From Comment
          Where Writer_ID = '$user_id' AND  Content != '--- 삭제된 댓글입니다 ---'
          Union
          Select Product_Index, Content, RegisterDate
          From ChildComment
          Where Writer_ID = '$user_id') Comments
          Order By RegisterDate DESC LIMIT $start, $end
          ";


  // $sql = "SELECT Product_Index, Content, Date(RegisterDate) as regiDate
  //         From Comment
  //         Where Writer_ID = '$user_id' AND  Content != '--- 삭제된 댓글입니다 ---'
  //         Order by regiDate LIMIT $start, $end
  //         Union
  //         Select Product_Index, Content, Date(RegisterDate) as regiDate
  //         From ChildComment
  //         Where Writer_ID = '$user_id' AND  Content != '--- 삭제된 댓글입니다 ---'
  //         Order by regiDate LIMIT $start, $end";

  $result = mysqli_query($conn, $sql);

  echo "<h2 class=' text-white'><p><hr>댓글 목록<hr></p></h2>";
  echo "<div class='row'>";
    echo "<table class='text-center table table-hover' >";
      echo "<thead>";
        echo "<tr class='table-dark text-primary '>";
          echo "<th scope='col' style='padding-left:0px;'>원문</th>";
          echo "<th scope='col' style='padding-left:0px; padding-right:0px;'>내용</th>";
          echo "<th scope='col'>작성일</th>";
       echo "</tr>";
    echo "</thead>";

    echo "<tbody>";

  while($rows = mysqli_fetch_array($result)){
    echo "<tr class='table-secondary'>";
      echo "<td style='padding-left:0px;'><a href='/detail.php?product_index={$rows['Product_Index']}'>원문보기</a></td>";
      echo "<td >{$rows['Content']}</td>";
      echo "<td style='padding-left:0px;padding-right:0px;'>{$rows['RegisterDate']}</td>";
    echo "</tr>";
  }
  echo "</tbody></table>";
  setPageList($totalCount, $pageIndex, "wroteComments");
}

// 페이지 세팅
function setPageList($totalCount, $pageIndex, $categoryItem){
  // $totalCount = 100;
  $countInBlock = 5;
  $currentBlockPosition = ceil($pageIndex / $countInBlock);

  $b_start_page = ( ($currentBlockPosition - 1) * $countInBlock ) + 1; //현재 블럭에서 시작페이지 번호
  $b_end_page = $b_start_page + $countInBlock - 1; //현재 블럭에서 마지막 페이지 번호
  $totalPage =  ceil($totalCount/5); //총 페이지 수

  if ($b_end_page > $totalPage)
    $b_end_page = $totalPage;



  echo "<div class='form-group' ><div class='row'><div class='col-lg-4'></div><div class='col-lg-4'><div><ul class='pagination ' style='font-size:large;'>";
  echo "<li class='page-item '><a class='page-link' href='/mypage.php?pageIndex=1&categoryItem=$categoryItem' >&#9664;&#9664;</a></li>";

  if($currentBlockPosition > 1){
    $previous = $b_start_page-1;
    echo "<li class='page-item '><a class='page-link' href='/mypage.php?pageIndex=$previous&categoryItem=$categoryItem' >&#9664;</a></li>";
  }

  $totalBlock = ceil($totalPage / $countInBlock);

  for($i = $b_start_page; $i <= $b_end_page; $i++){
    if($pageIndex == $i)
      echo "<li class='page-item active'><a class='page-link' href='/mypage.php?pageIndex=$i&categoryItem=$categoryItem' >$i</a></li>";
    else
      echo "<li class='page-item '><a class='page-link' href='/mypage.php?pageIndex=$i&categoryItem=$categoryItem' >$i</a></li>";

  }
  if($currentBlockPosition < $totalBlock){
    $next = $b_end_page + 1;
    echo "<li class='page-item '><a class='page-link' href='/mypage.php?pageIndex=$next&categoryItem=$categoryItem' >&#9654;</a></li>";
  }


  echo "<li class='page-item '><a class='page-link' href='/mypage.php?pageIndex=$totalPage&categoryItem=$categoryItem' >&#9654;&#9654;</a></li>";
  echo "</ul></div></div></div></div>";

}

// 회원탈퇴 태그 셋팅
function setMemberLeave($user_id){
  echo "<div class='joinWindow korean' style='width:500px;'>";
    echo "<div class='container text-center'><div class='row'><div class='col-md-2'></div><div class='col-md-8'><h1>비밀번호입력</h1></div></div></div>";


      echo "<div ><div class='row'><div class='col-md-2'></div><div class='col-md-8'>";

            echo "<input type='password' class='form-control' style='font-size:medium;margin-top:20px;' name='leavePwd' id='leavePwd' placeholder='비밀번호를 입력하세요'></div><div class='col-md-2'></div></div></div><br>";

      echo "<div ><div class='row' ><div class='col-md-4'></div>";
          echo "<div class='col-md-4'><button class='btn btn-outline-danger' id='memberLeaveBtn' style='margin-left:12px;'>회원탈퇴</button></div></div>";
      echo "</div>";

  echo "</div>";

  // <!-- 삭제 다이얼로그 -->
  print("
    <div class='modal' id='memberLeaveModal'>
      <div class='modal-dialog' role='document'>
        <div class='modal-content'>
          <div class='modal-header'><button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>
          <div class='modal-body'><h4 class='korean'>정말 탈퇴하시겠습니까?</h4></div>
          <div class='modal-footer'>
            <button type='button' id='modalLeave' class='btn btn-outline-danger korean'>삭제</button>
            <button type='button' id='modalCancel' class='btn btn-secondary korean' data-dismiss='modal'>취소</button></div></div></div></div>


    <script>
    var productModal = document.getElementById('memberLeaveModal');
    var productModalOpenBtn = document.getElementById('memberLeaveBtn');
    var productRealLeaveBtn = document.getElementById('modalLeave');
    var cancelBtn = document.getElementById('modalCancel');
    var span = document.getElementsByClassName('close')[0];

    productModalOpenBtn.onclick = function() {
      productModal.style.display = 'block';
    }

    productRealLeaveBtn.onclick = function() {
      var data = new FormData();
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/DB/User_Delete.php');
      xhr.onload = function(e) {
          if(this.status == 200) {
              console.log('Result : '+this.responseText.trim());
              if(e.currentTarget.responseText.trim() == 'Fail'){
                productModal.style.display = 'none';
                alert('비밀번호가 틀렸습니다');
              }
              else{
                 page_replace();
              }

          }
      }
      data.append('password', $('#leavePwd').val());
      data.append('user_id', '$user_id');
      xhr.send(data);

    }

    cancelBtn.onclick = function(){
      productModal.style.display = 'none';
    }

    window.onclick = function(event) {
      if (event.target == productModal) {  productModal.style.display = 'none'; }
    }

    span.onclick = function() {
      productModal.style.display = 'none';
    }

    function page_replace(){

      location.replace('logout.php');
    }
    </script>"
  );
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
    <?php include("backgroundFooterSet.php"); ?>

    .body_{
      position: relative;
      height: 1200px;
      width: 100%;
      padding-top: 0px;
      font-family: 'Nanum Gothic', sans-serif;
    }

    .joinWindow{
      top: 40px;
      padding-top: 50px;
      padding-bottom: 30px;
      width: 600px;
      position: absolute;
      transform: translate(-50%, 0);
      left:50%;
      border: 1px solid #666;
      border-radius: 15px;
      background-color: #ffffff;
    }

    td a{
      width: 100%;
      height: 100%;
      display: block;
    }

    </style>

    <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic" rel="stylesheet">
    <link rel="stylesheet" href="/css/bootstrap.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script language="javascript">
      function validation(name, value){
        var regEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i; //이메일 유효성검사식
        var regPwd = /^[a-z0-9]{6,20}$/;


        if(name == 'password'){
          if(!value){
            $("#password").addClass("is-invalid");
            $("#password").removeClass("is-valid");
            $("#password_feedback").addClass("invalid-feedback");
            $("#password_feedback").removeClass("valid-feedback");
            $("#password_feedback").text("비밀번호를 입력해주세요");

            return false;
          }
          else{
            if(!value.match(regPwd)){
              //  형식이 틀린상황
              $("#password").addClass("is-invalid");
              $("#password").removeClass("is-valid");
              $("#password_feedback").addClass("invalid-feedback");
              $("#password_feedback").removeClass("valid-feedback");
              $("#password_feedback").text("비밀번호는 6자리 이상이어야합니다.");
              return false;
            }
            else{
              //  형식이 맞은 상황
              $("#password").addClass("is-valid");
              $("#password").removeClass("is-invalid");
              $("#password_feedback").addClass("valid-feedback");
              $("#password_feedback").removeClass("invalid-feedback");
              $("#password_feedback").text("등록가능한 비밀번호입니다.");

            }
          }
        }

        if(name == 'pwdCheck'){
          if(!value){
            $("#pwdCheck").addClass("is-invalid");
            $("#pwdCheck").removeClass("is-valid");
            $("#pwdCheck_feedback").addClass("invalid-feedback");
            $("#pwdCheck_feedback").removeClass("valid-feedback");
            $("#pwdCheck_feedback").text("비밀번호를 입력해주세요");

            return false;
          }
          else{
            if(value != $("#password").val()){
              // 이메일 형식이 틀린상황
              $("#pwdCheck").addClass("is-invalid");
              $("#pwdCheck").removeClass("is-valid");
              $("#pwdCheck_feedback").addClass("invalid-feedback");
              $("#pwdCheck_feedback").removeClass("valid-feedback");
              $("#pwdCheck_feedback").text("비밀번호가 일치하지 않습니다.");
              return false;
            }
            else{
              // 이메일 형식이 맞은 상황
              $("#pwdCheck").addClass("is-valid");
              $("#pwdCheck").removeClass("is-invalid");
              $("#pwdCheck_feedback").addClass("valid-feedback");
              $("#pwdCheck_feedback").removeClass("invalid-feedback");
              $("#pwdCheck_feedback").text("비밀번호 일치");

              return true;
            }
          }
        }


        if(name == 'email'){
          if(!value){
            $("#email").addClass("is-invalid");
            $("#email").removeClass("is-valid");
            $("#email_feedback").addClass("invalid-feedback");
            $("#email_feedback").removeClass("valid-feedback");
            $("#email_feedback").text("이메일을 입력해주세요");

            return false;
          }
          else{
            if(!value.match(regEmail)){
              // 이메일 형식이 틀린상황
              $("#email").addClass("is-invalid");
              $("#email").removeClass("is-valid");
              $("#email_feedback").addClass("invalid-feedback");
              $("#email_feedback").removeClass("valid-feedback");
              $("#email_feedback").text("이메일 형식에 맞게 입력해주세요");
              return false;
            }
            else{
              // 이메일 형식이 맞은 상황
              $("#email").addClass("is-valid");
              $("#email").removeClass("is-invalid");
              $("#email_feedback").addClass("valid-feedback");
              $("#email_feedback").removeClass("invalid-feedback");
              $("#email_feedback").text("등록가능한 이메일입니다");
            }
          }
        }
      }
    </script>
  </head>

  <body>
    <?php include("print_navigation.php");  ?>

    <div class="body_">
      <div class="container ">
        <div class="row">
          <!-- 카테고리 리스트 -->
          <div class="col-lg-3">
            <?php setCategoryList($categoryItem); ?>
          </div>
          <!-- 본문 -->
          <div class="col-lg-9 korean ">
            <?php
            switch ($categoryItem) {
              case 'myInfo':
              setMyInfo($user_id);          break;

              case 'sellingProducts':
              setSellingProducts($user_id, $pageIndex); break;

              case 'wroteComments':
              setComments($user_id, $pageIndex);        break;

              case 'memberLeave' :
              setMemberLeave($user_id);     break;

              default:
              setSellingProducts($user_id, $pageIndex); break;
            }
            ?>

            </div>
          </div>
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
    </footer>


  </body>
</html>
