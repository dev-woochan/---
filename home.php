<?php
include 'dbconfig.php';

session_start(); //세션 시동걸어주기 

if (isset($_SESSION['login_id'])) { //세션에 아이디가 있어야댐
    $user_name = $_SESSION['login_name'];
    $user_id = $_SESSION['login_id'];
} else {
    $user_name = "Guest"; //로그인 값없을시 안전하게 user_name사용하기위해서 예외처리해줌 
}
//아이디 불러오기 끝


$sql = "SELECT koPost.id, user.name, koPost.create_time, koPost.title, koPost.riseSelect, koPost.stockName, koPost.watchCnt, koPost.likeCnt, LPAD(koStock.code, 6, '0') AS stockCode
FROM koPost
JOIN user ON koPost.user_id = user.id
JOIN koStock ON koPost.stockName = koStock.name
order by koPost.id desc limit 5";
//게시글 불러오기 
$loadPost = mysqli_query($mysqli, $sql);

$ussql = "SELECT usPost.id, user.name, usPost.create_time, usPost.title, usPost.riseSelect,usPost.symbol, usPost.watchCnt, usPost.likeCnt
FROM usPost
JOIN user ON usPost.user_id = user.id
order by usPost.id desc limit 5";
//게시글 불러오기 
$loadUsPost = mysqli_query($mysqli, $ussql);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF8">
    <link rel="stylesheet" href="home.css" type="text/css">
    <title>
        오른다 홈
    </title>
    <script type="text/javascript">
        function getCookie(name) {
            var cookie = document.cookie; //cookie라는 변수에 쿠키 전체값 넣기 (배열로반환됨 key=value; key2=value2; 요론식)
            if (document.cookie != "") { // 쿠키가 있는경우
                var cookie_array = cookie.split("; "); // ; 가 구분자이기때문에 key=value로 된 배열을 얻기위해 split 해준다
                for (var i = 0; i < cookie_array.length; i++) { //spilit한 것을 순회
                    var cookie_name = cookie_array[i].split("=");
                    if (cookie_name[0] == "popupYN") {
                        return cookie_name[1];
                    }
                }
            }
            return undefined;
        }

        function openPopup(url) {
            var cookieCheck = getCookie("popupYN"); //쿠키를 받아와서 24시간 열람 x 를 체크했다면 N이 들어가있으므로 팝업이 열리지 않음 
            if (cookieCheck != "N") window.open(url, '', 'width=400,height=520,left=0,top=0')
        }
    </script>
</head>

<body onload="openPopup('popup.html')">
    <header>
        <div class="header_inner">
            <div class="logo_area">
                <a href="/risingproject/home.php">
                    <div class="logo"><img src="resources/rise_logo.png" alt="오른다 로고" width="300px" height="120px"
                            alt="오른다로고"></div>
                </a>
            </div>
            <div class="right_area">
                <?php
                if ($user_name != "Guest") {
                    echo $user_name, " 님 환영합니다 " . '<form action="user/mypage.php" method="POST">
                    <input type="submit" value="마이페이지">
                    </form>
                    <form action="/risingproject/user/logout_process.php" method="POST">
                    <input type="submit" value="로그아웃">
                    </form>'
                    ;
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
        <div style="margin-top : 10px; font-weight: 600; margin-top: 10px;">
            주요 증시 현황
        </div>
        <section class="sc-chart">
            <section class="chart_wrap">
                <div class="chart_inner">
                    <div class="chart_title">
                        코스피
                    </div>
                    <span id="KOSPIclpr">- </span>
                    <span id="KOSPIvs">-</span>
                    <span id="KOSPIfltRt">-</span>
                    <div>
                        <img src="https://ssl.pstatic.net/imgfinance/chart/main/KOSPI.png?sidcode=1708490701986"
                            class="main_chart_kospi" alt="실시간 코스피 차트">
                    </div>
                </div>
                <div class="chart_inner">
                    <div class="chart_title">
                        코스닥
                    </div>
                    <span id="KOSDAQclpr">-</span>
                    <span id="KOSDAQvs">-</span>
                    <span id="KOSDAQfltRt">-</span>
                    <div>
                        <img src="https://ssl.pstatic.net/imgfinance/chart/main/KOSDAQ.png?sidcode=1708490701988"
                            class="main_chart_kosdaq" alt="실시간 코스닥 차트">
                    </div>
                </div>
            </section>
            <section class="chart_wrap">
                <div class="chart_inner">
                    <div class="chart_title">
                        나스닥
                    </div>
                    <span id='NASDAQclpr'>15,927.90 </span>
                    <span id='NASDAQvs'>+316.14</span>
                    <span id='NASDAQfltRt'>+2.03%</span>
                    <div>
                        <img src="https://ssl.pstatic.net/imgfinance/chart/world/continent/NAS@IXIC.png?1708491621832"
                            class="main_chart_nasdaq" alt="실시간 나스닥 차트">
                    </div>
                </div>
                <div class="chart_inner">
                    <div class="chart_title">
                        S&P500
                    </div>
                    <span id="S&Pclpr">5,099.96 </span>
                    <span id="S&Pvs">+51.54 </span>
                    <span id="S&PfltRt">+1.02%</span>
                    <div>
                        <img src="https://ssl.pstatic.net/imgfinance/chart/world/continent/SPI@SPX.png?1708491621832"
                            class="main_chart_S&P500" alt="실시간 S&P500 차트">
                    </div>
                </div>


            </section>
            <section class="recent_wrap">
                <div class="chart_inner">
                    <div class="chart_title">
                        최근 조회 주식
                    </div>
                    <div id="recentStock">
                        <?php
                        if (isset($_COOKIE['recentStock'])) {
                            $stockJson = $_COOKIE['recentStock'];
                            $stockList = json_decode($stockJson, true);
                            $index = 0;
                            while ($index < count($stockList)) {
                                $num = $index + 1;
                                echo "
                            <div id='stockList'>
                                <div id='stockNum'>$num.</div><div id='stock'>$stockList[$index]</div>
                            </div>
                            ";
                                $index++;
                            }
                            ;
                        } else {
                            echo "최근 조회한 주식이 없습니다";
                        }
                        ?>
                    </div>

                </div>


            </section>

        </section>
        <section id="sc-board">
            <section class="stock_board">
                <div class="board_title"><a href="/risingproject/board_koreaStock.php">국내주식 게시판</a></div>
                <table>
                    <colgroup>
                        <col style="width: 4%;">
                        <col style="width: 8%;">
                        <col style="width: 20%;">
                        <col style="width: 8%;">
                        <col style="width: 8%;">
                        <col style="width: 4%;">
                        <col style="width: 3%;">
                        <col style="width: 3%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">번호</th>
                            <th scope="col">종목명</th>
                            <th scope="col">제목</th>
                            <th scope="col">글쓴이</th>
                            <th scope="col">작성일</th>
                            <th scope="col">오를까?</th>
                            <th scope="col">조회</th>
                            <th scope="col">공감</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        while ($row = mysqli_fetch_array($loadPost)) {

                            $likeCntSql = "SELECT user_id FROM post_likes WHERE post_id = '{$row['id']}'";
                            $likeResult = mysqli_query($mysqli, $likeCntSql);
                            $likeCnt = 0;
                            while (mysqli_fetch_array($likeResult)) {
                                $likeCnt++;
                            }

                            ?>


                            <tr class="ub-content">
                                <td><?php
                                echo $row['id'];
                                ?></td>
                                <td><?php
                                echo "<a href='https://finance.naver.com/item/main.naver?code=" . $row["stockCode"] . "' class='stockLink' target='_blank'>";
                                echo $row["stockName"];
                                echo "</a>";
                                ?></td>
                                <td><?php
                                echo "<a href='http://192.168.101.129/risingproject/board_koreaStock/koPost_detail.php?id=" . $row["id"] . "'>";
                                echo $row["title"];
                                echo "</a>";
                                ?></td>
                                <td><?php
                                echo $row["name"];
                                ?></td>
                                <td><?php
                                echo $row["create_time"];
                                ?></td>
                                <td>
                                    <?php
                                    echo $row["riseSelect"];
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
                            </tr>

                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </section>
            <section class="stock_board">
                <div class="board_title"><span>미국주식 게시판</span></div>
                <table>
                    <colgroup>
                        <col style="width: 4%;">
                        <col style="width: 8%;">
                        <col style="width: 20%;">
                        <col style="width: 8%;">
                        <col style="width: 8%;">
                        <col style="width: 4%;">
                        <col style="width: 3%;">
                        <col style="width: 3%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">번호</th>
                            <th scope="col">종목명</th>
                            <th scope="col">제목</th>
                            <th scope="col">글쓴이</th>
                            <th scope="col">작성일</th>
                            <th scope="col">오를까?</th>
                            <th scope="col">조회</th>
                            <th scope="col">공감</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        while ($row2 = mysqli_fetch_array($loadUsPost)) {
                            $likeCntSql = "SELECT user_id FROM post_likes WHERE post_id = '{$row2['id']}'";
                            $likeResult = mysqli_query($mysqli, $likeCntSql);
                            $likeCnt = 0;
                            while (mysqli_fetch_array($likeResult)) {
                                $likeCnt++;
                            }

                            ?>

                            <tr class="ub-content">
                                <td><?php
                                echo $row2['id'];
                                ?></td>
                                <td><?php
                                echo $row2["symbol"];
                                ?></td>
                                <td><?php
                                echo "<a href='http://192.168.101.129/risingproject/board_usStock/usPost_detail.php?id=" . $row2["id"] . "'>";
                                echo $row2["title"];
                                echo "</a>";
                                ?></td>
                                <td><?php
                                echo $row2["name"];
                                ?></td>
                                <td><?php
                                echo $row2["create_time"];
                                ?></td>
                                <td>
                                    <?php
                                    echo $row2["riseSelect"];
                                    ?>
                                </td>
                                <td class="table">
                                    <?php
                                    echo $row2["watchCnt"];
                                    ?>
                                </td>
                                <td class="table">
                                    <?php
                                    echo $likeCnt;
                                    ?>
                                </td>
                            </tr>

                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </section>

    </main>

    <script src="recentStock.js"></script>
    <script src="home.js"></script>

</body>
<footer>
    Copyright 2024. KimChanWoo all rights reserved.
</footer>

</html>