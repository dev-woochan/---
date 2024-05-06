<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호변경</title>
</head>
<body>
    <div>
     비밀번호 변경
    </div>
    <form action="change_pass_process.php" method="POST">
    <input type="password" placeholder="비밀번호입력" name= "password">
    <input type="password" placeholder="새로운 비밀번호" name="new_password">
    <input type="password" placeholder="비밀번호 확인" name="new_password_confirm">
    <input type="submit" value="제출하기" >
</form>
</body>
</html>


<!--iframe에 넣어줄 비밀번호 변경창  -!>