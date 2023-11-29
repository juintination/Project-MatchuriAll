<!DOCTYPE html>
<html>
<head>
    <title>Customer Page</title>
    <style>
        /* 프로필 사진 이미지 스타일 */
        img.profile_pic_style {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
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

        th, td {
            padding: 10px;
            text-align: left;
        }

        /* RECEIPT 아이템 스타일 */
        .receipt-item {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Customer Page</h1>
    
    <?php
    // DB 정보 불러오기
    include 'db_info.php';

    // 고객 정보를 데이터베이스에서 가져오는 쿼리
    $customer_id = $_GET['customer_id'];
    $store_id = $_GET['store_id'];

    // Use bind variables to prevent SQL injection
    $sql = "SELECT * FROM CUSTOMER WHERE customer_id = :customer_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':customer_id', $customer_id);
    oci_execute($stmt);

    $customerRow = oci_fetch_assoc($stmt);

    if ($customerRow) {
        // 고객 정보를 출력
        echo "<p><strong>Customer Name:</strong> " . $customerRow['CUSTOMER_NAME'] . "</p>";
        echo "<p><strong>Date of Birth:</strong> " . $customerRow['CUSTOMER_BIRTH'] . "</p>";
        echo "<p><strong>Gender:</strong> " . $customerRow['CUSTOMER_SEX'] . "</p>";
        echo "<p><strong>Phone Number:</strong> " . $customerRow['CUSTOMER_PHONE'] . "</p>";
        echo "<p><strong>Point:</strong> " . $customerRow['CUSTOMER_POINT'] . "</p>";

        // 고객의 프로필 정보를 데이터베이스에서 가져오는 쿼리
        $profile_id = $customerRow['PROFILE_ID'];

        if (isset($profile_id)) {
            $sqlProfile = "SELECT * FROM PROFILE WHERE profile_id = :profile_id";
            $stmtProfile = oci_parse($conn, $sqlProfile);
            oci_bind_by_name($stmtProfile, ':profile_id', $profile_id);
            oci_execute($stmtProfile);

            $profileRow = oci_fetch_assoc($stmtProfile);

            if ($profileRow) {
                // 고객의 프로필 정보를 출력
                echo "<p><strong>Profile ID:</strong> " . $profileRow['PROFILE_ID'] . "</p>";
                
                // 프로필 사진을 출력
                if (!empty($profileRow['PROFILE_PIC'])) {
                    $profile_pic = base64_encode($profileRow['PROFILE_PIC']->load());
                    echo "<img src='data:image/png;base64, $profile_pic' alt='Profile Picture' class='profile_pic_style'>";
                } else {
                    echo "<p>No profile picture available.</p>";
                }
                
                echo "<p><strong>Profile Info:</strong> " . $profileRow['PROFILE_INFO'] . "</p>";

                // 프로필 수정 버튼
                echo "<a href='edit_profile.php?profile_id=$profile_id&store_id=$store_id&customer_id=$customer_id'>프로필 수정</a>";
                
            } else {
                echo "Profile not found.";
            }

            oci_free_statement($stmtProfile);
        } else {
            echo "Profile ID is not set.";
        }

        // 해당 고객의 구매 내역을 나열하는 쿼리
        $sqlReceipt = "SELECT * FROM RECEIPT WHERE customer_id = :customer_id";
        $stmtReceipt = oci_parse($conn, $sqlReceipt);
        oci_bind_by_name($stmtReceipt, ':customer_id', $customer_id);
        oci_execute($stmtReceipt);
        
        // 표 헤더 출력
        echo "<h2>구매 내역</h2>";
        echo "<table>";
        echo "<tr>";
        echo "<th>영수증 ID</th>";
        echo "<th>가격(원)</th>";
        echo "<th>결제 방법</th>";
        echo "<th>구매 일자</th>";
        echo "<th>상세 내역 보기</th>";
        echo "</tr>";

        while ($receiptRow = oci_fetch_assoc($stmtReceipt)) {
            echo "<tr class='receipt-item'>";
            echo "<td>" . $receiptRow['RECEIPT_ID'] . "</td>";
            echo "<td>" . $receiptRow['RECEIPT_PRICE'] . "</td>";
            echo "<td>" . $receiptRow['PAYMENT_METHOD'] . "</td>";
            
            // 날짜 포맷팅
            $purchaseDate = date_create_from_format("d-M-y h.i.s A", $receiptRow['RECEIPT_TIME']);
            echo "<td>" . $purchaseDate->format('d-M-y h.i.s A') . "</td>";

            // 상세 내역 보기 링크
            echo "<td><a class='order-detail-link' href='get_order_detail.php?receipt_id=" . $receiptRow['RECEIPT_ID'] . "'>자세히</a></td>";

            echo "</tr>";
        }

        echo "</table>";

        oci_free_statement($stmtReceipt);

    } else {
        echo "Customer not found.";
    }

    oci_free_statement($stmt);
    oci_close($conn);
    ?>
    <h1>처음으로 돌아가기</h1>
    <form action="index.php">
        <input type="submit" value="돌아가기">
    </form>
</body>
</html>
