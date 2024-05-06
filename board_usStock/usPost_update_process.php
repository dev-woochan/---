<?php
include '../dbconfig.php';

$post_id = $_POST["id"];
$update_title = $_POST["title"];
$update_content = $_POST["editordata"];

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
                $update_content = str_replace("blob:http://192.168.101.129/".$filename, $targetDir.$filePath, $update_content);
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

$sql = "UPDATE usPost
SET title = '$update_title', content = '$update_content'
WHERE id = ($post_id)";

mysqli_query($mysqli,$sql);

echo "<script> window.alert('수정이 완료되었습니다');
</script>";

header("Location: http://192.168.101.129/risingproject/board_usStock.php");


mysqli_close($mysqli);

?>