<!DOCTYPE html>
<html>
<head>
    <title>Order Detail</title>
    <style>
        /* 테이블 스타일 */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Order Detail</h1>

    <?php
    // DB 정보 불러오기
    include 'db_info.php';

    // 구매 번호 가져오기
    $receipt_id = $_GET['receipt_id'];

    // 해당 구매 내역을 조회하는 쿼리
    $sqlReceipt = "SELECT * FROM RECEIPT WHERE receipt_id = :receipt_id";
    $stmtReceipt = oci_parse($conn, $sqlReceipt);
    oci_bind_by_name($stmtReceipt, ':receipt_id', $receipt_id);
    oci_execute($stmtReceipt);

    // 구매 내역 표 헤더 출력
    echo "<h2>구매 내역</h2>";
    echo "<table>";
    echo "<tr>";
    echo "<th>구매 번호</th>";
    echo "<th>가격(원)</th>";
    echo "<th>결제 방법</th>";
    echo "<th>구매 일자</th>";
    echo "<th>이전으로 돌아가기</th>";
    echo "</tr>";

    $receiptRow = oci_fetch_assoc($stmtReceipt);

    if ($receiptRow) {
        echo "<tr class='receipt-item'>";
        echo "<td>" . $receiptRow['RECEIPT_ID'] . "</td>";
        echo "<td>" . $receiptRow['RECEIPT_PRICE'] . "</td>";
        echo "<td>" . $receiptRow['PAYMENT_METHOD'] . "</td>";
        
        // 날짜 포맷팅
        $purchaseDate = date_create_from_format("d-M-y h.i.s A", $receiptRow['RECEIPT_TIME']);
        echo "<td>" . $purchaseDate->format('d-M-y h.i.s A') . "</td>";

        echo "<td><a class='order-detail-link' href='javascript:history.go(-1)'>돌아가기</a></td>";

        echo "</tr>";
    }

    echo "</table>";

    // 해당 구매 번호에 대한 상세 내역을 조회하는 쿼리
    $sqlOrderDetail = "SELECT * FROM ORDER_DETAIL WHERE receipt_id = :receipt_id";
    $stmtOrderDetail = oci_parse($conn, $sqlOrderDetail);
    oci_bind_by_name($stmtOrderDetail, ':receipt_id', $receipt_id);
    oci_execute($stmtOrderDetail);

    // 상세 내역 표 헤더 출력
    echo "<h2>상세 내역</h2>";
    echo "<table>";
    echo "<tr>";
    echo "<th>영수증 ID</th>";
    echo "<th>상품 ID</th>";
    echo "<th>상품명</th>";
    echo "<th>수량</th>";
    echo "<th>가격(원)</th>";
    echo "</tr>";

    while ($orderDetailRow = oci_fetch_assoc($stmtOrderDetail)) {
        echo "<tr>";
        echo "<td>" . $orderDetailRow['RECEIPT_ID'] . "</td>";
        echo "<td>" . $orderDetailRow['PRODUCT_ID'] . "</td>";

        // 수정된 부분: PRODUCT 테이블에서 profile_id를 기반으로 product_name을 가져오기
        $productId = $orderDetailRow['PRODUCT_ID'];
        $sqlProductName = "SELECT product_name FROM PRODUCT WHERE product_id = :product_id";
        $stmtProductName = oci_parse($conn, $sqlProductName);
        oci_bind_by_name($stmtProductName, ':product_id', $productId);
        oci_execute($stmtProductName);
        $productRow = oci_fetch_assoc($stmtProductName);
        $productName = ($productRow) ? $productRow['PRODUCT_NAME'] : "Product not found";

        echo "<td>" . $productName . "</td>";
        echo "<td>" . $orderDetailRow['PRODUCT_COUNT'] . "</td>";
        echo "<td>" . $orderDetailRow['INTERMEDIATE_PRICE'] . "</td>";

        echo "</tr>";
    }

    echo "</table>";

    oci_free_statement($stmtOrderDetail);
    oci_close($conn);
    ?>
</body>
</html>
