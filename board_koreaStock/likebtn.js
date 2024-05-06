
let like_btn = document.getElementById("like_btn");





like_btn.addEventListener("click", likeClick
);

async function likeClick() {
    //포스팅 id 가 필요함 
    let postId = '';
    var currentQueryString = window.location.search; //url에서 쿼리 받아옴 
    postId = currentQueryString.substring(4); //id값만 담음 

    let data = { 'postId': postId };

    try {
        const response = await fetch("likebtn.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });
        const result = await response.json();
        if (result.valid == 'increase') {
            document.getElementById("like_cnt").innerText = parseInt(document.getElementById("like_cnt").innerText + 1);
            console.log('증가');
        } else if (result.valid == 'decrease') {
            document.getElementById("like_cnt").innerText = parseInt(document.getElementById("like_cnt").innerText - 1);
            console.log('감소');
        } else {
            alert("로그인을 해주세요");
        }
    } catch (error) {
        console.log("좋아요버튼 에러: " + error);
    }

}