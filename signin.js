//회원가입 클릭전 닉네임, 아이디 중복체크 


//fetch 함수를 이용한 비동기 메일중복체크요청
//blur처리될때 mailcheck에 post로 이메일 정보를 보내 중복검사 실시함 
async function mail_check(data) {
    try {
        //fetch요청을 보냄 await으로 요청이 끝날때까지 기다림 
        const response = await fetch("/risingproject/user/mailCheck.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });
        // response를 .json으로 객체화시켜줌 fetch의 응답이 response로 오는것 !!
        const result = await response.json();
        console.log(result.valid);
        if (result.valid) {
            document.getElementById("mail_check").innerText = "중복되는 메일이 없습니다";
            document.getElementById("mail_check").style.color = "green";
            console.log('트루됨');
        } else {
            document.getElementById("mail_check").innerText = "이미 사용중인 이메일입니다";
            document.getElementById("mail_check").style.color = "red";
            console.log('엘스됨');
        }
    } catch (error) {
        document.getElementById("mail_check").innerText = "다른 이메일을 설정해주세요";
        document.getElementById("mail_check").style.color = "red";
        console.log('캐치됨');
    }
}

//name체크 
async function name_check(data) {
    try {
        const response = await fetch("/risingproject/user/nameCheck.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });
        const result = await response.json();
        console.log(result.valid);
        if (result.valid) {
            document.getElementById("name_check").innerText = "중복되는 이름이 없습니다";
            document.getElementById("name_check").style.color = "green"
            console.log('트루됨');
        } else {
            document.getElementById("name_check").innerText = "이미 사용중인 이름 입니다";
            document.getElementById("name_check").style.color = "red"
            console.log('엘스됨');
        }
    } catch (error) {
        document.getElementById("name_check").innerText = "다른 이름을 설정해주세요";
        document.getElementById("name_check").style.color = "red"
        console.log('캐치됨');
    }
}


//중복검사 실행코드 onblur는 focus가 사라졌을때를 뜻한다. 
let tmpmail = document.getElementById("mail");
tmpmail.onblur = () => {
    let inputMail = tmpmail.value;
    let data = { mail: inputMail };
    mail_check(data);
}
//닉네임도 동일하게
let tmpname = document.getElementById("name");
tmpname.onblur = () => {
    let inputName = tmpname.value;
    let data = { name: inputName };
    name_check(data);
}




//회원가입 유효성 검사 
//닉네임 3자 이상 아이디 email양식 비밀번호 7자 이상
//유효한 값만 submit 해서 서버에서 처리하게 해줌 
function siginin_Check() {
    //아이디, 패스워드 정규식 
    const idPattern = /[a-z0-9]*@[a-z0-9]+[|.]*.com/i;
    const passPattern = /[a-z0-9]/i;

    //form값 받아오기 
    let name = document.getElementById("name").value.trim();
    let mail = document.getElementById("mail").value.trim();
    let password = document.getElementById("password").value.trim();
    let passconfirm = document.getElementById("password_confirm").value.trim();

    document.getElementById("name_check").innerText = "";
    document.getElementById("mail_check").innerText = "";
    document.getElementById("password_check").innerText = "";
    document.getElementById("password_confirm_check").innerText = "";
    //입력값 틀릴시 발생 
    if (name.length == 0) {
        document.getElementById("name_check").innerText = "닉네임을 입력해주세요";
        document.getElementById("name").focus();
        return false;
    } else if (name.length < 3) {
        document.getElementById("name").focus();
        document.getElementById("name_check").innerText = "닉네임은 3자 이상 설정해주세요";
        return false;
    } else if (!idPattern.test(mail)) {
        document.getElementById("mail").focus();
        document.getElementById("mail_check").innerText = "abc@abc.com 의 양식을 지켜야합니다";
        return false;
    } else if (password == 0) {
        document.getElementById("password_check").innerText = "비밀번호를 입력해주세요";
        document.getElementById("password").focus();
        return false;
    } else if (password.length < 7) {
        document.getElementById("password").focus();
        document.getElementById("password_check").innerText = "비밀번호는 7자리 이상 설정되어야합니다";
        return false;
    } else if (!passPattern.test(password)) {
        document.getElementById("password").focus();
        document.getElementById("password_check").innerText = "비밀번호는 영어소문자,숫자로 구성되어야합니다";
        return false;
    }
    else if (passconfirm != password) {
        document.getElementById("password_confirm").focus;
        document.getElementById("password_confirm_check").innerText = "비밀번호가 일치하지 않습니다";
        return false
    }
    else {
        alert("회원가입 성공")
        return true;
    }
}
