<?php
include "../dbconfig.php";

session_start(); //세션 시동걸어주기 

if (isset($_SESSION['login_id'])) { //세션에 아이디가 있어야댐
    $user_name = $_SESSION['login_name'];
    $login_id = $_SESSION['login_id'];
} else {
    $user_name = "Guest"; //로그인 값없을시 안전하게 user_name사용하기위해서 예외처리해줌 
}
//아이디 불러오기 끝



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원정보 변경</title>
    <link rel="stylesheet" href="signin.css" type="text/css">
</head>

<body>
    <main>
        <div style="margin-bottom : 30px;">
            회원정보 변경
        </div>

        <form action="change_info_process.php" method="post">
            <div class="form_list">
                <label>이름</label>
                <input type="text" placeholder="닉네임 3자이상" id="name" name="user_name" value="<?php echo $user_name ?>">
                <div id="name_check" class="check"></div>
            </div>
            <div class="form_list">
                <label>메일</label>
                <input type="text" placeholder="아이디(이메일) abc@abc.com" id="mail" name="user_mail"
                    value="<?php echo $user_eamil ?>">
                <div id="mail_check" class="check"></div>
            </div>
            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
            <div>
                <input type="submit" id="submit" value="회원정보 변경">
            </div>
        </form>

        </div>
    </main>
</body>
<script src="signin.js"></script>

</html>