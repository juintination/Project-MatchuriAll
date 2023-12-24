<!DOCTYPE html>
<html>
<head>
    <title>Order Detail</title>
    <style>
        body{
            background-color: #4d59db;
        }
        h1 {
            text-align: center;
            margin-top:50px;
            margin-bottom: 30px;
            color: whitesmoke;
            font-family: 'Nova Square', sans-serif;
        }
        /* 테이블 스타일 */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }
        h2 {
            margin-top: 30px;
            font-family: 'Noto Sans KR', sans-serif;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .order-detail-link {
            text-decoration: none;
            color: #848b91;
            font-weight: bold;
        }

        .order-detail-link:hover {
            text-decoration: underline;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nova+Square&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@600&display=swap" rel="stylesheet">
</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <h1>Order Detail</h1><br>
<div class='container'>
    <?php
    // DB 정보 불러오기
    include '../db_info.php';

    // 구매 번호 가져오기
    $receipt_id = $_GET['receipt_id'];

    // 해당 구매 내역을 조회하는 쿼리
    $sqlReceipt = "SELECT * FROM RECEIPT WHERE receipt_id = :receipt_id";
    $stmtReceipt = oci_parse($conn, $sqlReceipt);
    oci_bind_by_name($stmtReceipt, ':receipt_id', $receipt_id);
    oci_execute($stmtReceipt);

    // 구매 내역 표 헤더 출력
    echo "<h2><i class='fa fa-shopping-cart' aria-hidden='true'></i> 구매 내역</h2>";
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
        echo "<td>" . $purchaseDate->format('Y년 m월 d일 H시 i분 s초') . "</td>";

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
    echo "<h2><i class='fa fa-list-ul' aria-hidden='true'></i> 상세 내역</h2>";
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
    </div>
</body>
</html>
