<?php
include '../dbconfig.php';

session_start(); //세션 시동걸어주기 

if(isset($_SESSION['login_id'])){ //세션에 아이디가 있어야댐
    $user_name = $_SESSION['login_name'] ;
    $user_id = $_SESSION['login_id'];
} else{
    $user_name  ="Guest"; //로그인 값없을시 안전하게 user_name사용하기위해서 예외처리해줌 
}
//아이디 불러오기 끝

//유저정보 불러오기 
$loadUserSql = "SELECT user.id, user.create_time, user.name, user.password, user.email, user.postCnt, user.successCnt
FROM user
WHERE user.id = '$user_id'";

$result = mysqli_query($mysqli,$loadUserSql);
$user_info = mysqli_fetch_array($result);


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF8">
    <link rel="stylesheet" href="mypage.css" type="text/css">
    <title>
    마이페이지 
</title>
</head>

<body>
    <header>
        <div class="header_inner">
            <div class="logo_area">
                <a href="/risingproject/home.php">
                    <div class="logo"><img src="../resources/rise_logo.png" alt="오른다 로고" width="300px" height ="120px" alt="오른다로고"></div>
                </a>

            </div>
            <div class="right_area">
            <?php 
                if($user_name != "Guest"){
                    echo $user_name , " 님 환영합니다 ". '<form action="mypage.php" method="POST">
                    <input type="submit" value="마이페이지">
                    </form>
                    <form action="/risingproject/user/logout_process.php" method="POST">
                    <input type="submit" value="로그아웃">
                    </form>'
                    ;
                }else{
                    echo '
                    <a href="/risingproject/user/login.php">
                    <span class="button">
                        로그인
                    </span>
                </a>
                <a href="/risingproject/user/signin.html">
                    <span class="button">
                        회원가입
                    </span>
                </a>
                    ';
                }
                ?>
                
            </div>
        </div>
        </div>

        <nav class="gnb">
            <div class="gnb_inner">
                <div class="nav_board">
                    <a href="/risingproject/home.php">홈</a>
                </div>
                <div class="nav_board">
                    <a href ="/risingproject/board_koreaStock.php">국내주식</a>
                </div>
                <div class="nav_board">
                    <a href ="/risingproject/board_usStock.php">미국주식</a>
                </div>
                <div class="nav_board">
                <a href = "/risingproject/board_free.php">자유게시판</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <h1>
            회원정보 
        </h1>
        <div class = "mypage_wrap">
            <section class = "info_list">
                <div class = "user_info">
                    닉네임 : <?php echo $user_info['name']?>
            </div>
            <div class = "user_info">
                이메일 : <?php echo $user_info['email']?>
                </div>
            <div class = "user_info">
                가입일 : <?php echo $user_info['create_time']?>
                </div>
            <div class = "user_info">
                게시글 수 : <?php echo $user_info['postCnt']?>
                </div>
            </section>
            <div class = "showPost">
             <span style="font-weight:bolder;">작성한글 보러가기 </span>
             <div><a class="board_list">국내주식</a></div>
             <div><a class="board_list">미국주식</a></div>
             <div><a class="board_list">자유게시판</a></div>
            </div >
           <div class = "user_info">
            <button class="button">
            <a href ="change_info.php">정보변경</a>
            </button>
            <button class="button" id="change_btn">
            비밀번호 변경
            </button>
            <button class="button" id="delete_btn">
            회원탈퇴
            </button>
            </div>
            <div id="confirmPassword"></div>
            
            </div>
    </main>
    <script src="mypage.js"></script>
</body>
<footer>
    Copyright 2024. KimChanWoo all rights reserved.
</footer>

</html>