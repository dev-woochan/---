
const delete_btn = document.getElementById("delete_btn");
//회원탈퇴버튼
const iframe = document.getElementById("confirmPassword");

const change_btn = document.getElementById("change_btn");

const dynamicHTML = "<iframe id='open' src = 'confirmPass.php' height = '80px'> </iframe><button id='cancel'>취소</button>";
//탈퇴 html
const newPassHTML = "<iframe id='new_pass_form' src= 'change_pass_form.php' height = '120px'> </iframe><button id='cancel'>취소</button>";
//패스워드 변경 html
delete_btn.addEventListener("click", confirmPass);
// 탈퇴하기 버튼 클릭 이벤트 리스너 

function confirmPass() {
    iframe.innerHTML = dynamicHTML;
    // html을 먼저 삽입해준다 
    const cancelButton = document.getElementById('cancel');
    //그후 내부동작에 관한 것들 ㅇㅇ 
    const open = document.getElementById('open');

    cancelButton.addEventListener('click', function () {
        // 아이프레임 제거
        open.remove();
        cancelButton.remove();
    });
}

//비밀번호 변경하기 시작

//삽입할 html

change_btn.addEventListener("click", newPass);
//이벤트 추가

function newPass() {
    iframe.innerHTML = newPassHTML;
    //html 삽입
    const cancelButton = document.getElementById('cancel');

    const new_pass_form = document.getElementById('new_pass_form');

    cancelButton.addEventListener('click', function () {
        // 아이프레임 제거
        new_pass_form.remove();
        cancelButton.remove();
    });

}



