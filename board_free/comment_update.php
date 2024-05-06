<?php
include '../dbconfig.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true); // true를 전달하여 배열로 변환

$comment_id = $data['comment_id'];
$comment = $data['comment'];

$updateSql = "UPDATE freeComment SET content = ? WHERE id = ?";

$stmt = $mysqli->prepare($updateSql);
$stmt->bind_param("si",$comment, $comment_id);
$stmt->execute();
$stmt->close();

// 수정된 댓글 가져오기
$selectSql = "SELECT content FROM freeComment WHERE id = ?";
$stmd = $mysqli->prepare($selectSql);
$stmd->bind_param("i", $comment_id);
$stmd->execute();
$stmd->bind_result($updated_comment);
$stmd->fetch();
$mysqli->close();

// 댓글 수정 성공 시
$response = array(
    'success' => true,
    'updatedComment' => $updated_comment // 수정된 댓글 내용
);

header('Content-Type: application/json'); // JSON 형식으로 응답한다는 헤더 설정

echo json_encode($response);

?>