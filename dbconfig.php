    <?php
    //db연동하는 코드 db를 실행시켜준다 close는 다른코드에서 따로해주어야함
    // 보통 require로  불러온다. 
    $host = "localhost";
    $user = "woochan";
    $pw = "dusqhd1djr!";
    $dbName = "rising_db";

    $mysqli = mysqli_connect($host,$user,$pw,$dbName);
    if($mysqli){
    }else{
        die('연경실패' .mysqli_connect_error());
    }
?>

