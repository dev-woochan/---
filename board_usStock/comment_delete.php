<?php
include '../dbconfig.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true); // true를 전달하여 배열로 변환

$comment_id = $data['comment_id'];
// hidden으로 commentId를 받아옴 

$checkComment = "SELECT parentCommentId, orderNumber FROM usComment WHERE id = $comment_id";
//댓글, 대댓글여부 확인을 위함 

$result = mysqli_query($mysqli, $checkComment);
$row = mysqli_fetch_array($result);
$check = $row['parentCommentId'];
$orderNumber = $row['orderNumber'];
if ($check == NULL) { //부모 댓글의 경우 
    $deleteSql = "UPDATE usComment SET deleted = 1 WHERE id = ?";
    //deleted = 1로 바꿈 
    $stmt = $mysqli->prepare($deleteSql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    //실행

    $selectSql = "SELECT COUNT(deleted) AS deleted FROM usComment WHERE orderNumber = {$orderNumber} && deleted = 0";
    $selectResult = mysqli_query($mysqli, $selectSql);
    $selectRow = mysqli_fetch_array($selectResult);
    $deletedCount = $selectRow['deleted'];
    // orderNumber로 대댓글이 있는지 없는지 조회 

    if ($deletedCount > 1) { //대댓글이 있을때 
        $response = array(
            'success' => 'true',
            'type' => 'deleted',
            'orderNumber' => $orderNumber
            //deleted는 삭제되었습니다로 표시해줄예정
        );
    } else { //대댓글이 없을때 
        $delete = "DELETE FROM usComment WHERE id = ?";
        $stmtd = $mysqli->prepare($delete);
        $stmtd->bind_param("i", $comment_id);
        $stmtd->execute();
        $stmtd->close();
        //실제로 삭제하고 삭제된 type을 반환 
        $response = array(
            'success' => 'ture',
            'type' => 'delete'
        );
    }

} else {// 대댓글의 경우
    $selectSql = "SELECT deleted, depth FROM usComment WHERE orderNumber = {$orderNumber}";
    $selectResult = mysqli_query($mysqli, $selectSql);
    $cnt = 0;
    while ($selectRow = mysqli_fetch_array($selectResult)) {
        if ($selectRow['depth'] == 0) {
            $parentdeleted = $selectRow['deleted'];
        }
        $cnt++;
    }
    //댓글 대댓글이 몇개 달려있는지 확인 

    if ($cnt > 2) { //두개 이상일시 => 그냥 삭제만 하면됨 
        $delete = "DELETE FROM usComment WHERE id = {$comment_id}";
        mysqli_query($mysqli, $delete);
        $response = array(
            'success' => 'true',
            'type' => 'delete'
        );
    } else {//두개일시 (부모,나만있음 => 부모댓글이 삭제되어있으면 다 삭제해야됨)
        if ($parentdeleted == 1) {
            //삭제되어있는경우 
            $delete = "DELETE FROM usComment WHERE orderNumber = ?";
            $stmtd = $mysqli->prepare($delete);
            $stmtd->bind_param("i", $orderNumber);
            $stmtd->execute();
            $stmtd->close();
            $response = array(
                'success' => 'true',
                'type' => 'allDelete',
                'orderNumber' => $orderNumber
            ); //모든 댓글을 삭제하고 allDelete를 반환 
        } else {
            //부모댓글 살아있는경우 나만삭제 
            $delete = "DELETE FROM usComment WHERE id = {$comment_id}";
            mysqli_query($mysqli, $delete);
            $response = array(
                'success' => 'true',
                'type' => 'delete'
            );
        }
    }
}





header('Content-Type: application/json'); // JSON 형식으로 응답한다는 헤더 설정

echo json_encode($response);

?>