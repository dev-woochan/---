
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 수정하기</title>
    <!-- include libraries(jQuery, bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <!-- include summernote css/js -->
    <link rel="stylesheet" href="../resources/summernote-bs4.css" type="text/css">
    <script src="../resources/summernote-bs4.js"></script>
    <link rel="stylesheet" href="write.css" type="text/css">
    <!-- datepicker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


</head>

<body>
<?php
include '../dbconfig.php';

$post_id = $_POST["post_id"];
// //포스트로 클릭시에 게시글 id만 받아올 예정 get안쓴 이유는 수정은 본인만 할수있어야하니까

$sql = "SELECT usPost.create_time,usPost.symbol ,title, user.name, content, watchCnt, likeCnt, riseSelect, goalDate, postPrice
FROM usPost  
JOIN user ON usPost.user_id = user.id
WHERE usPost.id = ($post_id) ";

$result = mysqli_query($mysqli,$sql);
$result_array = mysqli_fetch_array($result);

session_start(); //세션 시동걸어주기 

if(isset($_SESSION['login_id'])){ //세션에 아이디가 있어야댐
    $user_name = $_SESSION['login_name'] ;
    $user_id = $_SESSION['login_id'];
} else{
    $user_name  ="Guest"; //로그인 값없을시 안전하게 user_name사용하기위해서 예외처리해줌 
}
//아이디 불러오기 끝

?>
    <header>
        <div class="header_inner">
            <div class="logo_area">
                <div class="logo_area">
                    <a href="/risingproject/home.php">
                        <div class="logo"><img src="/risingproject/resources/rise_logo.png" alt="오른다 로고" width="300px"
                                height="120px" alt="오른다로고"></div>
                    </a>

                </div>
            </div>
            <div class="right_area">
            <?php 
                if($user_name != "Guest"){
                    echo $user_name , " 님 환영합니다 ". '<form action="http://192.168.101.129/risingproject/user/mypage.php" method="POST">
                    <input type="submit" value="마이페이지">
                    </form>
                    <form action="/risingproject/user/logout_process.php" method="POST">
                    <input type="submit" value="로그아웃">
                    </form>'
                    ;
                }else{
                    echo '
                    <a href="/risingproject/user/login.php">
                    <span class="button">
                        로그인
                    </span>
                </a>
                <a href="/risingproject/user/signin.html">
                    <span class="button">
                        회원가입
                    </span>
                </a>
                    ';
                }
                ?>
            </div>
        </div>
        </div>

        <nav class="gnb">
            <div class="gnb_inner">
                <div class="nav_board">
                    <a href="/risingproject/home.php">홈</a>
                </div>
                <div class="nav_board">
                    <a href="/risingproject/board_koreaStock.php">국내주식</a>
                </div>
                <div class="nav_board">
                    <a href="/risingproject/board_usStock.php">미국주식</a>
                </div>
                <div class="nav_board">
                <a href = "/risingproject/board_free.php">자유게시판</a>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <h2>
            글 수정
        </h2>
        <form action="usPost_update_process.php" class="write_form" method="post" id="write_form">
            <div class="form_wrap">

                <div class="write_stock">
                    <label>제목</label><input type="text" class="write_textarea" name="title" id="titleInput"
                    value="<?php echo $result_array['title'] ?>">
                </div>
                <div class="write_stock">
                    <label>종목명</label><input type="text" class="write_textarea" name="symbol" 
                    value="<?php echo $result_array['symbol'] ?>">
                </div>
                <div class="write_stock">
                    <label>포스팅가격</label> <span ><?php echo $result_array['postPrice']."원" ?></span>
                    <label>목표일</label> <input type="text" name="goalDate" id="datepicker" class="datepicker"
                        placeholder="<?php echo $result_array['goalDate'] ?>" autocomplete="off">
                </div>
                <div class=" write_button_left">
                    
                    <input type="button" value="오른다" id="button_increase" class="button_select">
                    <input type="button" value="내린다" id="button_decrease" class="button_select">
                    <input type="hidden" name="riseSelect" id="select" value="<?php echo $result_array['riseSelect'] ?>">
                    <input type="hidden" name="create_time" id="date">
                    <input type="hidden" name="id" value = "<?php echo $post_id?>">
                </div>

                <textarea class="main_text" name="editordata" id="summernote">
                <?php echo $result_array['content'] ?>
                </textarea>

                <div class="write_button_right">
                    <input type="button" value="취소" onclick="history.back(-1)">
                    <input type="submit" value="수정" id="submit">
                </div>
            </div>
        </form>

    </main>
    <footer>
        <div>
            Copyright 2024. KimChanWoo all rights reserved.
        </div>
    </footer>

    <script>
        $("#datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            minDate: new Date(),
            maxDate: "+1M"
        });
        $(document).ready(function () {
            // 3️⃣ datepicker를 적용할 input 요소에 대해 datepicker를 호출
            $("#datepicker").datepicker();
        });
    </script>
    <script src="update.js"></script>
</body>

</html>