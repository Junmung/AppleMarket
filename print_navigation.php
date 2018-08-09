<?php
@session_start();
function printMemberCase(){
  if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    echo "<a class='nav-link korean' href=''><strong>$user_id 님</strong><span class='sr-only'>(current)</span></a>";

    echo "</li>";
    echo "<li class='nav-item'>";
      echo "<a class='nav-link' href='/logout.php' >logout</a>";
    echo "</li>";
    echo "<li class='nav-item'>";
      echo "<a class='nav-link' href='/mypage.php'>mypage</a>";
    echo "</li>";
    echo "<li class='nav-item'>";
      echo "<a class='nav-link korean' href='/register.php'>제품등록</a>";
  }
  else{
    $user_id = "비회원";
    echo "<a class='nav-link korean' href=''><strong>비회원</strong><span class='sr-only'>(current)</span></a>";
    echo "</li>";
    echo "<li class='nav-item'>";
      echo "<a class='nav-link' href='/join.php'>Join</a>";
    echo "</li>";
    echo "<li class='nav-item'>";
      echo "<a class='nav-link' href='/login.php'>Login</a>";
  }
}


print(
  "<nav class='navbar navbar-expand-lg navbar-dark bg-primary'>
  <a class='navbar-brand' href='/home.php' ><strong>&#63743; Apple Market</strong></a>
  <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarColor01' aria-controls='navbarColor01' aria-expanded='false' aria-label='Toggle navigation' style=''>
    <span class='navbar-toggler-icon'></span>
  </button>

  <div class='collapse navbar-collapse' id='navbarColor01'>
    <ul class='navbar-nav mr-auto'>
      <li class='nav-item'>
        <a class='nav-link' href='/home.php'>Home </a>
      </li>
      <li class='nav-item active'>"
);

printMemberCase();

print("
      </li>
      </ul>
    <form class='form-inline my-2 my-lg-0' action='home.php' method='get'>
      <input class='form-control mr-sm-2 korean' type='text' name='searchItem' placeholder='물품검색'>
      <button class='btn btn-secondary my-2 my-sm-0' type='submit'>Search</button>
    </form>
  </div>
</nav>"
);

 ?>
