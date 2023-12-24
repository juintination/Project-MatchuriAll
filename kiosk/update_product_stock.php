<?php
// DB 정보 불러오기
include '../db_info.php';

if (isset($_GET['product_id']) && isset($_GET['quantity'])) {
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

        // 재고가 0 미만으로 가지 않도록 보정
        if ($new_stock < 0) {
            $new_stock = 0;
        }

        // 상품의 재고 업데이트
        $update_query = "UPDATE PRODUCT SET PRODUCT_STOCK = $new_stock WHERE PRODUCT_ID = $product_id";
        $update_stmt = oci_parse($conn, $update_query);
        oci_execute($update_stmt);

        oci_free_statement($update_stmt);
    }

    oci_free_statement($stock_stmt);
}

oci_close($conn);
?>