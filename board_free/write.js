



const titleInput = document.getElementById("titleInput");
const contentInput = document.getElementById("summernote");


function writeCheck() { //예외처리를 위함 만약에 제목 종목 등 입력값이 제대로 안되어있을시 false 반환
    //onsubmit 으로 post 제출전 사용 예정
    console.log("확인 호출")
    if (titleInput.value.trim() == "") {
        alert('제목을입력해주세요');
        titleInput.focus();
        return false;
    } else if (contentInput.value == "") {
        alert("내용을 입력해주세요");
        contentInput.focus();
        return false;
    } else {
        return true;
    }
}



// 메인화면 페이지 로드 함수
$(document).ready(function () {
    $('#summernote').summernote({
        placeholder: '내용을 작성하세요',
        height: 300,
        maxHeight: 300,
        callbacks: {
            // 파일 업로드시 동작하는 코드
            // onImageUpload 이지만 비디오 드랍도 동작함.
            onImageUpload: function (files) {
                setFiles(files);
            },

            // 클립보드에 있는(윈도우 + 쉬프트 + s) 한 경우에 에디터에서 붙여넣기(컨트롤+v) 하는 경우
            // 섬머노트 기본 이미지 붙여넣기 기능을 막는 코드.
            // 없으면 이미지 2장씩 들어간다. ( 하나는 setFiles(file 형태) 로 하나는 base64(string 형태) 로 )
            onPaste: function (e) {
                const clipboardData = e.originalEvent.clipboardData;
                if (clipboardData && clipboardData.items && clipboardData.items.length) {
                    const item = clipboardData.items[0];
                    // 붙여넣는게 파일이고, 이미지면
                    if (item.kind === 'file' && item.type.indexOf('image/') !== -1) {
                        // 이벤트 막음
                        e.preventDefault();
                    }
                }
            }
        },
    });
});
const submit = document.getElementById('submit');

submit.addEventListener('click', function (event) {
    event.preventDefault(); // 기본 제출 동작을 막음
    summit();
});

// summit 함수 만들기
function summit() {
    if (writeCheck()) {

        let content = $('#summernote').summernote('code');

        const formData = new FormData(document.getElementById('write_form'));

        // 에디터 내부에 img, iframe 태그가 남아있는지 확인.
        const sommernoteWriteArea = document.getElementsByClassName("main_text")[0];
        const srcArray = [];
        // getElementsByTagName 가 반환하는 형태는 HTMLCollection 인데 실제 배열이 없어서 forEach() 가 없음..
        // 그래서 Array.from 로 array 로 만들어줌.
        const iframeTags = Array.from(sommernoteWriteArea.getElementsByTagName('iframe'));
        const imgsTags = Array.from(sommernoteWriteArea.getElementsByTagName('img'));

        // 람다 사용함. ( 공부해보시면 좋을것 같네요.. )
        iframeTags.forEach(iframe => {
            srcArray.push(iframe.src);
        });
        imgsTags.forEach(img => {
            srcArray.push(img.src);
        });

        const filesArrayLenght = filesArray.length;
        for (let i = 0; i < filesArrayLenght; i++) {
            const itrFile = filesArray[i];
            formData.append('files[]', itrFile);

            // 에디터 안에 주소가 쓰이고 있으면
            if (srcArray.includes(itrFile.name)) {

                console.log(itrFile.name);

                // 이유는 모르겠는데 서버에서 받는 파일 이름은 스키마나 baseUrl값이 없어져있었다.
                // 그래서 여기서 문자열을 변환해주도록 만들었다.
                const pathSplitArray = itrFile.name.split('/');
                content = content.replace(itrFile.name, pathSplitArray[pathSplitArray.length - 1]);

                // 왼쪽부터 (서버에서 받을때 사용할 파일 배열키, 파일)
                // 서버에서 항상 배열로 받을려면 키 뒤에 '[]' 필요.
            }
            // 이제 url 객체는 필요없으니까 메모리 해제
            URL.revokeObjectURL(itrFile.name);
        }

        formData.append("editordata", content);

        console.log(content);


        const httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = () => {
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
                if (httpRequest.status === 200) {
                    console.log(httpRequest.response);
                    alert('등록완료');
                    location.href = "http://192.168.101.129/risingproject/board_free.php";
                } else {
                    alert("게시물 등록중 오류가 발생했습니다.");
                    submit.disabled = false;
                }
            }
        }
        httpRequest.open('post', 'freePost_insert.php', true);
        httpRequest.send(formData);
    }
}




// filesArray 는 서버로 전송하기 전에 임시로 uri들을 들고 있는 배열이다.
const filesArray = [];

// 드래그앤 드랍시 동작하는 코드
function setFiles(files) {
    const filesLenght = files.length;
    for (let i = 0; i < filesLenght; i++) {
        const file = files[i];
        if (file.type.match('image.*')) {
            // 임시 url 생성하는 부분
            const url = URL.createObjectURL(file);
            file.name = url;
            // filesArray 이름을 방금 받은 url 로 담아둔다. (나중에 서버로 파일 보낼때 필요)
            filesArray.push(new File([file], url, {
                type: file.type
            }));
            console.log("이미지삽입:" + url);
            // 에디터에 이미지 붙여넣기.
            $('#summernote').summernote('insertImage', url);


        } else if (file.type.match('video.*')) {
            // 임시 url 생성하는 부분
            const url = URL.createObjectURL(file);
            console.log(file.type);
            filesArray.push(new File([file], url, {
                type: file.type
            }));
            const videoIframe = document.createElement("iframe");
            videoIframe.src = url;
            videoIframe.width = "640px";
            videoIframe.height = "480px";
            videoIframe.frameBorder = "0";
            videoIframe.className = "note-video-clip";

            // 에디터에 영상 붙여넣기 note-editable 에 붙여넣으면 됌.
            const sommernoteWriteArea = document.getElementsByClassName("note-editable")[0];
            sommernoteWriteArea.appendChild(videoIframe);

            // 비디오나 이미지가 아니면
        } else {
            alert('지원하지 않는 파일 타입입니다.');
        }
    }
}
