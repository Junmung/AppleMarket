<?php  ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <link href="https://fonts.googleapis.com/css?family=Nanum+Gothic" rel="stylesheet">
  <title>웹 제목</title>
  <style media="screen">
  .body_{
    position: relative;
    height: 1130px;
    width: 100%;
    padding-top: 250px;
    font-family: 'Nanum Gothic', sans-serif;
  }

  <?php include("backgroundFooterSet.php"); ?>

  .joinWindow{
    top: 100px;
    padding-top: 50px;
    padding-bottom: 30px;
    width: 600px;
    position: absolute;
    transform: translate(-50%, 0);
    left: 50%;
    border: 1px solid #666;
    border-radius: 15px;
    background-color: #ffffff;
  }
  </style>

  <link rel="stylesheet" href="/css/bootstrap.css">
  <script src="js/jquery.min.js"></script>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js" charset="utf-8"></script>
  <script language="javascript">

    function nullCheck(){

    }

    function validation(name, value){
      var regId = /^[a-z0-9]{3,20}$/;	// 아이디 유효성 검사식
      var regEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i; //이메일 유효성검사식
      var regPwd = /^[a-z0-9]{6,20}$/;

      if(name == 'user_id'){
        if(!value){
          $("#user_id").addClass("is-invalid");
          $("#user_id").removeClass("is-valid");
          $("#user_id_feedback").addClass("invalid-feedback");
          $("#user_id_feedback").removeClass("valid-feedback");
          $("#user_id_feedback").text("아이디를 입력해주세요");

          return false;
        }
        else{
          if(!value.match(regId)){
            // 형식이 틀린상황
            $("#user_id").addClass("is-invalid");
            $("#user_id").removeClass("is-valid");
            $("#user_id_feedback").addClass("invalid-feedback");
            $("#user_id_feedback").removeClass("valid-feedback");
            $("#user_id_feedback").text("아이디는 3자리 이상이어야합니다.");
            return false;
          }
          else{
            //  형식이 맞은 상황
            $("#user_id").addClass("is-invalid");
            $("#user_id").removeClass("is-valid");
            $("#user_id_feedback").addClass("invalid-feedback");
            $("#user_id_feedback").removeClass("valid-feedback");
            $("#user_id_feedback").text("중복체크를 눌러주세요.");
          }
        }
      }

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

    function ID_duplicateCheck(){
      var data = new FormData();
      var xhr_id = new XMLHttpRequest();
      xhr_id.open("POST", "/DB/ID_DuplicateCheck.php");
      xhr_id.onload = function(e) {
          if(this.status == 200) {
              console.log("Result : "+e.currentTarget.responseText);
              if(e.currentTarget.responseText == "Duplicate"){
                alert("중복된 아이디입니다.");
              }
              else{
                $("#user_id").addClass("is-valid");
                $("#user_id").removeClass("is-invalid");
                $("#user_id_feedback").addClass("valid-feedback");
                $("#user_id_feedback").removeClass("invalid-feedback");
                $("#user_id_feedback").text("등록가능한 아이디 입니다.");
              }
          }
      }
      data.append("user_id", $("#user_id").val());
      xhr_id.send(data);
    }

  </script>
</head>
<body>
  <?php
  include("print_navigation.php");
  ?>
  <input type="hidden" name="flag_validation" id="flag_validation" value="N" />


  <div class="body_" >

    <div class="joinWindow">
      <div class="container text-center">

        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8"> <h1>회원가입</h1> </div>
          <div class="col-md-2"></div>
        </div>

      </div>
      <br>
      <form action="/DB/User_Create.php" method="post">

        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-2"><label class="text-primary" style="font-size:large; float:left;" for="user_id"><strong>ID</strong></label></div>

        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-6">

              <input type="text" class="form-control is-invalid" style="font-size:medium;" name="user_id" id="user_id" value="" onkeyup="validation(this.name, this.value);" placeholder="아이디를 입력하세요">
              <div class="invalid-feedback korean" style="font-size:medium;" id='user_id_feedback'></div>
            </div>
            <div class="col-md-2"><button class="btn btn-info korean" style="font-size:14px;"type="button" id='idCheck' onclick="ID_duplicateCheck();">중복체크</button></div>

          </div>
        </div>

        <!-- 이메일 -->
        <div class="form-group">
          <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
              <label class="text-primary" style="font-size:large;padding-top:5px;" for="email"><strong>Email</strong></label>
              <input type="text" class="form-control is-invalid" style="font-size:medium;" name="email" id="email" value="" onkeyup="validation(this.name, this.value);" placeholder="이메일을 입력하세요">
              <div class="invalid-feedback korean" style="font-size:medium;" id='email_feedback'></div>
            </div>

            <div class="col-md-2"></div>
          </div>
        </div>

        <!-- 비밀번호 -->
        <div class="form-group">
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
              <label class="text-primary" style="font-size:large;padding-top:5px;" for="password"><strong>Password</strong></label>
              <input type="password" class="form-control is-invalid" name="password" id="password" value="" onkeyup="validation(this.name, this.value);" placeholder="비밀번호를 입력하세요">
              <div class="invalid-feedback korean" style="font-size:medium;" id='password_feedback'></div>
            </div>
            <div class="col-md-2"></div>
          </div>
        </div>

        <!-- 비밀번호 확인 -->
        <div class="form-group">
          <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
              <label class="text-primary" style="font-size:large;padding-top:5px;" for="pwdCheck"><strong>Re-Password</strong></label>
              <input type="password" class="form-control is-invalid" name="pwdCheck" id="pwdCheck" onkeyup="validation(this.name, this.value);" placeholder="비밀번호 확인">
              <div class="invalid-feedback korean" style="font-size:medium;" id='pwdCheck_feedback'></div>
            </div>

            <div class="col-md-2"></div>
          </div>
        </div>

        <br>

        <!-- 회원가입 -->
        <div class="form-group">
          <div class="row" >
            <div class="col-md-4"></div>

            <div class="col-md-4 text-center">
              <button type="submit" class="btn btn-primary" style="font-size:15px;" >회원가입</a>
            </div>

            <div class="col-md-4"></div>
          </div>
        </div>

      </form>
    </div>

  </div>

 <!-- 배경이미지  -->
  <div id="bg"><div class="bg_img"></div></div>

  <!-- Footer -->
  <footer class="py-5 bg-primary">
    <div class="container">
      <p class="m-0 text-center">Copyright &copy; Apple Market 2018</p>
    </div>
    <!-- /.container -->
  </footer>
</body>
</html>
