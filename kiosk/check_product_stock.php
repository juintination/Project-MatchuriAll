<?php
// DB 정보 불러오기
include '../db_info.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $quantity = $_GET['quantity'];

    // 해당 상품의 현재 재고 확인
    $stock_query = "SELECT PRODUCT_STOCK FROM PRODUCT WHERE PRODUCT_ID = $product_id";
    $stock_stmt = oci_parse($conn, $stock_query);
    oci_execute($stock_stmt);

    if ($row = oci_fetch_assoc($stock_stmt)) {
        $current_stock = $row['PRODUCT_STOCK'];

        // 결제된 수량만큼 재고 감소
        $new_stock = $current_stock - $quantity;

        // 재고가 0 미만인 경우를 알림
        if ($new_stock < 0) {
            // HTTP 상태 코드 변경 (400 Bad Request)
            http_response_code(400);
            echo "재고가 부족합니다. 상품 ID: $product_id, 현재 재고: $current_stock";
        } else {
            http_response_code(200);
        }
    }
    oci_free_statement($stock_stmt);
}

oci_close($conn);
?>