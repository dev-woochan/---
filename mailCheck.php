<?php
include '../dbconfig.php';

header('Content-Type: application/json; charset=UTF-8');

$json_data = file_get_contents('php://input');

$mail = json_decode($json_data)->mail; //json에서 이메일 주소 추출
 
$sql = "SELECT email FROM user WHERE email = '$mail'";

$result = $mysqli->query($sql);


if($result->num_rows > 0){
   $response = array('valid' => false); //중복이 있을때
}else {
    $response = array('valid' => true); //중복이 없을때
}

echo json_encode($response); //결과를 json형식으로 변환 


$mysqli->close();

?>