<?php
//게시글 삭제를 위한 php코드 post로 게시글 id를 받아와서 게시글을 삭제한다.

include '../dbconfig.php';
//db호출 myslqi

$post_id = $_POST['post_id'];


$delete_comment = "DELETE FROM comment WHERE parentPostId = '$post_id'";
//외래키에 걸려있는 의존성때문에 댓글부터 삭제해준다 

$delete_sql = "DELETE FROM koPost WHERE koPost.id = '$post_id'";
// 그후 게시글 삭제 

mysqli_query($mysqli,$delete_comment);
mysqli_query($mysqli,$delete_sql);
// 쿼리 실행 


mysqli_close($mysqli); // sql 종료 

echo "<script> alert('삭제되었습니다.');</script>";
//삭제알림창 

header("Location:http://192.168.101.129/risingproject/board_koreaStock.php");
//게시판으로 이동하기 

?>