<?php
//작성한 게시글을 업로드하는 백엔드 부분 db호출 및 insert가 주된 내용임 
include '../dbconfig.php';
// db호출 db이름은 mysqli
session_start();

if(isset($_SESSION['login_id'])){ //세션에 아이디가 있어야댐
    $user_name = $_SESSION['login_name'] ;
    $user_id = $_SESSION['login_id'];
} else{
    $user_name  ="Guest"; //로그인 값없을시 안전하게 user_name사용하기위해서 예외처리해줌 
}
//아이디 불러오기 끝


$title = $_POST['title'];
$boardType = 'korea';
$stockName = $_POST['stockName'];
$content = $_POST['editordata'];
$goalDate = $_POST['goalDate'];
$postPrice = $_POST['postPrice'];
$create_time = date("Y-m-d H:i:s");
$riseSelect = $_POST['riseSelect'];
//게시글 post 받기

$targetDir = "image/"; //이미지 디렉토리지정 

try {
    if (isset($_FILES['files'])) {

        $files = $_FILES['files'];
    
        $countfiles = count($files['name']);
    
        for ($i = 0; $i < $countfiles; $i++) {
    
            $filename = $files['name'][$i];
            // 확장자 가져오기. 보통 사용할때는 크게 문제 없어보임.
            // 혹시 확장자가 빈문자열이면 php.ini 업로드 용량 제한 확인해볼 것.
            $extension = explode('/', $files['type'][$i])[1];
            $filePath = $filename . '.' . $extension;
    
            // 파일 업로드 성공했다면
            if (move_uploaded_file($files['tmp_name'][$i], $targetDir.$filePath)){
                // 기존 경로값을 서버의 파일 경로로 변경.
                $content = str_replace("blob:http://192.168.101.129/".$filename, $targetDir.$filePath, $content);
                // 파일 업로드 실패했다면
            } else {
                // 파일이 없어도 업로드 시도했을 때 에러를 무시하도록 설정
                if ($files['error'][$i] == UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                // 그 외의 에러는 throw를 통해 예외를 던집니다.
                throw new Exception("Error Processing Request: File upload failed", 1);
            }
        }
    } else {
        echo "no file upload";
    }
} catch (\Throwable $th) {
    // 예외가 발생하면 에러 메시지를 출력합니다.
    echo $th->getMessage();
}



$stmt = $mysqli->prepare("INSERT INTO koPost (create_time, title ,user_id, stockName, content, watchCnt, likeCnt, riseSelect, goalDate, postPrice) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
//db에는 이미지 이름을 저장하여 불러올수있게하였다.

$stockCode = 0;
$watchCnt = 0;
$likeCnt = 0;

$stmt->bind_param("ssisssissi", $create_time, $title ,$user_id , $stockName, $content, $watchCnt, $likeCnt, $riseSelect, $goalDate, $postPrice);

$stmt->execute();

$mysqli->close();

?>