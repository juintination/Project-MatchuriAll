<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // DB 정보 불러오기
    include '../db_info.php';

    // 현재 시간을 포함한 timestamp 생성
    date_default_timezone_set('Asia/Seoul');
    $currentTime = date('Y-m-d H:i:s');

    // RECEIPT 테이블에 데이터 삽입
    $receiptQuery = "INSERT INTO RECEIPT (RECEIPT_NUM, RECEIPT_TIME, RECEIPT_PRICE, PAYMENT_METHOD, STORE_ID, CUSTOMER_ID) 
                     VALUES ('{$data['receipt_num']}', TO_DATE('$currentTime', 'YYYY-MM-DD HH24:MI:SS'), '{$data['receipt_price']}', '{$data['payment_method']}', '{$data['store_id']}', '{$data['customer_id']}')";
    
    $stmt = oci_parse($conn, $receiptQuery);
    oci_execute($stmt);

    oci_free_statement($stmt);
    oci_close($conn);
}
?>