<?php
//db 실행 
include '../dbconfig.php';

//post로 회원가입 값 받기 
$name = $_POST['user_name'];
$email = $_POST['user_mail'];
$pass = $_POST['user_password'];
$currentTime = date("Y-m-d H:i:s");
echo $currentTime;

echo 'POST 성공<br>';
//sql 준비
$stmt = $mysqli->prepare("INSERT INTO user (create_time,name,password,email,postCnt,successCnt) VALUES (?,?,?,?,0,0)");
//bind params
echo '준비성공<br>';
$stmt->bind_param("ssss",$currentTime,$name,$pass,$email);
echo '바인드성공<br>';

// 쿼리 실행하기 
$stmt->execute();

echo '디비입력성공<br>';
$stmt->close();

header("location: " ."../home.php");

exit;

?>
