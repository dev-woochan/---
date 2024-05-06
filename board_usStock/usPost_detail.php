<?php
include '../dbconfig.php';
// db 호출 

$post_num = $_GET["id"]; // a태그에 query 를 넣어서 get요청을 보냄 => 포스팅 번호 

session_start(); //세션 시동걸어주기 

if (isset($_SESSION['login_id'])) { //세션에 아이디가 있어야댐
    $user_name = $_SESSION['login_name'];
    $user_id = $_SESSION['login_id'];
} else {
    $user_name = "Guest"; //로그인 값없을시 안전하게 user_name사용하기위해서 예외처리해줌 
}
//아이디 불러오기 끝

// 게시글 불러오기 
$sql = "SELECT usPost.create_time,usPost.symbol ,title, user.name, content, watchCnt, likeCnt, riseSelect, goalDate, postPrice
FROM usPost  
JOIN user ON usPost.user_id = user.id
WHERE usPost.id = ($post_num) ";


$result = mysqli_query($mysqli, $sql);
$result_array = mysqli_fetch_array($result);

// 콘텐츠 끝 댓글 불러오는 db 작성 

$commentSql = "SELECT usComment.id, usComment.create_time, content, parentPostId, parentCommentId, orderNumber, depth, user.name, deleted, replyOrder 
FROM usComment
JOIN user ON user_id = user.id
WHERE parentPostId = '{$post_num}' && depth = 0
order by orderNumber desc
";
//get으로 받아온 값과 일치하는 댓글만 불러오고 순서 orderNumber, depth로 정렬함 물론 최신이 가장위로오게 desc
$commentResult = mysqli_query($mysqli, $commentSql);

//조회수 
$watchSql = "UPDATE usPost SET watchCnt = watchCnt + 1 WHERE id = ($post_num) ";
mysqli_query($mysqli, $watchSql);

//좋아요수 
$likeCntSql = "SELECT user_id FROM post_likes WHERE post_id = $post_num";
$likeResult = mysqli_query($mysqli, $likeCntSql);
$likeCnt = 0;

while (mysqli_fetch_array($likeResult)) {
    $likeCnt++;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미국주식 게시글보기</title>
    <link rel="stylesheet" href="koPost_detail.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">



</head>

<body>

    <header>
        <div class="header_inner">
            <div class="logo_area">
                <div class="logo_area">
                    <a href="/risingproject/home.php">
                        <div class="logo">
                            <img src="/risingproject/resources/rise_logo.png" alt="오른다 로고" width="300px" height="120px"
                                alt="오른다로고">
                        </div>
                    </a>

                </div>
            </div>
            <div class="right_area">
                <?php
                if ($user_name != "Guest") {
                    echo '<span id="user_name">' . $user_name . '</span> 님 환영합니다 <form action="../user/mypage.php" method="POST">';
                    echo '<input type="submit" value="마이페이지">';
                    echo '</form>';
                    echo '<form action="/risingproject/user/logout_process.php" method="POST">';
                    echo '<input type="submit" value="로그아웃">';
                    echo '</form>';

                } else {
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
                    <a href="/risingproject/board_free.php">자유게시판</a>
                </div>
            </div>
        </nav>
    </header>
    <main>

        <div class="articleContentBox">
            <div class="article_header">
                <div class="header_top">
                    <a href="http://192.168.101.129/risingproject/board_usStock.php" class="link_board">미국주식 게시판</a>
                    <div>
                        <?php
                        if ($result_array["name"] == $user_name) {
                            echo "<form action='http://192.168.101.129/risingproject/board_usStock/usPost_update.php' method='POST'>";
                            echo "<input type='hidden' id='post_id' name='post_id' value= $post_num>";
                            echo "<input type='submit' id='update_btn' value='수정' style='background-color: green; color: white;' >";
                            echo "</form>";
                            echo "<form action='http://192.168.101.129/risingproject/board_usStock/usPost_delete.php' method= 'POST'onsubmit='return confirm(\"정말로 삭제하시겠습니까??\");'>";
                            echo "<input type='hidden' id='post_id' name='post_id' value= $post_num>";
                            echo "<input type='submit' class='button' style='background-color: red; color: white;' id='delete_btn' value='삭제'>";
                            echo "</form>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="header_mid">
                <div class="article_title">
                    <?php
                    echo $result_array["title"];
                    ?>

                </div>
                <div class="article_writer">작성자 :
                    <?php
                    echo $result_array["name"];
                    ?>
                </div>
                <div class="article_date">
                    <?php
                    echo $result_array["create_time"];
                    ?>
                </div>
            </div>
            <!--underline-->
            <div class="article_stock">
                <div class="stock">
                    종목 :
                    <?php
                    echo $result_array["symbol"];
                    ?>
                </div>
                <div class="riseSelect">
                    <?php
                    echo $result_array["riseSelect"];
                    ?>
                </div>
            </div>
            <div class="article_goal">
                <div class="price">
                    작성일 가격 :
                    <?php
                    echo $result_array["postPrice"];
                    echo " 원";
                    ?>
                </div>
                <div class="goalDate">
                    목표일 :
                    <?php
                    echo $result_array["goalDate"];
                    ?>

                </div>

            </div>
            <div class="article_content">
                <?php
                echo $result_array["content"];

                ?>
            </div>
            <!--underline-->


            <div class="like">
                <button id="like_btn"></button>
                <div style="font-size: 20px;" id="like_cnt">
                    <?php
                    echo $likeCnt;
                    ?>
                </div>
            </div>
            <!--좋아요 버튼 끝-->
            <div class="article_comment">
                <div class="comment_title">댓글</div>
                <!--underline-->
                <!--댓글 불러오기 시작 -->
                <ul id="comment_container">
                    <?php
                    while ($comment_row = mysqli_fetch_array($commentResult)) {
                        ?>
                        <?php
                        if ($comment_row['deleted'] == 1) {
                            echo "<li class='comment_list' data-id='{$comment_row['id']}' data-orderNumber='{$comment_row['orderNumber']}'>";
                            echo "<div class='comment_list'>삭제되었습니다</div>"; //삭제된댓글은 아예안보여줌 
                        } else {
                            echo "<li class='comment_list' data-id='{$comment_row['id']}' data-orderNumber='{$comment_row['orderNumber']}'>";
                            ?>
                            <div class="comment_top">
                                <div class="comment_name" style="font-weight:700;">
                                    <?php
                                    echo "<span id ='comment_name'>{$comment_row['name']}</span>";
                                    ?>
                                </div>
                                <div>
                                    <input type="hidden" name="comment_id" class="comment_id" value="<?php
                                    echo $comment_row['id'];
                                    ?>">
                                    <div style="display :flex; flex-direction:row;" class="buttons">
                                        <?php
                                        if ($user_name == $comment_row['name']) {
                                            echo '<button class="modify_comment" style="background-color: green; color: white;" onclick="modify_comment(this)">수정</button>
                                                    <button class="delete_comment" style="background-color: red; color: white;" onclick="comment_delete(this)">삭제</button> ';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="comment_time">
                                <?php
                                echo $comment_row['create_time'];
                                ?>
                            </div>
                            <div class="comment_bottom">
                                <div class="comment_content" style="text-align:start; margin-top:10px; font-size:14px">
                                    <?php
                                    echo $comment_row['content'];
                                    ?>
                                </div>
                                <div style="padding-top:20px" class="replyBtnWrap">
                                    <button class="reply_btn" onclick="reply_form_insert(this);">답변</button>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <ul class="reply_container">
                            <?php
                            $replySql = "SELECT 
                                fc.id AS comment_id,
                                fc.create_time, 
                                fc.content, 
                                fc.parentPostId, 
                                fc.parentCommentId, 
                                fc.orderNumber, 
                                fc.depth, 
                                user.name AS user_name, 
                                fc.deleted, 
                                fc.replyOrder,
                                parent.id AS parent_comment_id,
                                parent_user.name AS parent_user_name
                            FROM 
                                usComment AS fc
                                JOIN user ON fc.user_id = user.id
                                LEFT JOIN comment AS parent ON fc.parentCommentId = parent.id
                                LEFT JOIN user AS parent_user ON parent.user_id = parent_user.id
                            WHERE 
                                fc.parentPostId = '{$post_num}' 
                                AND fc.depth = 1 
                                AND fc.orderNumber = {$comment_row['orderNumber']};";

                            $reply_result = mysqli_query($mysqli, $replySql);
                            while ($reply_row = mysqli_fetch_array($reply_result)) {
                                $parent_user_name = $reply_row['parent_user_name'];
                                if ($parent_user_name == NULL) {
                                    $parent_user_name = "삭제된 댓글";
                                }

                                echo "<li class='comment_list' data-id='{$reply_row['comment_id']}' data-orderNumber='{$reply_row['orderNumber']}'>";
                                ?>
                                <div class="comment_top">
                                    <div class="comment_name" style="font-weight:700;">
                                        <?php
                                        echo "<span id ='comment_name'>{$reply_row['user_name']}</span><span style='color:blue; font-size: 14px'>  @{$parent_user_name}</span>";
                                        ?>
                                    </div>
                                    <div>

                                        <div style="
                                display :flex;
                                flex-direction:row;
                                " class="buttons">
                                            <?php
                                            if ($user_name == $reply_row['user_name']) {
                                                echo '<button class="modify_comment" style="background-color: green; color: white;" onclick="modify_comment(this)" >수정</button>
                                        <button class="delete_comment" style="background-color: red; color: white;" onclick="comment_delete(this)">삭제</button> ';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="comment_time">
                                    <?php
                                    echo $reply_row['create_time'];
                                    ?>
                                </div>
                                <div class="comment_bottom">
                                    <div class="comment_content" style="text-align:start; margin-top:10px; font-size:14px">
                                        <?php
                                        echo $reply_row['content'];
                                        ?>
                                    </div>
                                    <div style="padding-top:20px" class="replyBtnWrap">
                                        <button class="reply_btn" onclick="reply_form_insert(this);">답변</button>
                                    </div>
                                </div>
                                <?php

                                ?>
                                </li>
                                <?php
                            }
                            ?>

                        </ul>
                        </li>
                        <?php

                    }
                    ?>
                </ul>


                <!--댓글 불러오기 끝 -->

                <div class="comment_insert">
                    <div class="comment_insert_name" id="login_name">
                        <!--로그인했을때 안했을때 경우 나눠서 이름으로 등록되거나 로그인 요구창 나옴 -->
                        <?php
                        if ($user_name != "Guest") { //로그인중 
                            echo '<span style="font-weight: bold">' . $user_name . '</span>';
                        } else {
                            echo '
                            <a href="/risingproject/user/login.php" style="font-weight: bold"> 로그인</a>  을해주세요 
                            ';
                        }
                        ?>
                    </div>
                    <input type="text" id="comment_insert_content" name="comment_insert_content">
                    <div class="comment_btn_wrapper">

                        <?php
                        if ($user_name != "Guest") { //로그인중 
                            echo '<input type="button" value = "등록" id="comment_btn" onclick="comment_insert()">   ';
                        } else {
                            //로그인 안되어있으면 등록버튼이 없음
                        }
                        ?>
                    </div>


                </div>
            </div>


        </div>


        <footer>
            Copyright 2024. KimChanWoo all rights reserved.
        </footer>
        <script src="comment.js">
        </script>
        <script src="likebtn.js">
        </script>
</body>

</html>