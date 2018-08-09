
<?php
  function getUserID(){
    if(isset($_GET['login_id'])){
      echo "{$_GET['login_id']}";
    }
    else{
      echo "";
    }
  }

  function setLoginState(){
    if(isset($_GET['login_check'])){
      if($_GET['login_check'] == 'fail'){
        echo "<p class='korean' style='Color:red'>아이디 또는 비밀번호를 확인하세요.</p>";
      }
    }
  }
 ?>
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


  .login_{
    top: 100px;
    padding-top: 50px;
    padding-bottom: 50px;
    width: 500px;
    position: absolute;
    transform: translate(-50%, 0);
    left: 50%;
    border: 1px solid #666;
    border-radius: 15px;
    /* box-shadow:0 0 6px (0,0,0,1); */
    background-color: #ffffff;
  }

  </style>

  <link rel="stylesheet" href="/css/bootstrap.css">
</head>
<body>
  <?php
  include("print_navigation.php");
  ?>

  <div class="body_" >
    <div class="login_">
      <div class="container text-center">

        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <h1>LOGIN</h1>
            <?php setLoginState();?>
          </div>
        </div>
      </div>

      <form action="/DB/login_check.php" method="post">

        <div class="form-group">
          <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
              <label class="text-primary" style="font-size:large;" for="usr">ID</label>
              <input type="text" class="form-control" style="font-size:medium;" name="login_id" id="usr" value="<?= getUserID();?>" placeholder="아이디를 입력하세요">
            </div>

          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
              <label class="text-primary" style="font-size:large;" for="pwd">Password</label>
              <input type="password" class="form-control" style="font-size:medium;" name="password" id="pwd" placeholder="비밀번호를 입력하세요">
            </div>

          </div>
        </div>
        <br>
        <div class="form-group">
          <div class="row" >
            <div class="col-md-2"></div>

            <div class="col-md-8">
              <button type="submit" class="btn btn-primary">로그인</button>
              <a href="/join.php" class="btn btn-primary" role="button" >회원가입</a>
            </div>

          </div>
        </div>

      </form>
    </div>


  </div>

  <!-- Footer -->
  <footer class="py-5 bg-primary">
    <div class="container">
      <p class="m-0 text-center">Copyright &copy; Apple Market 2018</p>
    </div>
  </footer>

  <div id="bg"><div class="bg_img"></div></div>

  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
