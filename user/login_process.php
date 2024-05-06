
<?php
require '../dbconfig.php';
// mariadb연결 

session_start();
//세션시작 

$login_email = $_POST['email'];
$login_pass = $_POST['pw'];
$saveIdCheck = $_POST['check_box']; // 체크박스 체크여부임 
//post로 id, pass 값 받기 

$sql = "SELECT * FROM user WHERE email = '$login_email'";
// 로그인 한 아이디의 행 찾음 

$result = $mysqli->query($sql);


if($result->num_rows > 0){ //로그인할 아이디가 있는경우 
    //배열형태로 result에 저장 
    $row = $result->fetch_array();
    $load_email = $row['email'];
    $load_pass = $row['password'];
    $id = $row['id'];
    $name = $row['name'];


    if($login_pass == $load_pass){
        // //그리고 로드된 pass,입력된 pass값이 일치해야함 
        // $sql = "INSERT INTO login (id) VALUES ('$id')";
        // //로드된 값을 login db에 저장 => 로그인 한척하기위한것으로 사용
        // $mysqli->query($sql);

        $_SESSION['login_id'] = $id; // 세션에 현재 로그인된 id 를 저장 
        $_SESSION['login_name'] = $name; // 이름도 자주쓰므로 저장

        if($saveIdCheck == "on"){ //아이디 저장이 체크인 경우 
            setcookie("savedEmail",$load_email,time() + 3000); // 저장된아이디에 이메일 저장 
            echo "쿠키성공ㅋㅋ";
        }else{
            setcookie("savedEmail",$load_email,time() - 3000); // 체크 해제면 쿠키 만료시킴 
        }
        echo "<script type='text/javascript'>
        alert('로그인 성공');
        window.location.replace('http://192.168.101.129/risingproject/home.php');
        </script>";
        //성공시 replace로 이동 
    }else{
        //그외엔 모두 실패처리 
        echo "<script type='text/javascript'>
        alert('아이디 패스워드를 확인해주세요');
        history.go(-1);
        </script>";
    }


}else{//로그인할 아이디가 없는경우
    echo "<script type='text/javascript'>
    alert('아이디 패스워드를 확인해주세요');
    history.go(-1);
    </script>";
}




$mysqli->close();

?>

