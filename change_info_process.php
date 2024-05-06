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

//post로 회원가입 값 받기 
if($_POST['user_name']){ // 유저 정보 변경의 경우 
$name = $_POST['user_name'];
$email = $_POST['user_mail'];
$id = $_POST['user_id'];

echo 'POST 성공<br>';
//sql 준비
$updateSql = "UPDATE user SET name = '$name', email = '$email' WHERE id = '$id'";//bind params;

$_SESSION['login_name'] = $name;

mysqli_query($mysqli,$updateSql);

header("location: " ."mypage.php");// 리다이렉트 마이페이지로 



mysqli_close($mysqli);



exit;
}

?>
