<?php
// DB 정보 불러오기
include 'db_info.php';

if (isset($_GET['product_id']) && isset($_GET['quantity'])) {
    $product_id = $_GET['product_id'];
    $quantity = $_GET['quantity'];

    // 해당 상품의 현재 재고 확인
    $stock_query = "SELECT product_stock FROM PRODUCT WHERE product_id = $product_id";
    $stock_result = $conn->query($stock_query);

    if ($stock_result->num_rows > 0) {
        $stock_row = $stock_result->fetch_assoc();
        $current_stock = $stock_row['product_stock'];

        // 결제된 수량만큼 재고 감소
        $new_stock = $current_stock - $quantity;

        // 재고가 0 미만으로 가지 않도록 보정
        if ($new_stock < 0) {
            $new_stock = 0;
        }

        // 상품의 재고 업데이트
        $update_query = "UPDATE PRODUCT SET product_stock = $new_stock WHERE product_id = $product_id";
        $conn->query($update_query);
    }
}

$conn->close();
?>
