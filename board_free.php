<?php 
include 'dbconfig.php';

session_start(); //세션 시동걸어주기 

if(isset($_SESSION['login_id'])){ //세션에 아이디가 있어야댐
    $user_name = $_SESSION['login_name'] ;
    $user_id = $_SESSION['login_id'];
} else{
    $user_name  ="Guest"; //로그인 값없을시 안전하게 user_name사용하기위해서 예외처리해줌 
}
//아이디 불러오기 끝


$currentPage = 1; // 기본적으로 1번 페이지로 지정 
            if (isset($_GET["currentPage"])) {
                $currentPage = $_GET["currentPage"];
            } //get요청에 페이지 지정이 있으면 그페이지로 설정하게됨 /

//페이징 작업용 테이블 내 전체 행 갯수 조회 
$sqlCount = "SELECT count(*) FROM freePost";
// koPost의 총 행의 갯수를 조회함 count(*) = 전체행수를 알려주는 sql 자체함수임 
$resultCount = mysqli_query($mysqli,$sqlCount); //sql 실행
if($rowCount = mysqli_fetch_array($resultCount)){
    $totalRowNum = $rowCount["count(*)"];
} //totalRowNum에 결과 값 담김 행이 몇개인지 

if($resultCount){
}else{
    mysqli_error($mysqli);
} // 실행실패시 에러발생

$rowPerPage = 10; //몇개씩 보여줄건지 20개 
$totalPage; // 전체 페이지 개수 


            $countSql = "SELECT COUNT(*) AS row_count FROM freePost";
            $result = $mysqli->query($countSql);

            if ($result) {
                $row = $result->fetch_assoc(); // 결과 행 가져오기
                $rowCount = $row['row_count']; // 결과 행 수 가져오기
                $totalPage = ceil($rowCount / $rowPerPage); // 전체 페이지 수 계산
            } else {
                echo "Error: " . $mysqli->error;
            }

        $begin = ($currentPage -1) * $rowPerPage; // 시작할 post id 의 번호 begin
        //시작되는 번호 = get으로 받아온 현재페이지 즉 1의경우 0 이된다 2번째페이지의 경우 1*10이므로 10번째 배열부터 시작됨  
        $sql = "SELECT freePost.id, user.name, freePost.create_time, freePost.title, freePost.watchCnt, freePost.likeCnt
        FROM freePost
        JOIN user ON freePost.user_id = user.id
         order by freePost.id desc limit $begin,$rowPerPage ";
        // 리스트 조회를위한 select user, freePost를 id로 조인해서 user.name이 반환되게하였다. desc로 내림차순 begin ~ rowPerPage = 1~20까지라는뜻임 
        $result = mysqli_query($mysqli,$sql);
        if($result){
        }else{
            mysqli_error($mysqli);
        }    

   
?>

<!DOCTYPE html>
<html lang="kr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css" type="text/css">

    <title>자유게시판</title>
</head>

<body>

    <header>
        <div class="header_inner">
            <div class="logo_area">
                <a href="/risingproject/home.php">
                <div class="logo"><img src="resources/rise_logo.png" alt="오른다 로고" width="300px" height ="120px" alt="오른다로고"></div>
                </a>

            </div>
            <div class="right_area">
            <?php 
                if($user_name != "Guest"){
                    echo $user_name , " 님 환영합니다 ". '<form action="user/mypage.php" method="POST">
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
                    <a href = "/risingproject/board_usStock.php">미국주식</a>
                </div>
                <div class="nav_board">
                    <a href = "/risingproject/board_free.php">자유게시판</a>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="list_option">
            <div class="left_option">
                
            </div>
            <div class="right_option">
                <input type="button" value="글쓰기" onClick="location.replace('http://192.168.101.129/risingproject/board_free/write.php');">
            </div>
        </div>
        <section id="sc-board">
            <section class="stock_board">
                <div class="board_title"><span>자유 게시판</span></div>
                <table>
                    <colgroup style="margin-bottom:10px">
                        <col style="width: 4%;">
                        <col style="width: 20%;">
                        <col style="width: 8%;">
                        <col style="width: 8%;">
                        <col style="width: 3%;">
                        <col style="width: 3%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">번호</th>
                            <th scope="col">제목</th>
                            <th scope="col">글쓴이</th>
                            <th scope="col">작성일</th>
                            <th scope="col">조회</th>
                            <th scope="col">공감</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            while($row = mysqli_fetch_array($result)){
                                //좋아요수 
                                        $likeCntSql = "SELECT user_id FROM post_likes WHERE post_id = '{$row['id']}'";
                                        $likeResult = mysqli_query($mysqli,$likeCntSql);
                                        $likeCnt = 0;
                                        while(mysqli_fetch_array($likeResult)){
                                            $likeCnt++;
                                        }
                                        
                                ?>
                                 <tr >
                                    <!--id 시작-->
                                        <td class="table" style="  line-height: 2rem;"> 
                                            <?php
                                                echo $row['id'];
                                            ?>
                                        </td>
                                        <!--id  끝 -->
                            <!--제목 title -->
                            <td class="table" > 
                                            <?php
                                                echo "<a href='http://192.168.101.129/risingproject/board_free/freePost_detail.php?id=".$row["id"]."'>";
                                                echo $row["title"];
                                                echo "</a>";
                                                ?>
                            </td>
                            <!--제목 끝-->
                            <td class="table"> 
                                            <?php
                                                echo $row["name"];
                                                ?>
                            </td>
                            <td class="table"> 
                                            <?php
                                                echo $row["create_time"];
                                                ?>
                            </td>
                            <td class="table"> 
                                            <?php
                                                echo $row["watchCnt"];
                                                ?>
                            </td>
                            <td class="table"> 
                                            <?php
                                                echo $likeCnt;
                                                ?>
                            </td>
                        <?php
                        }
                
                        ?>
                        <!--테이블생성기 끝-->
                    
                    </tbody>
                </table>
            </section>
        </section>
        
        <div class="bottom_paging_wrap" style="margin-top:20px;">
            <div class="bottom_paging_box_iconpaging">
                
                <?php 
                if($currentPage ==1){
                    $prevPage = 1;
                }else{
                    $prevPage = $currentPage -1;
                }
                echo "<a href='board_free.php?currentPage=1'>처음</a>";
                echo "<a href='board_free.php?currentPage=${prevPage}'>이전</a>";
                 
                for($index =1; $index<=$totalPage; $index++){
                    echo "<a href='board_free.php?currentPage=${index}'>${index}</a>";
                }
                if($totalPage ==1){
                    $nextpage = 1;
                }else{
                    $nextpage = $currentPage+1;
                }
                echo "<a href='board_free.php?currentPage=${nextpage}'>다음</a>";
                echo "<a href='board_free.php?currentPage=${totalPage}'>끝</a>"
                ?>                   
            </div>
           


        </div>

    </main>
    <footer>
        <div> Copyright 2024. KimChanWoo all rights reserved.

        </div>
    </footer>

<?php
 $mysqli->close();
?>

</body>

</html>