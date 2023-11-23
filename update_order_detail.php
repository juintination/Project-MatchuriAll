<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data && isset($data['products']) && is_array($data['products'])) {
        // DB 정보 불러오기
        include 'db_info.php';

        // RECEIPT 테이블에서 가장 최근에 삽입된 receipt_id 가져오기
        $getLastReceiptIdQuery = "SELECT MAX(receipt_id) AS max_receipt_id FROM RECEIPT";
        $result = $conn->query($getLastReceiptIdQuery);
        $row = $result->fetch_assoc();
        $receiptId = $row['max_receipt_id'];

        // ORDER_DETAIL 테이블에 각 상품 정보 삽입
        foreach ($data['products'] as $product) {
            $productId = $product['product_id'];
            $productCount = $product['product_count'];
            $intermediatePrice = $product['intermediate_price'];

            $orderDetailQuery = "INSERT INTO ORDER_DETAIL (product_count, intermediate_price, receipt_id, product_id) 
                                VALUES ('$productCount', '$intermediatePrice', '$receiptId', '$productId')";

            $conn->query($orderDetailQuery);
        }

        $conn->close();
    } else {
        // 데이터가 부족하거나 잘못된 형식인 경우 에러 메시지 출력
        http_response_code(400);
        echo "Invalid data format";
    }
}
?>
