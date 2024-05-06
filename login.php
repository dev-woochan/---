<!DOCTYPE html>
<html lang="kr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>오른다 로그인</title>
    <link rel="stylesheet" href="login.css" type="text/css">

</head>

<body>
    <header class="logo">
        <a href="/risingproject/home.php">
        <div class="logo"><img src="../resources/rise_logo.png" alt="오른다 로고" width="300px" height ="120px" alt="오른다로고"></div>
        </a>

    </header>
    <main>

        <div class="login">
            <div>
                로그인
            </div>
            <form action="login_process.php" method="post" class="login_form" onsubmit= "return loginCheck()" >
                <div class="login_wrap">
                    <div class="idpw_wrap">
                        <input type="text" id="email" class="email" name="email" placeholder="아이디(메일주소)" value =
                        <?php 
                        if(isset($_COOKIE['savedEmail'])){//저장된 이메일이 있을경우만!!
                            $savedEmail = $_COOKIE['savedEmail']; //변수에 이메일저장
                            echo "'$savedEmail'"; //value에 쿠키에저장된 이메일 입력
                        } 
                        ?>>
                    </div>
                    <div class="idpw_wrap">
                        <input type="password" id="pw" class="pw" name="pw" placeholder="비밀번호">
                    </div>
                    <div>
                        <input type="checkbox" name ="check_box" value = "on"
                        <?php 
                        if(isset($_COOKIE['savedEmail'])){//저장된 이메일이 있을경우만!!
                            echo "checked"; //자동로그인 자동으로 체크되도록 
                        } 
                        ?>
                        ><span>아이디 저장</span>
                    </div>
                    <div>
                        <button class="btn-login" type="submit">
                            <span>로그인</span></button>
                    </div>
            </form>

            <div>
                <div class="btn-signin">
                    <span>비밀번호찾기</span>
                    <a href="signin.html">회원가입</a>
                </div>
            
            </div>
        </div>


    </main>
    <script src="login.js"></script>

</body>
<footer>
    <div> Copyright 2024. KimChanWoo all rights reserved.

    </div>
</footer>

</html>