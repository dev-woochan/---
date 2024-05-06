<?php
include '../dbconfig.php';

session_start();

header('Content-Type: application/json; charset=UTF-8');


$json_data = file_get_contents('php://input');// php://input은 request body의 값을 읽어옴 

$post_id = json_decode($json_data)->postId; //json에서 이메일 주소 추출

if(isset($_SESSION['login_id'])){
    $user_id = $_SESSION['login_id'];

    $sql ="SELECT * FROM post_likes WHERE post_id = $post_id AND user_id = $user_id";

    $result = mysqli_query($mysqli,$sql);
    $result_array = mysqli_fetch_array($result);

    if(isset($result_array)){ //아이디가 있음 => 이미 좋아요를 누른것임 
        $response = array('valid' => 'decrease');
        $delete = "DELETE FROM post_likes WHERE post_id = $post_id AND user_id = $user_id ";
        mysqli_query($mysqli,$delete);
    }else{//아이디가없음 좋아요 추가해야함 
        $response = array('valid' => 'increase');
        $increase = "INSERT INTO post_likes (post_id, user_id) VALUES($post_id,$user_id)";
        mysqli_query($mysqli,$increase);
    }

}else{
    $response = array('valid' => 'nologin');
}


echo json_encode($response);

$mysqli->close();


?>