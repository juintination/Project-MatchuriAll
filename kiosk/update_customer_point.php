<?php
// DB 정보 불러오기
include '../db_info.php';

if (isset($_POST['customer_id']) && isset($_POST['total_receipt_price'])) {
    $customer_id = $_POST['customer_id'];
    $total_receipt_price = $_POST['total_receipt_price'];

    // 추가할 포인트 계산(예시: 결제한 금액의 1%를 포인트로 추가)
    $points_to_add = $total_receipt_price * 0.01;

    // CUSTOMER 테이블에서 고객 포인트 업데이트
    $updatePointsSql = "UPDATE CUSTOMER SET customer_point = customer_point + :pointsToAdd WHERE customer_id = :customerId";
    $updatePointsStmt = oci_parse($conn, $updatePointsSql);
    oci_bind_by_name($updatePointsStmt, ':pointsToAdd', $points_to_add);
    oci_bind_by_name($updatePointsStmt, ':customerId', $customer_id);

    if (oci_execute($updatePointsStmt)) {
        echo "고객 포인트가 성공적으로 업데이트되었습니다.";
    } else {
        echo "고객 포인트 업데이트 중 오류가 발생했습니다.";
    }

    oci_free_statement($updatePointsStmt);
    oci_close($conn);
} else {
    echo "필수 매개변수가 누락되었습니다.";
}
?>
