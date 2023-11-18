<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // 데이터베이스 연결 설정
    $servername = "localhost";
    $username = "root";
    $password = "admin";
    $database = "demoDB";

    // 데이터베이스 연결
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("데이터베이스 연결 실패: " . $conn->connect_error);
    }

    // 현재 시간을 포함한 timestamp 생성
    $currentTime = date('Y-m-d H:i:s');

    // RECEIPT 테이블에 데이터 삽입
    $receiptQuery = "INSERT INTO RECEIPT (receipt_num, receipt_time, receipt_price, payment_method, store_id, user_id) 
                     VALUES ('{$data['receipt_num']}', '$currentTime', '{$data['receipt_price']}', '{$data['payment_method']}', '{$data['store_id']}', '{$data['user_id']}')";
    
    $conn->query($receiptQuery);

    $conn->close();
}
?>
