<?php

include '../dbconfig.php';

$loadStockList = "SELECT name FROM koStock";
$result = mysqli_query($mysqli,$loadStockList);

$stockList = array();

while($row = mysqli_fetch_array($result)){ //0~ 모든 주식정보가 저장됨 배열에 
    $stockList[] = $row['name'];
} 

echo json_encode($stockList); //json화 시켜서 반환 

mysqli_close($mysqli);
?>