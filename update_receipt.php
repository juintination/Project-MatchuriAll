<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // DB 정보 불러오기
    include 'db_info.php';

    // 현재 시간을 포함한 timestamp 생성
    date_default_timezone_set('Asia/Seoul');
    $currentTime = date('Y-m-d H:i:s');

    // RECEIPT 테이블에 데이터 삽입
    $receiptQuery = "INSERT INTO RECEIPT (receipt_num, receipt_time, receipt_price, payment_method, store_id, user_id) 
                     VALUES ('{$data['receipt_num']}', '$currentTime', '{$data['receipt_price']}', '{$data['payment_method']}', '{$data['store_id']}', '{$data['user_id']}')";
    
    $conn->query($receiptQuery);

    $conn->close();
}
?>
