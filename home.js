
let key = "wJw%2BTdV9J5yrB4rM0HYG0YlhLtTtos5EoUBuqJwba%2FwmmwufGmUkKyoG4rS%2BQAYbycOaRarMU2fEckCLIH4wCA%3D%3D";
let url = `https://apis.data.go.kr/1160100/service/GetMarketIndexInfoService/getStockMarketIndex?serviceKey=${key}&numOfRows=1&pageNo=1&resultType=json&idxNm=코스피
`;
let KOSDAQclprElement = document.getElementById('KOSDAQclpr');
let KOSDAQvsElement = document.getElementById('KOSDAQvs');
let KOSDAQfltRtElement = document.getElementById('KOSDAQfltRt');
let KOSPIclprElement = document.getElementById('KOSPIclpr');
let KOSPIvsElement = document.getElementById('KOSPIvs');
let KOSPIfltRtElement = document.getElementById('KOSPIfltRt');

let NASDAQclpr = document.getElementById('NASDAQclpr');
let NASDAQvs = document.getElementById('NASDAQvs');
let NASDAQfltRt = document.getElementById('NASDAQfltRt');


let SNPclpr = document.getElementById('S&Pclpr');
let SNPvs = document.getElementById('S&Pvs');
let SNPfltRt = document.getElementById('S&PfltRt');
//한국 정보포탈 제공 api 
//kosdaq 및 kospi 지수 정보를 실시간으로 받아와서 처리한다.
//json으로 받아와서 종가,등락률,등락정보를 반영함 
fetch(url)
    .then(response => response.json())
    .then(
        data => {
            console.log(data);
            let KOSPIclpr = data.response.body.items.item[0]['clpr'];
            let KOSPIvs = data.response.body.items.item[0]['vs'];
            let KOSPIfltRt = data.response.body.items.item[0]['fltRt'];

            if (KOSPIvs > 0) {
                KOSPIclprElement.style.color = "red";
                KOSPIvsElement.style.color = "red";
                KOSPIfltRtElement.style.color = "red";
                KOSPIclprElement.textContent = KOSPIclpr;
                KOSPIvsElement.textContent = KOSPIvs;
                KOSPIfltRtElement.textContent = KOSPIfltRt + "%";
            } else {
                KOSPIclprElement.style.color = "blue";
                KOSPIvsElement.style.color = "blue";
                KOSPIfltRtElement.style.color = "blue";
                KOSPIclprElement.textContent = KOSPIclpr;
                KOSPIvsElement.textContent = KOSPIvs;
                KOSPIfltRtElement.textContent = KOSPIfltRt + "%";
            }

        }
    ).catch(error => {
        console.error("Error발생 :", error);
        throw error;
    })


let urlKOSDAQ = `https://apis.data.go.kr/1160100/service/GetMarketIndexInfoService/getStockMarketIndex?serviceKey=${key}&numOfRows=1&pageNo=1&resultType=json&idxNm=코스닥
`;
fetch(urlKOSDAQ)
    .then(response => response.json())
    .then(
        data => {
            console.log(data);
            let KOSDAQclpr = data.response.body.items.item[0]['clpr'];
            let KOSDAQvs = data.response.body.items.item[0]['vs'];
            let KOSDAQfltRt = data.response.body.items.item[0]['fltRt'];


            console.log(KOSDAQclpr);
            console.log(KOSDAQvsElement);

            if (KOSDAQvs > 0) {
                KOSDAQclprElement.style.color = "red";
                KOSDAQvsElement.style.color = "red";
                KOSDAQfltRtElement.style.color = "red";
                KOSDAQclprElement.textContent = KOSDAQclpr;
                KOSDAQvsElement.textContent = KOSDAQvs;
                KOSDAQfltRtElement.textContent = KOSDAQfltRt + "%";
            } else {
                KOSDAQclprElement.style.color = "blue";
                KOSDAQvsElement.style.color = "blue";
                KOSDAQfltRtElement.style.color = "blue";
                KOSDAQclprElement.textContent = KOSDAQclpr;
                KOSDAQvsElement.textContent = KOSDAQvs;
                KOSDAQfltRtElement.textContent = KOSDAQfltRt + "%";
            }
        }
    ).catch(error => {
        console.error("Error발생 :", error);
        throw error;
    });


if (NASDAQvs.innerText.substring(0, 1) === "+") {
    NASDAQclpr.style.color = "red";
    NASDAQvs.style.color = "red";
    NASDAQfltRt.style.color = "red";
} else {
    NASDAQclpr.style.color = "blue";
    NASDAQvs.style.color = "blue";
    NASDAQfltRt.style.color = "blue";
}

if (SNPvs.innerText.substring(0, 1) === "+") {
    SNPclpr.style.color = "red";
    SNPvs.style.color = "red";
    SNPfltRt.style.color = "red";
} else {
    SNPclpr.style.color = "blue";
    SNPvs.style.color = "blue";
    SNPfltRt.style.color = "blue";
}
//나스닥 snp 색깔 설정 