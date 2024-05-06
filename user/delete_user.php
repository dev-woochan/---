<?php
include '../dbconfig.php';

$passwordinput = $_POST['password'];

session_start(); //세션 시동걸어주기 

if(isset($_SESSION['login_id'])){ //세션에 아이디가 있어야댐
    $user_name = $_SESSION['login_name'] ;
    $login_id = $_SESSION['login_id'];
} else{
    $user_name  ="Guest"; //로그인 값없을시 안전하게 user_name사용하기위해서 예외처리해줌 
}
//아이디 불러오기 끝

$loadUser = "SELECT id, password FROM user WHERE id = '$login_id'"; //세션에 있는 아이디로 회원정보 로드 
$result = mysqli_query($mysqli,$loadUser);
$result_array = mysqli_fetch_array($result);
$password = $result_array['password'];

if($password == $passwordinput){
    $update_postId = "UPDATE koPost SET user_id = 11 WHERE user_id ='$login_id'"; // 탈퇴한 회원들이라는 아이디를 만들어 탈퇴한회원은 다 탈퇴한회원으로 나오도록 변경
$update_commentId = "UPDATE comment SET user_id = 11 WHERE user_id ='$login_id'";
mysqli_query($mysqli,$update_commentId);
mysqli_query($mysqli,$update_postId);
//댓글 , 포스팅 아이디값 변경 

$deletSql = "DELETE FROM user WHERE user.id = '$login_id'";
mysqli_query($mysqli,$deletSql);
// 계정 삭제 
$deletLogin = "DELETE FROM login";
mysqli_query($mysqli,$deletLogin);
// 로그인된 값 로그아웃
echo "<script>window.alert('회원탈퇴 되었습니다'); window.parent.location.href='http://192.168.101.129/risingproject/home.php';</script>";
//회원탈퇴후 홈으로 이동시키기위해 window.parent에 접근하였다 ! 


mysqli_close($mysqli);
}else{
    echo "<script>window.alert('비밀번호를 확인해주세요'); window.history.back();</script>";
    
}





?>