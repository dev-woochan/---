//최근조회한 주식들을 쿠키에 저장하는 스크립트 a태그의 value값을 불러와서 쿠키에 저장한다.

let stockLinks = document.getElementsByClassName('stockLink');

for (let i = 0; i < stockLinks.length; i++) {
    stockLinks[i].addEventListener('click', function () {
        console.log("클릭됨");
        let stockName = stockLinks[i].innerText; // 주식이름 변수에 저장
        let stockList = [];
        if (getCookie("recentStock") !== "") {
            stockList = JSON.parse(getCookie("recentStock"));
            document.cookie = "recentStock" + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"; //삭제하기 
        }
        stockList.unshift(stockName);
        if (stockList.length > 10) {
            stockList.pop();
        }
        stockJson = JSON.stringify(stockList);
        setCookie("recentStock", stockJson, 30); // recentStock = 주식명 , 30 일간 
    });
}

function setCookie(name, value, days) { //쿠키 만드는 함수 
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + ";"
}



function getCookie(name) { //쿠키 가져오기 쿠키는 key=value; key2=value2; 로 저장됨 
    var cookieName = name + "=";
    var decodedCookie = decodeURIComponent(document.cookie);//모든 쿠키String 가져오기 
    var cookieArray = decodedCookie.split(';'); // 분리해서 배열로 만들기 
    for (var i = 0; i < cookieArray.length; i++) { //배열중 일치하는 key를 가져와서 return해준다 
        var cookie = cookieArray[i].trim();
        if (cookie.indexOf(cookieName) == 0) {
            return cookie.substring(cookieName.length, cookie.length); //잘라서 value만 가져옴
        }
    }
    return "";
}