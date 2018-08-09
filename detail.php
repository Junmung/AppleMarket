<?php
@session_start();
if(isset($_SESSION['user_id'])){
  $user_id = $_SESSION['user_id'];
}
else{
  $user_id = "";
}

$product_index = $_GET['product_index'];

if(isset($_GET['selectedList'])){
  $selectedListItem = $_GET['selectedList'];
}
else{
  $selectedListItem = "All";
}

// 카테고리 세팅
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
    if($categoryItem == $categoryList[$i]){  $active = 'active';  }
    else{  $active = '';  }
    print "<a href='home.php?selectedList=$categoryList[$i]' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center $active'>$categoryList[$i]<span class='badge badge-light badge-pill'>$listCount[$i]</span></a>";
  }
  print "</div>";
}

// 슬라이드 이미지 세팅
function setProductImgsSlide($product_index){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");

  $sql = "SELECT FileName From Image Where Product_Index='{$product_index}'";
  $result = mysqli_query($conn, $sql);
  $imgs_Count = mysqli_num_rows($result);

  for($i = 0; $i < $imgs_Count; $i++){
    if($i == 0){
      echo "<div id='productImgsSlide' class='carousel slide' data-ride='carousel'>";
      echo "<ol class='carousel-indicators'>";
      echo "<li data-target='#productImgsSlide' data-slide-to='{$i}' class='active'></li>";
    }
    else{ echo "<li data-target='#productImgsSlide' data-slide-to='{$i}'></li>"; }
  }
  echo "</ol>";
  echo "<div class='carousel-inner'>";

  for($i = 0; $i < $imgs_Count; $i++){
    $rows = mysqli_fetch_array($result);
    if($i == 0){ echo "<div class='carousel-item active'>"; }
    else{ echo "<div class='carousel-item'>"; }
    echo "<img class='d-block img-fluid' src='/DB/uploadImgs/{$rows['FileName']}'>";
    echo "</div>";
  }
  echo "</div>";
  echo "<a class='carousel-control-prev' href='#productImgsSlide' role='button' data-slide='prev'>";
  echo "<span class='carousel-control-prev-icon' aria-hidden='true'></span>";
  echo "<span class='sr-only'>Previous</span></a>";
  echo "<a class='carousel-control-next' href='#productImgsSlide' role='button' data-slide='next'>";
  echo "<span class='carousel-control-next-icon' aria-hidden='true'></span>";
  echo "<span class='sr-only'>Next</span></a></div>";
}

// 내용 세팅
function setContents($product_index){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "SELECT * From Product Where _id='{$product_index}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  $currency = number_format($row['Price']);
  $tradeType = array("",
  "<span class='badge badge-pill badge-primary'>직거래</span>",
  "<span class='badge badge-pill badge-primary'>택배거래</span>",
  "<span class='badge badge-pill badge-primary'>직거래</span><span class='badge badge-pill badge-primary'>택배거래</span>");

  echo "<div class='card mt-4'><div class='card-header korean'>";
  echo "<h4 class='text-primary'>{$row['Title']}</h3>";

  if(isset($_SESSION['user_id'])){
    // 수정 버튼
    if(getProductOwnerID($product_index) == "{$_SESSION['user_id']}"){
      echo "<form class='form' action='./DB/Product_modify.php' method='get' >";
      echo "<div class='form-group'>";
      echo "<input type='hidden' name='product_index' value='$product_index' />";
      echo "<button type='submit' class='my_button'>수정</button></div></form>";

      // 삭제버튼
      echo "<button class='my_button' id='productDeleteBtn'>삭제</button>";
    }
  }

  echo "</div><div class='card-body korean'>";
  echo "<h4>{$currency}원</h4><hr>";
  echo "<p class='text-primary'>";
  echo nl2br($row['Content']);
  echo "</p><hr>";

  echo $tradeType[$row['TradeType']];
  echo "<span class='badge badge-pill badge-primary' style='float:right;font-size:small;'>{$row['RegisterDate']}</span></div></div>";
}

// 댓글 세팅
function setComments($product_index, $session_id){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "SELECT * From Comment Where Product_Index=$product_index Order By RegisterDate ASC";
  $result = mysqli_query($conn, $sql);

  while($row = mysqli_fetch_array($result)){
    $commentID = $row['_id'].$row['Writer_ID'];
    $textColor = "";
    echo "<div id='comment{$row['_id']}'>";
    echo "<span class='text-info' style='font-size:large;'>{$row['Writer_ID']}</span>";
    if(getProductOwnerID($product_index) == $row['Writer_ID']){
      echo "<span class='badge badge-pill badge-danger'style='margin-left:10px; '>작성자</span>";
    }
    echo "<button type='button' onclick='showReplyWindow({$row['_id']});' class='btn btn-sm btn-outline-secondary' style='font-size:small;padding:4px; padding-left:6px;padding-right:6px;margin-left:15px;'>답글</button>";

    // 삭제, 수정 버튼 로그인
    if($row['Writer_ID'] == $session_id){
      echo "<button type='button' id='comment$commentID' onclick='commentDelete({$row['_id']}, \"{$row['Writer_ID']}\");' class='btn btn-sm btn-outline-secondary' style='float:right;font-size:small;padding:4px; padding-left:6px;padding-right:6px;margin-bottom:6px;margin-left:15px;'>삭제</button>";
      echo "<button type='button' id='btn_commentModify' onclick='showModifyWindow(1, {$row['_id']}, \"{$row['Content']}\");' class='btn btn-sm btn-outline-secondary' style='float:right;font-size:small;padding:4px; padding-left:6px;padding-right:6px;margin-bottom:6px;margin-left:15px;'>수정</button>";
    }
    echo "<br><br><span style='float:right;'>{$row['RegisterDate']}</span>";
    if(nl2br($row['Content']) == "--- 삭제된 댓글입니다 ---"){
      $textColor = "color:red;";
    }
    echo "<h6 style='padding-left:10px;$textColor'>";

    echo nl2br($row['Content']);
    echo "</h6><hr></div>";

    // 부모댓글에 대댓글이 달려있다면 대댓글 생성해주기
    if($row['hasChild'] == 1) setChildComment($row['_id'], $session_id);
  }
}

// 대댓글 세팅
function setChildComment($parent_index, $session_id){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");
  $sql = "SELECT * From ChildComment Where Parent_Index=$parent_index Order By RegisterDate ASC";
  $result = mysqli_query($conn, $sql);

  while($childRows = mysqli_fetch_array($result)){
    $commentID = $childRows['_id'].$childRows['Writer_ID'];
    echo "<div id=\"child{$childRows['_id']}\">";
    echo "<div style='padding-left:30px;'>";
    echo "<span class='text-info' style='font-size:large;'>&#8618;&nbsp;{$childRows['Writer_ID']}</span>";
    if(getProductOwnerID($childRows['Product_Index']) == $childRows['Writer_ID']){
      echo "<span class='badge badge-pill badge-danger'style='margin-left:10px; '>작성자</span>";
    }
    if($session_id == $childRows['Writer_ID']){
      echo "<button type='button' id='child$commentID' onclick='childCommentDelete({$childRows['Parent_Index']}, {$childRows['_id']}, \"{$childRows['Writer_ID']}\");' class='btn btn-sm btn-outline-secondary' style='float:right;font-size:small;padding:4px; padding-left:6px;padding-right:6px;margin-bottom:6px;margin-left:15px;'>삭제</button>";
      echo "<button type='button' id='btn_commentModify' onclick='showModifyWindow(0, {$childRows['_id']}, \"{$childRows['Content']}\");' class='btn btn-sm btn-outline-secondary' style='float:right;font-size:small;padding:4px; padding-left:6px;padding-right:6px;margin-bottom:6px;margin-left:15px;'>수정</button>";
    }
    echo "<br><br><span style='float:right;'>{$childRows['RegisterDate']}</span>";
    echo "<h6 style='padding-left:35px;'>{$childRows['Content']}</h6><hr></div></div>";
  }
}

function getProductOwnerID($product_index){
  $conn = mysqli_connect("localhost", "root", "!!236510ss", "AppleMarket");

  $sql = "SELECT Owner_ID From Product Where _id=$product_index";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  return $row['Owner_ID'];
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>웹 제목</title>
  <style media="screen">
  <?php include("backgroundFooterSet.php"); ?>

  .card{
    font-family: 'Nanum Gothic', sans-serif;
    text-transform: none;
  }

  .contentWindow{
    top: 50px;
    padding-top: 15px;
    padding-bottom: 15px;
    margin-bottom: 100px;
    width: 900px;
    position: relative;
    transform: translate(-50%, 0);
    left: 40%;
    border: 1px solid #666;
    border-radius: 15px;
    /* box-shadow:0 0 6px (0,0,0,1); */
    background-color: #ffffff;
  }

  .my_button {
    float: right;
    display: inline-block;
    width: 70px;
    text-align: center;
    padding: 10px;
    background-color: #000000;
    color: #fff;
    text-decoration: none;
    border-radius: 15px;
    font-family: 'Nanum Gothic', sans-serif;
  }

  </style>
  <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic" rel="stylesheet">
  <link rel="stylesheet" href="/css/bootstrap.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>

</head>

<body>
  <?php include("print_navigation.php"); ?>

  <!--  Content -->
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <?php setCategoryList($selectedListItem);?>
      </div>

      <div class="col-lg-9 contentWindow">
        <?php setProductImgsSlide($product_index); ?>
        <?php setContents($product_index); ?>

        <div class="card card-outline-secondary my-4">
          <div class="card-header korean">
            <h5 class="text-primary">댓글목록</h5>
          </div>

          <div class="card-body korean">
            <?php setComments($product_index, $user_id); ?>

            <div id="commentAddPoint"></div>

            <div class="my-4">
              <h5 class="korean">댓글입력</h5>
              <textarea class="form-control-lg korean form-myControl_85" name="commentContent" rows='1'  placeholder="내용을 입력하세요" id="content" value="" ></textarea>
              <input type="hidden" name="product_index" id="product_index" value="<?php echo $product_index; ?>" />
              <input type="hidden" name="session_id" id="session_id" value="<?php echo $user_id; ?>" />
              <button type="button" onclick="commentRegister();"id="btn_parent_comment_register" class="btn btn-outline-primary btn-lg korean form-myControl_15">등록</button>
            </div>

          </div>
        </div>

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
  </footer>

  <!-- 삭제 다이얼로그 -->
  <div class="modal" id="productDeleteModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="korean">정말 삭제하시겠습니까?</h4>
        </div>
        <div class="modal-footer">
          <button type="button" id="modalDelete" class="btn btn-outline-danger korean">삭제</button>
          <button type="button" id="modalCancel" class="btn btn-secondary korean" data-dismiss="modal">취소</button>
        </div>
      </div>
    </div>
  </div>

<script>
var productModal = document.getElementById('productDeleteModal');
var productModalOpenBtn = document.getElementById("productDeleteBtn");
var productRealDeleteBtn = document.getElementById("modalDelete");
var cancelBtn = document.getElementById("modalCancel");
var span = document.getElementsByClassName("close")[0];

// 댓글등록
function commentRegister(){
  $.ajax({
    url:'./DB/Comment_register.php',
    type:'post',
    data:{
      session_id: $("#session_id").val(),
      content: $("#content").val(),
      product_index: $("#product_index").val()
    },
    dataType: 'json',
    success: function(result){
      console.log("성공 Result : "+result);
      // 댓글 번호, 아이디, 내용, 등록시간을 리턴받아서 뿌려준다.
      addCommentInTag(result.commentIndex, result.commentWriterID ,result.content, result.date);
      $("#content").val("");
    }
  })
}

// 댓글삭제.
function commentDelete(commentIndex, commentWriterID){
  var commentID = "comment"+commentIndex + commentWriterID;

  $(document).on("click", '#'+commentID, function(){
    var check = confirm("댓글을 삭제하시겠습니까?");

    if(check){
      console.log(""+commentID + "   " + $("#session_id").val());
      $.ajax({
        url:'./DB/Comment_delete.php',
        type:'post',
        data:{
          product_index: $("#product_index").val(),
          comment_index: commentIndex
        },
        dataType: 'json',
        success: function(result){
          // 태그에서 댓글을 빼준다. 대댓글이 달려있다면 내용수정
          if(result.response == "Delete"){
            deleteCommentInTag(commentIndex);
          }
          else{
            updateCommentInTag("comment", commentIndex, "--- 삭제된 댓글입니다 ---");
          }
        },
        error: function(errormsg){
          console.log(arguments);
          console.log(errormsg);
        }
      })
    }
    else{
      return;
    }
  });
}

// 대댓글삭제.
function childCommentDelete(parentIndex, childIndex, commentWriterID){
  var commentID = "child"+childIndex + commentWriterID;
  console.log("parentIndex -->"+parentIndex);
  console.log("childIndex -->"+childIndex);
  $(document).on("click", '#'+commentID, function(){
    var check = confirm("댓글을 삭제하시겠습니까?");

    if(check){
      $.ajax({
        url:'./DB/ChildComment_delete.php',
        type:'post',
        data:{
          product_index: $("#product_index").val(),
          parent_Index: parentIndex,
          child_Index: childIndex
        },
        dataType: 'json',
        success: function(result){
          // 태그에서 댓글을 빼준다.
          if(result.response == "OnlyChild"){
            deleteChildCommentInTag(childIndex);
          }
          else{
            deleteChildCommentInTag(childIndex);
            deleteCommentInTag(parentIndex);
          }
        },
        error: function(errormsg){
          console.log(arguments);
          console.log(errormsg);
        }
      })
    }
    else{
      return;
    }
  });
}

// 댓글 수정창 보여주기
function showModifyWindow(isParent, commentIndex, content){
  if(isParent)  $("<div class='my-4' id='commentModifyWindow' style='padding-left:1.3rem;'>").appendTo('#comment'+commentIndex);
  else          $("<div class='my-4' id='commentModifyWindow' style='padding-left:1.3rem;'>").appendTo('#child'+commentIndex);

  $('#commentModifyWindow').append("<h5 class='korean'>댓글수정</h5>");
  $('#commentModifyWindow').append("<div id='modifyRow' class='row'>");
  $('#modifyRow').append("<div id='modifyCol' class='col-md-12'>");
  $('#modifyCol').append("<textarea id='commentModifyTextarea' class='form-control-lg korean form-myControl_85' rows='1' type='text' placeholder='내용을 입력하세요'>"+content+"</textarea>");
  $('#modifyCol').append("<button type='button' id='btn_commentModify' onclick='commentModify("+isParent+", "+commentIndex+");' class='btn btn-outline-primary btn-lg korean form-myControl_15'>수정</button><hr>");
}

// 대댓글 입력창 보여주기
function showReplyWindow(commentIndex){
  $("<div class='my-4' id='childCommentInputWindow' style='padding-left:1.3rem;'>").appendTo('#comment'+commentIndex);
  $('#childCommentInputWindow').append("<h5 class='korean'>답글입력</h5>");
  $('#childCommentInputWindow').append("<div id='replyRow' class='row'>");
  $('#replyRow').append("<div id='replyCol' class='col-md-12'>");
  $('#replyCol').append("<textarea id='childCommentTextarea' class='form-control-lg korean form-myControl_85' rows='1' type='text' placeholder='내용을 입력하세요'  ></textarea>");
  $('#replyCol').append("<button type='button' id='btn_childCommentRegister' onclick='childCommentRegister("+commentIndex+");' class='btn btn-outline-primary btn-lg korean form-myControl_15'>등록</button><hr>");
}


// 댓글,대댓글 내용 디비에서 수정하기
function commentModify(isParent, commentIndex){
  $(document).on("click", '#btn_commentModify', function(){
    $.ajax({
      url:'./DB/Comment_update.php',
      type:'post',
      data:{
        isParent: isParent,
        commentIndex: commentIndex,
        content: $("#commentModifyTextarea").val()
      },
      dataType: 'json',
      success: function(result){
        // 부모자식여부, 댓글번호, 수정된내용을 리턴받는다.
        updateCommentInTag(result.type, result.commentIndex, result.modifiedContent);
        $("#commentModifyTextarea").val("");
        $("#commentModifyWindow").remove();
        $(document).off("click", '#btn_commentModify');
      },error: function(errormsg){
        console.log(arguments);
        console.log(errormsg);
      }
    })
  });
}

// 대댓글 내용 디비에 등록하기
function childCommentRegister(commentIndex){
  $(document).on("click", '#btn_childCommentRegister', function(){
    $.ajax({
      url:'./DB/ChildComment_register.php',
      type:'post',
      data:{
        commentIndex: commentIndex,
        content: $("#childCommentTextarea").val(),
        product_index: $("#product_index").val()
      },
      dataType: 'json',
      success: function(result){
        // 댓글 번호, 아이디, 내용, 등록시간을 리턴받아서 뿌려준다.
        addChildCommentInTag(result.parentIndex, result.currentChildIndex, result.lastChildIndex, result.commentWriterID ,result.content, result.date);
        $("#childCommentTextarea").val("");
        $("#childCommentInputWindow").remove();
        $(document).off("click", '#btn_childCommentRegister');
      },error: function(errormsg){
        console.log(arguments);
        console.log(errormsg);
      }
    })
  });
}


// 태그에 대댓글 실시 추가
function addChildCommentInTag(parentIndex, currentChildIndex, lastChildIndex, writer_id, content, registerDate){
  var commentID = currentChildIndex + writer_id;

  // 첫 대댓글이라면
  if(lastChildIndex == currentChildIndex) {
    $("#comment"+parentIndex).after("<div id=child"+currentChildIndex+">");
  }
  else{
    $("#child"+lastChildIndex).after("<div id=child"+currentChildIndex+">");
  }

  $('#child'+currentChildIndex).append("<div id='childWrap"+currentChildIndex+"' style='padding-left:30px;'>");
  $('#childWrap'+currentChildIndex).append("<span class='text-info' style='font-size:large;'>&#8618;&nbsp;"+writer_id+"</span>");
  if(<?php $ownerID = getProductOwnerID($product_index); echo "\"$ownerID\""; ?> == writer_id){
    $('#childWrap'+currentChildIndex).append("<span class='badge badge-pill badge-danger'style='margin-left:10px; '>작성자</span>");
  }
  $('#childWrap'+currentChildIndex).append("<button type='button' id=\"child"+commentID+"\" onclick='childCommentDelete("+parentIndex+", "+currentChildIndex+", \""+writer_id+"\");' class='btn btn-sm btn-outline-secondary' style='float:right;font-size:small;padding:4px; padding-left:6px;padding-right:6px;margin-bottom:6px;margin-left:15px;'>삭제</button>");
  $('#childWrap'+currentChildIndex).append("<button type='button' id='btn_commentModify' onclick='showModifyWindow(0, "+currentChildIndex+", \""+content+"\");' class='btn btn-sm btn-outline-secondary' style='float:right;font-size:small;padding:4px; padding-left:6px;padding-right:6px;margin-bottom:6px;margin-left:15px;'>수정</button>");
  $('#childWrap'+currentChildIndex).append("<br><br><span style='float:right;'>"+registerDate+"</span>");
  $('#childWrap'+currentChildIndex).append("<h6 style='padding-left:35px;'><pre>"+content+"</pre></h6><hr>");
}

// 태그에 댓글 실시간 추가
function addCommentInTag(commentIndex, commentWriterID, content, date){
  var commentID = commentIndex + commentWriterID;

  $("#commentAddPoint").append("<div id=comment"+commentIndex+">");
  $('#comment'+commentIndex).append("<span class='text-info' style='font-size:large;'>"+commentWriterID+"</span>");
  if(<?php $ownerID = getProductOwnerID($product_index); echo "\"$ownerID\""; ?> == commentWriterID){
    $('#comment'+commentIndex).append("<span class='badge badge-pill badge-danger'style='margin-left:10px; '>작성자</span>");
  }
  $('#comment'+commentIndex).append("<button type='button' onclick='showReplyWindow("+commentIndex+");' class='btn btn-sm btn-outline-secondary' style='font-size:small;padding:4px; padding-left:6px;padding-right:6px;margin-left:15px;'>답글</button>");
  $('#comment'+commentIndex).append("<button type='button' id=\"comment"+commentID+"\" onclick='commentDelete("+commentIndex+", \""+commentWriterID+"\");' class='btn btn-sm btn-outline-secondary' style='float:right;font-size:small;padding:4px; padding-left:6px;padding-right:6px;margin-bottom:6px;margin-left:15px;'>삭제</button>");
  $('#comment'+commentIndex).append("<button type='button' id='btn_commentModify' onclick='showModifyWindow(1, "+commentIndex+", \""+content+"\");' class='btn btn-sm btn-outline-secondary' style='float:right;font-size:small;padding:4px; padding-left:6px;padding-right:6px;margin-bottom:6px;margin-left:15px;'>수정</button>");
  $('#comment'+commentIndex).append("<br><br><span style='float:right;'>"+date+"</span>");
  $('#comment'+commentIndex).append("<h6 style='padding-left:10px;'><pre>"+content+"</pre></h6><hr>");
}

// 태그에서 댓글 삭제
function deleteCommentInTag(commentIndex){
  $('#comment'+commentIndex).remove();
}

// 태그에서 대댓글 삭제
function deleteChildCommentInTag(childIndex){
  $('#child'+childIndex).remove();
}

// 태그에 수정내용을 적용한다.
function updateCommentInTag(type, commentIndex, content){
  // type+index 조합으로 태그의 위치를 찾고
  // 위치에 맞는 <h6> 태그의 내용을 전달받은 content 로 바꿔준다.
  // type --> comment or child 로 입력받는다.
  var index = type + commentIndex + " h6";
  if(content == "--- 삭제된 댓글입니다 ---"){
    $("div#"+index).filter(':first').text(content).css('color' ,'red');
  }
  else{
    $("div#"+index).filter(':first').text(content);
  }

}

productModalOpenBtn.onclick = function() {
  productModal.style.display = "block";
}

productRealDeleteBtn.onclick = function() {
  var data = new FormData();
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "./DB/Product_delete.php");
  xhr.onload = function(e) {
      if(this.status == 200) {
          console.log("Result : "+e.currentTarget.responseText);
          page_replace();
      }
  }
  data.append("product_index", <?php echo "$product_index"; ?>);
  xhr.send(data);

}

cancelBtn.onclick = function(){
  productModal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == productModal) {  productModal.style.display = "none"; }
}

span.onclick = function() {
  productModal.style.display = "none";
}

function page_replace(){
  location.replace("home.php");
}
</script>
</body>
</html>
