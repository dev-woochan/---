//로그인 유효성 검사 아이디, 비밀번호 입력이 없을시 submit하기전에 alter로 알려준다

function loginCheck() {
    const email = document.getElementById('email').value.trim();
    const pw = document.getElementById('pw').value.trim();

    if (email == "") {
        alert("아이디를 입력해주세요.");
        document.getElementById('email').focus();
        return false;
    } else if (pw == "") {
        alert("비밀번호를 입력해주세요.");
        document.getElementById('pw').focus();
        return false;
    } else {
        return true;
    }

}

