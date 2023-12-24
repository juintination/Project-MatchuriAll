<!DOCTYPE html>
<html>
<head>
    <title>수익 상세 정보</title>
    <link rel="stylesheet" href="css/get_profit.css">
</head>
<body>    
    <div class='container'>
    <h1>수익 상세 정보</h1>
        <?php
        // DB 정보 불러오기
        include '../db_info.php';

        // URL에서 store_id 가져오기
        $store_id = $_GET['store_id'];

        // 가게 정보 출력
        echo "<br><h2>가게 정보</h2>";
        $sqlStore = "SELECT * FROM STORE WHERE store_id = :store_id";
        $stmtStore = oci_parse($conn, $sqlStore);
        oci_bind_by_name($stmtStore, ':store_id', $store_id);
        oci_execute($stmtStore);

        $storeRow = oci_fetch_assoc($stmtStore);
        if ($storeRow) {
            echo "<p><strong>가게 이름:</strong> " . $storeRow['STORE_NAME'] . "</p>";
            echo "<p><strong>분류:</strong> " . $storeRow['CLASSIFICATION'] . "</p>";
        } else {
            echo "가게를 찾을 수 없습니다.";
        }

        oci_free_statement($stmtStore);

        // 영수증 정보 출력
        echo "<br><h2>영수증 정보</h2>";
        $sqlReceipt = "SELECT * FROM RECEIPT WHERE store_id = :store_id ORDER BY RECEIPT_ID ASC";
        $stmtReceipt = oci_parse($conn, $sqlReceipt);
        oci_bind_by_name($stmtReceipt, ':store_id', $store_id);
        oci_execute($stmtReceipt);

        if (oci_fetch_assoc($stmtReceipt)) {
            echo "<table>";
            echo "<tr><th>영수증 ID</th><th>가격</th><th>결제 수단</th><th>구매 시간</th><th>고객 정보</th></tr>";

            do {
                $receiptId = oci_result($stmtReceipt, 'RECEIPT_ID');
                $customerId = oci_result($stmtReceipt, 'CUSTOMER_ID');
                $purchaseTime = oci_result($stmtReceipt, 'RECEIPT_TIME');

                echo "<tr>";
                echo "<td>" . $receiptId . "</td>";
                echo "<td>" . oci_result($stmtReceipt, 'RECEIPT_PRICE') . "</td>";
                echo "<td>" . oci_result($stmtReceipt, 'PAYMENT_METHOD') . "</td>";
                echo "<td>" . $purchaseTime . "</td>";
                echo "<td><a href='user_page.php?store_id=$store_id&customer_id=$customerId'>보러가기</a></td>";
                echo "</tr>";
            } while (oci_fetch($stmtReceipt));

            echo "</table>";
        } else {
            echo "이 상점에 대한 영수증이 없습니다.";
        }

        oci_free_statement($stmtReceipt);

        // 주문 상세 정보 출력
        echo "<br><h2>주문 상세 정보</h2>";
        $orderDetailQuery = "SELECT ORDER_DETAIL.*, PRODUCT.PRODUCT_NAME
                            FROM ORDER_DETAIL
                            INNER JOIN PRODUCT ON ORDER_DETAIL.product_id = PRODUCT.product_id
                            INNER JOIN RECEIPT ON ORDER_DETAIL.receipt_id = RECEIPT.receipt_id
                            WHERE RECEIPT.store_id = :store_id
                            ORDER BY ORDER_DETAIL.receipt_id ASC";

        $stmtOrderDetail = oci_parse($conn, $orderDetailQuery);
        oci_bind_by_name($stmtOrderDetail, ':store_id', $store_id);
        oci_execute($stmtOrderDetail);

        if (oci_fetch($stmtOrderDetail)) {
            echo "<table>";
            echo "<tr><th>영수증 ID</th><th>상품 ID</th><th>상품 이름</th><th>상품 수량</th><th>중간 가격</th></tr>";

            do {
                echo "<tr>";
                echo "<td>" . oci_result($stmtOrderDetail, 'RECEIPT_ID') . "</td>";
                echo "<td>" . oci_result($stmtOrderDetail, 'PRODUCT_ID') . "</td>";
                echo "<td>" . oci_result($stmtOrderDetail, 'PRODUCT_NAME') . "</td>";
                echo "<td>" . oci_result($stmtOrderDetail, 'PRODUCT_COUNT') . "</td>";
                echo "<td>" . oci_result($stmtOrderDetail, 'INTERMEDIATE_PRICE') . "</td>";
                echo "</tr>";
            } while (oci_fetch($stmtOrderDetail));

            echo "</table>";
        } else {
            // 주문 상세 정보가 없을 경우
            echo "<p>이 가게에 대한 주문 상세 정보가 없습니다.</p>";
        }

        oci_free_statement($stmtOrderDetail);

        oci_close($conn);
        ?>
        
        <br>
        <form action="admin_page.php" method="get">
            <input type="hidden" name="store_id" value="<?php echo $store_id; ?>">
            <input type="submit" value="관리자 페이지로 돌아가기" class='button'>
        </form>
    </div>
</body>
</html>
