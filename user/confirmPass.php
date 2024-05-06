<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호확인</title>
</head>
<body>
    <div>
     정말 삭제하시려면 비밀번호를 입력해주세요
    </div>
    <form action="delete_user.php" method="POST">
    <input type="password" placeholder="비밀번호입력" name= "password">
    <input type="submit" value="제출하기" onclick= "return confirm('정말로 삭제하시겠습니까?');">
</form>
</body>
</html>

<!--iframe에 들어갈 비밀번호 입력확인창 비밀번호가 확인창을받고 post로 유저삭제 요청을 보낸다 -!>