<?php
//db 실행 
include '../dbconfig.php';

session_start(); //세션 시동걸어주기 

if(isset($_SESSION['login_id'])){ //세션에 아이디가 있어야댐
    $user_name = $_SESSION['login_name'] ;
    $user_id = $_SESSION['login_id'];
} else{
    $user_name  ="Guest"; //로그인 값없을시 안전하게 user_name사용하기위해서 예외처리해줌 
}
//아이디 불러오기 끝


$loadIdSql = "SELECT user.password
FROM user
WHERE id = '$user_id'";

$loginResult = mysqli_query($mysqli,$loadIdSql);
$login_array = mysqli_fetch_array($loginResult);
if($login_array['password']){
    $user_pass = $login_array['password'] ;
}

    $pass = $_POST['password'];
    $newPass = $_POST['new_password'];
    $newPassConfrim = $_POST['new_password_confirm'];

    if($user_pass == $_POST['password']){//입력한값이 맞은경우 
        if($newPass == $newPassConfrim){ //둘다 맞은경우 
            $updatePass = "UPDATE user SET password = '$newPass' WHERE id = '$user_id' ";
 
            mysqli_query($mysqli,$updatePass);

            echo "<script>window.parent.location.href='http://192.168.101.129/risingproject/user/mypage.php';</script>";

            echo "<script>window.alert('비밀번호가 변경되었습니다'); </script>";

        }else{
            echo "<script>window.parent.alert('비밀번호가 일치하지 않습니다'); </script>";
        }
    }else{
        echo "<script>window.parent.alert('현재 비밀번호가 틀립니다'); </script>";

    } 

    ?>