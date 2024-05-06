<?php
// 세션에서 로그인 아이디 name을 삭제한다 

session_start(); //세션 시동걸어주기 

session_unset(); // 세션 변수 제거 

session_destroy(); //모두 삭제 

echo "<script> alert('로그아웃 되었습니다');</script>";

echo "<script> location.href = 'http://192.168.101.129/risingproject/home.php';</script>"
//게시판으로 이동하기 

?>