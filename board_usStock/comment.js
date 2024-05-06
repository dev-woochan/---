let urlParams = new URLSearchParams(window.location.search);
let id = urlParams.get('id');

//댓글 ajax 통신을 위한 comment.js 입력한값을 받아서 검사후 php코드로 보낸다
const comment_container = document.getElementById("comment_container");

//부모댓글 달기 
function comment_insert() {
    event.preventDefault();
    let commentInput = document.getElementById("comment_insert_content").value;
    let parentPostId = id;
    let orderNum = document.getElementsByClassName("comment_list").length + 1;
    let depth = 0; // 부모댓글이므로 depth = 0;
    //입력한 값 가져오기 
    //ajax 통신 
    let data;
    data = {
        "content": commentInput,
        "parentPostId": parentPostId,
        "orderNum": orderNum,
        "depth": depth
    };
    try {
        //fetch요청
        fetch("/risingproject/board_usStock/comment_insert.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data)
        }).then(response => {
            console.log(response.status);
            return response.json();
        })
            .then(result => {
                console.log(result);
                //댓글등록이 성공하게되면 댓글값이 db에 저장되게 될것임 
                if (result.valid) {
                    //성공한경우 댓글 추가 
                    // //댓글 html 변수에담기
                    // // 새로운 댓글 추가하기 
                    var newCommentElement = document.createElement('li');
                    newCommentElement.className = "comment_list";
                    newCommentElement.dataset.id = result.id;
                    newCommentElement.dataset.orderNumber = result.orderNumber;
                    newCommentElement.innerHTML = `                      
                    <div class="comment_top">
                            <div class="comment_name" style="font-weight:700;"><span id="comment_name">${result.user_name}</span></div>
                        <div>
                            <div style="display :flex;flex-direction:row;"class="buttons">
                                <button class="modify_comment" style="background-color: green; color: white;" onclick="modify_comment(this)">수정</button>
                                <button class="delete_comment" style="background-color: red; color: white;" onclick="comment_delete(this)">삭제</button>
                            </div>                     
                         </div>  
                    </div>
                    <div class="comment_time">${result.time}</div>
                    <div class="comment_bottom">
                        <div class="comment_content" style="text-align:start; margin-top:10px; font-size:14px">${result.content}</div>                 
                        <div style="padding-top:20px" class="replyBtnWrap">
                            <button class="reply_btn" onclick="reply_form_insert(this)">답변</button>
                        </div>
                    </div>    
                     <ul class="reply_container"></ul>`;
                    comment_container.prepend(newCommentElement);
                    newCommentElement.setAttribute('tabindex', '-1');
                    newCommentElement.focus();
                    //맨앞에 삽입 (최신순정렬이기때문)
                    document.getElementById("comment_insert_content").value = '';
                } else {
                    window.alert("댓글입력 실패");
                }
            });
    } catch (error) {
        console.error('에러발생 : ', error);
    }
}
//부모 댓글 달기 끝

//대댓글 입력창 넣기 
function reply_form_insert(button) {
    //button에는 this로 이 요소를 접근시킬것임 
    const parent_comment = button.closest(".comment_list");
    let parent_name = parent_comment.querySelector("#comment_name").innerText;
    //부모이름을 받아옴 
    button.disabled = true; //답글 클릭 비활성화(중복클릭방지)
    let replyElement = document.createElement('div');
    replyElement.className = "reply_insert";
    replyElement.innerHTML = '';
    const html = `<i class="fa-solid fa-reply" style="margin-right:5px;flex:1"></i> 
                    <div class="comment_insert" style="flex:12">
                        <input type="text" class="reply_content" name="comment_insert_content" placeholder="@${parent_name} 님께 답글">
                        <div class="comment_btn_wrapper">
                            <button id="reply_submit" onclick="reply_submit(this)">등록</button><button id="reply_cancel" onclick="reply_cancel(this);">취소</button>
                        </div>
                    </div>`;
    //들어갈 html 생성하기 
    replyElement.innerHTML = html;
    //삽입
    parent_comment.appendChild(replyElement);
    //appendChild로 맨뒤에 삽입 
    replyElement.querySelector(".reply_content").focus();
}

//대댓글 취소 
function reply_cancel(button) {
    let reply_insert = button.closest(".reply_insert");
    const parent_comment = button.closest(".comment_list");
    reply_insert.parentNode.removeChild(reply_insert);
    parent_comment.querySelector(".reply_btn").disabled = false;
    // reply_insert 삭제하고 기존 버튼 활성화 
}

//대댓글 등록하기 
function reply_submit(button) {
    //필요한것들 받아오기
    let parent_comment = button.closest(".comment_list");
    let parent_id = parent_comment.dataset.id; //부모 댓글 아이디 받아오기 
    console.log("받아온 부모 아이디 :" + parent_id);
    let reply_container = button.closest(".reply_container");
    if (reply_container == null) {
        reply_container = parent_comment.querySelector(".reply_container");
    }
    console.log(reply_container);
    //대댓글 넣을 컨테이너 
    let reply_insert = button.closest(".reply_insert");
    //답글 창 요소 
    let reply_content = reply_insert.querySelector(".reply_content").value;
    let reply_data = {
        "parent_comment_id": parent_id,
        "content": reply_content,
        "parentPostId": id,
        "depth": 1,
    } //부모id, 댓글입력한값, 포스팅id, depth 넘김
    fetch("comment_insert.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(reply_data)
    })
        .then(response => response.json()) //response값 디코딩
        .then(data => {
            if (data.valid) {
                //요청 성공시 동작 reply 할 답변 넣어주기,누구에게 답변하는것인지,
                reply_content = data.content;
                let parentName = data.parentName;
                let username = data.user_name;
                let time = data.time;
                let reply_list = document.createElement('li');
                let id = data.id;
                reply_list.className = "comment_list";
                reply_list.dataset.id = id;
                reply_list.dataset.orderNumber = data.orderNumber;
                let html = ` 
                <div class="comment_top">
                    <div class="comment_name" style="font-weight:700;">
                        <span id="comment_name">${username}</span><span style="color:blue; font-size: 14px">  @${parentName}</span>
                    </div>                 
                <div>
                    <div style="
                    display :flex;
                    flex-direction:row;
                    " class="buttons">
                            <button class="modify_comment" style="background-color: green; color: white;" onclick="modify_comment(this)">수정</button>
                            <button class="delete_comment" style="background-color: red; color: white;" onclick="comment_delete(this)">삭제</button>      
                    </div>               
                </div>  
                </div>
                <div class="comment_time">${time}</div>
                <div class="comment_bottom">
                    <div class="comment_content" style="text-align:start; margin-top:10px; font-size:14px">
                    ${reply_content}
                </div>                
                    <div style="padding-top:20px" class="replyBtnWrap">
                    <button class="reply_btn" onclick="reply_form_insert(this)">답변</button>
                    </div>   
                </div>`
                reply_list.innerHTML = html;
                //html 생성하기 
                reply_container.appendChild(reply_list);
                //대댓글 삽입 
                reply_cancel(button);
                //입력창 삭제 
            }
        })
}
//대댓글 입력 끝

//댓글 수정 
function modify_comment(button) {
    let comment_list = button.closest(".comment_list");
    //댓글창 불러옴
    let html_backup = comment_list.innerHTML;
    //백업용 html
    let comment_content_element = comment_list.querySelector(".comment_content");
    let comment_content = comment_content_element.innerText;
    let comment_id = comment_list.dataset.id;
    //아이디, 기존값 로드 
    comment_list.querySelector(".buttons").innerHTML = "";            //수정 삭제버튼 삭제

    let text_area = document.createElement("textarea"); //삽입할 택스트 에어리어
    text_area.value = comment_content; //기존값 삽입
    text_area.style.width = "400px";

    comment_content_element = comment_content_element.parentNode.replaceChild(text_area, comment_content_element); // 택스트에어리어로 변경

    text_area.focus(); //수정창에 포커싱 

    let button_area = comment_list.querySelector(".replyBtnWrap"); //버튼 바꿀 곳

    let submit_area = document.createElement("div"); // 변경할 html
    submit_area.innerHTML = `<button class='modify_submit'>등록</button><button class='modify_cancel'>취소</button>`;

    button_area = button_area.parentNode.replaceChild(submit_area, button_area); // 내부요소 변경하기

    let submitBtn = submit_area.querySelector('.modify_submit');
    let cancelBtn = submit_area.querySelector('.modify_cancel');

    cancelBtn.addEventListener("click", function () { //취소버튼  기존 html로 복구함
        comment_list.innerHTML = html_backup;// 기존 html로 복구
        console.log("취소");
    });

    submitBtn.addEventListener("click", function () { //등록버튼 수정이 되어야함
        var modify_content = text_area.value; // 입력값 받아옴
        var data = {
            "comment_id": comment_id,
            "comment": modify_content
        }; //json 데이터 만들어줌

        fetch("comment_update.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json() //반환된 데이터 json encoded 되어있음 풀어주기
            )
            .then(data => { //위의 결과값 data
                console.log(data);
                if (data.success) { //true 반환시
                    let updatedComment = data.updatedComment; //반환받은 comment 받아와줌
                    console.log(updatedComment);
                    comment_list.innerHTML = html_backup; //기존 html로 변경
                    comment_list.querySelector('.comment_content').innerText = updatedComment; //content 변경 반영하기
                }
            })
            .catch(error => {
                console.log("fetch에러:" + error);
                alert("에러발생");
            })
    });
}
//댓글 수정 끝!

//댓글 삭제 시작
function comment_delete(button) {
    let comment_list = button.closest(".comment_list");
    // 삭제 버튼 클릭 시 동작
    var comment_id = comment_list.dataset.id;
    // 해당 댓글의 ID를 가져옴
    let data = {
        "comment_id": comment_id
    }
    if (confirm('정말 삭제하시겠습니까?')) {
        fetch("comment_delete.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.type == 'deleted') {
                        let comment_top = comment_list.querySelector(".comment_top");
                        comment_list.removeChild(comment_top);
                        let comment_time = comment_list.querySelector(".comment_time");
                        comment_list.removeChild(comment_time);
                        let comment_bottom = comment_list.querySelector(".comment_bottom");
                        //기존 내부 요소들 삭제 
                        let deleted_element = document.createElement('div');
                        deleted_element.className = "comment_list"
                        deleted_element.innerText = "삭제되었습니다";
                        deleted_element.dataset.orderNumber = data.orderNumber;
                        comment_bottom = comment_list.replaceChild(deleted_element, comment_bottom);
                        // 삭제된 댓글입니다로 교체 
                        console.log("삭제되었습니다");
                    } else if (data.type == 'delete') {
                        comment_list.parentNode.removeChild(comment_list);
                        //삭제하기 (자식댓글이 없는경우)
                        console.log("그냥삭제");
                    } else if (data.type == 'allDelete') {
                        console.log("모두삭제");
                        let parent_list = comment_list.parentNode.closest(".comment_list");
                        parent_list.parentNode.removeChild(parent_list);

                    }
                } else {
                    alert("댓글삭제 실패 ")
                }
            })
    }
}


let riseSelect = document.querySelector(".riseSelect");
if (riseSelect.innerText.trim() == '내린다') {
    console.log(riseSelect.innerText);
    riseSelect.style.backgroundColor = "#2468d5";
}


