<?php

include '../dbconfig.php';
//db호출 myslqi

header('Content-Type: application/json; charset=UTF-8');

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

session_start(); //세션 연동

if (isset($_SESSION['login_id'])) { //세션에 아이디가 있어야댐
    $user_name = $_SESSION['login_name'];
    $user_id = $_SESSION['login_id'];
} else {
    $user_name = "Guest"; //로그인 값없을시 안전하게 user_name사용하기위해서 예외처리해줌 
}
//아이디 불러오기 끝

$content = $data['content'];
$create_time = date("Y-m-d H:i:s");
$parentPostId = $data["parentPostId"];
$depth = $data["depth"];
$deleted = 0;
$likes = 0;

if (isset($data['parent_comment_id'])) { //대댓글의 경우!
    $parentCommentId = $data["parent_comment_id"];

    // 대댓글의 경우 orderNumber 및 replyOrder를 가져와야 함
    $parentSql = "SELECT id, orderNumber FROM usComment WHERE id = ?";
    $parentCntSql = $mysqli->prepare($parentSql);
    $parentCntSql->bind_param("i", $parentCommentId);
    $parentCntSql->execute();
    $cntResult = $parentCntSql->get_result();
    $cnt = 1;
    $orderNumber = 0; // orderNumber 초기화
    $replyOrder = 0; // replyOrder 초기화

    if ($cntResult->num_rows > 0) {
        $cnt++;
        $row = $cntResult->fetch_assoc(); // 결과 행 가져오기
        $orderNumber = $row['orderNumber']; //부모아이디의 orderNumber 가져오기 
    }

    $replyOrder = $cnt;

    $stmt = $mysqli->prepare("INSERT INTO usComment (create_time, content, parentPostId, orderNumber, depth, user_id, deleted, parentCommentId, likes, replyOrder ) 
VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssiiiiiiii", $create_time, $content, $parentPostId, $orderNumber, $depth, $user_id, $deleted, $parentCommentId, $likes, $replyOrder);

    $result = $stmt->execute();
    if ($result) {
        // 삽입된 행의 ID 값을 얻습니다.
        $commentId = $mysqli->insert_id;
    } else {
        echo "Error: " . $stmt->error;
    }

    $query = "
    SELECT u.name
    FROM usComment c
    JOIN user u ON c.user_id = u.id
    WHERE c.id = ?
";

    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $parentCommentId);
    $statement->execute();
    $statement->bind_result($parentUserName);
    $statement->fetch();

    if ($result) { //성공한경우 
        $response = array('valid' => true, 'content' => $content, 'parentName' => $parentUserName, 'time' => $create_time, 'user_name' => $user_name, 'id' => $commentId, 'orderNumber' => $orderNumber);
    } else {
        $response = array('valid' => false);
    }

    echo json_encode($response); //결과값 반환 트루이면 추가하고  false면 댓글추가를 안하기 위함 

    $mysqli->close();

} else { //일반 댓글의 경우 
    $orderNumberSql = "SELECT MAX(orderNumber) AS max_value FROM usComment WHERE parentPostId = $parentPostId";
    $orderResult = mysqli_query($mysqli, $orderNumberSql);
    while ($row = $orderResult->fetch_assoc()) {
        if ($row['max_value'] == NULL) {
            $orderNumber = 1;
        } else {
            $orderNumber = $row['max_value'] + 1;
        }
    } //orderNUmber는 댓글의 순서이고 부모 댓글만 가지고있다, 가장큰 숫자로 설정됨 댓글에서는 orderNumber를 parentid로 확인함 


    $stmt = $mysqli->prepare("INSERT INTO usComment (create_time, content, parentPostId, orderNumber, depth, user_id, deleted, likes) 
VALUE (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssiiiiii", $create_time, $content, $parentPostId, $orderNumber, $depth, $user_id, $deleted, $likes);

    $result = $stmt->execute();

    if ($result) { //성공한경우 
        $comment_id = $stmt->insert_id;
        $response = array('valid' => true, 'orderNumber' => $orderNumber, 'content' => $content, 'time' => $create_time, 'user_name' => $user_name, 'id' => $comment_id);
    } else {
        $response = array('valid' => false);
    }

    echo json_encode($response); //결과값 반환 트루이면 추가하고  false면 댓글추가를 안하기 위함 

    $mysqli->close();
}

?>