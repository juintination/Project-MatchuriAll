<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/adminpage.css">
    <title>Admin Page</title>
    <style>
        /* 프로필 사진 이미지 스타일 */
        img.profile_pic_style {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            // DB 정보 불러오기
            include '../db_info.php';

            // 관리자 정보를 데이터베이스에서 가져오는 쿼리
            $store_id = $_GET['store_id'];
            $sql = "SELECT ADMIN.*, STORE.store_name, STORE.classification
                    FROM ADMIN
                    LEFT JOIN STORE ON ADMIN.admin_id = STORE.admin_id
                    WHERE STORE.store_id = :store_id";

            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':store_id', $store_id);
            oci_execute($stmt);

            $adminRow = oci_fetch_assoc($stmt);

            if ($adminRow) {
                // 관리자의 프로필 정보를 데이터베이스에서 가져오는 쿼리
                $profile_id = $adminRow['PROFILE_ID'];
        ?>
        <a href='../index.php' class='button'>로그아웃</a>
        <a href="../profile/edit_profile.php?profile_id=<?php echo $profile_id; ?>&store_id=<?php echo $store_id; ?>" class='button'>프로필 수정</a>
        <a href="../kiosk/kiosk_login.php?store_id=<?php echo $store_id; ?>" class="button">키오스크 들어가기</a>
        <header>Welcome to the Admin Page</header>
        <div class="store_container">
            <div class="item_store">
                <?php
                    // 가게 정보를 출력
                    echo "<p style='font-size: 50px;'><strong>" . $adminRow['STORE_NAME'] . "</strong></p>";
                    echo "<p style='font-size: 20px; color: #CCC;'> " . $adminRow['CLASSIFICATION'] . "</p>";
                ?>
            </div>
        </div>
        <div class="info_container">
            <div class="item_profile">
                <?php
                    if (isset($profile_id)) {
                        $sqlProfile = "SELECT * FROM PROFILE WHERE profile_id = :profile_id";
                        $stmtProfile = oci_parse($conn, $sqlProfile);
                        oci_bind_by_name($stmtProfile, ':profile_id', $profile_id);
                        oci_execute($stmtProfile);
    
                        $rowProfile = oci_fetch_assoc($stmtProfile);
    
                        // 사용자의 프로필 정보를 출력
                        echo "<p><strong>Profile ID:</strong> " . $rowProfile['PROFILE_ID'] . "</p>";
    
                        // 프로필 사진을 출력
                        if ($rowProfile['PROFILE_PIC']->size() > 0) {
                            $profile_pic = base64_encode($rowProfile['PROFILE_PIC']->load());
                            echo "<img src='data:image/png;base64, $profile_pic' alt='Profile Picture' class='profile_pic_style'>";
                        } else {
                            echo "<p>No profile picture available.</p>";
                        }
                        
                        echo "<p><strong>Profile Info:</strong></p>";
                        echo isset($rowProfile['PROFILE_INFO']) ? "<p>" . $rowProfile['PROFILE_INFO'] . "</p>" : "<p>No Profile Info</p>";
    
                        oci_free_statement($stmtProfile);
                    } else {
                        echo "Profile ID is not set.";
                    }
                ?>
            </div>
            <div class="item_admin">
                <?php
                    // 관리자 정보를 출력
                    echo "<p><strong>Admin Name:</strong> " . $adminRow['ADMIN_NAME'] . "</p>";
    
                    // 날짜 포맷팅
                    $adminBirthdate = date_create_from_format("d-M-y", $adminRow['ADMIN_BIRTH']);
                    echo "<p><strong>Day of Birth:</strong> " . $adminBirthdate->format('Y년 m월 d일') . "</p>";
    
                    echo "<p><strong>Phone Number:</strong> " . $adminRow['ADMIN_PHONE'] . "</p>";
                    echo "<p><strong>Email:</strong> " . $adminRow['ADMIN_EMAIL'] . "</p>";
                ?>
            </div><br><br><br><br><br>
        </div>
        <?php
            // PRODUCT 리스트 출력
            echo '<h2 style="margin-left: 10px;">상품 정보</h2>';
            echo "<table>";
            echo "<tr><th>상품 ID</th><th>상품명</th><th>상품 종류</th><th>가격(원)</th><th>재고</th><th>수정</th></tr>";

            // 해당 STORE에 속한 PRODUCT들을 조회하는 쿼리
            $sqlProduct = "SELECT * FROM PRODUCT WHERE store_id = :store_id ORDER BY product_id ASC";
            $stmtProduct = oci_parse($conn, $sqlProduct);
            oci_bind_by_name($stmtProduct, ':store_id', $store_id);
            oci_execute($stmtProduct);

            $rowProduct = oci_fetch_assoc($stmtProduct);
            if ($rowProduct) {
                do {
                    echo "<tr>";
                    echo "<td>" . $rowProduct['PRODUCT_ID'] . "</td>";
                    echo "<td>" . $rowProduct['PRODUCT_NAME'] . "</td>";
                    echo "<td>" . $rowProduct['PRODUCT_VAR'] . "</td>";
                    echo "<td>" . $rowProduct['PRODUCT_PRICE'] . "</td>";
                    echo "<td>" . $rowProduct['PRODUCT_STOCK'] . "</td>";
                    echo "<td><a href='edit_product.php?product_id=" . $rowProduct['PRODUCT_ID'] . "&store_id=$store_id'>Edit</a></td>";
                    echo "</tr>";
                } while ($rowProduct = oci_fetch_assoc($stmtProduct));

                echo "</table>";
            } else {
                echo "<table>";
                echo "<tr class='receipt-item'>";
                echo "<td colspan='6'>아직 이 가게에 아무런 상품도 등록되지 않았네요!</td>";
                echo "</tr>";
                echo "</table>";
            }

            oci_free_statement($stmtProduct);

            // 총 수익 계산
            $sqlTotalRevenue = "SELECT SUM(RECEIPT.RECEIPT_PRICE) AS TOTAL_REVENUE
            FROM RECEIPT
            WHERE RECEIPT.STORE_ID = :store_id";

            $stmtTotalRevenue = oci_parse($conn, $sqlTotalRevenue);
            oci_bind_by_name($stmtTotalRevenue, ':store_id', $store_id);
            oci_execute($stmtTotalRevenue);

            $totalRevenueRow = oci_fetch_assoc($stmtTotalRevenue);
            $totalRevenue = $totalRevenueRow['TOTAL_REVENUE'];

            oci_free_statement($stmtTotalRevenue);

            // 오늘 하루 동안의 수익
            $sqlTodayRevenue = "SELECT SUM(RECEIPT.RECEIPT_PRICE) AS TODAY_REVENUE
            FROM RECEIPT
            WHERE RECEIPT.STORE_ID = :store_id
            AND TRUNC(RECEIPT.RECEIPT_TIME) = TRUNC(SYSDATE)";

            $stmtTodayRevenue = oci_parse($conn, $sqlTodayRevenue);
            oci_bind_by_name($stmtTodayRevenue, ':store_id', $store_id);
            oci_execute($stmtTodayRevenue);

            $todayRevenueRow = oci_fetch_assoc($stmtTodayRevenue);
            $todayRevenue = $todayRevenueRow['TODAY_REVENUE'];

            oci_free_statement($stmtTodayRevenue);

            // 최근 일주일 동안의 수익
            $sqlLastWeekRevenue = "SELECT SUM(RECEIPT.RECEIPT_PRICE) AS LAST_WEEK_REVENUE
            FROM RECEIPT
            WHERE RECEIPT.STORE_ID = :store_id
            AND TRUNC(RECEIPT.RECEIPT_TIME) >= TRUNC(SYSDATE) - 7";

            $stmtLastWeekRevenue = oci_parse($conn, $sqlLastWeekRevenue);
            oci_bind_by_name($stmtLastWeekRevenue, ':store_id', $store_id);
            oci_execute($stmtLastWeekRevenue);

            $lastWeekRevenueRow = oci_fetch_assoc($stmtLastWeekRevenue);
            $lastWeekRevenue = $lastWeekRevenueRow['LAST_WEEK_REVENUE'];

            oci_free_statement($stmtLastWeekRevenue);

            // 이번 달의 수익
            $sqlLastMonthRevenue = "SELECT SUM(RECEIPT.RECEIPT_PRICE) AS LAST_MONTH_REVENUE
                FROM RECEIPT
                WHERE RECEIPT.STORE_ID = :store_id
                AND TRUNC(RECEIPT.RECEIPT_TIME) >= TRUNC(SYSDATE, 'MM')";

            $stmtLastMonthRevenue = oci_parse($conn, $sqlLastMonthRevenue);
            oci_bind_by_name($stmtLastMonthRevenue, ':store_id', $store_id);
            oci_execute($stmtLastMonthRevenue);

            $lastMonthRevenueRow = oci_fetch_assoc($stmtLastMonthRevenue);
            $lastMonthRevenue = $lastMonthRevenueRow['LAST_MONTH_REVENUE'];

            oci_free_statement($stmtLastMonthRevenue);

            // 수익 정보 표시
            echo "<br>";
            echo '<h2 style="margin-left: 10px;">매출 정보</h2>';
            echo "<table>";
            echo "<tr><th>총 수익</th><th>오늘의 수익</th><th>최근 일주일 동안의 수익</th><th>이번 달의 수익</th><th>자세히 보기</th></tr>";
            echo "<tr>";
            echo "<td>" . number_format($totalRevenue) . "원</td>";
            echo "<td>" . number_format($todayRevenue) . "원</td>";
            echo "<td>" . number_format($lastWeekRevenue) . "원</td>";
            echo "<td>" . number_format($lastMonthRevenue) . "원</td>";
            echo "<td><a href='get_profit.php?store_id=$store_id'>보러가기</a></td>";
            echo "</tr>";
            echo "</table>";


            // 상품 추가 버튼
            echo "<br>";
            echo '<h2 style="margin-left: 10px;">상품 추가하기</h2>';
            echo "<form action='add_product.php' method='post' class='item_input'>";
            echo "<label for='productName'>상품명:</label>";
            echo "<input class='input' type='text' name='productName' required style='border-radius: 5px;'><br>";

            echo "<label for='productVar'>상품 종류:</label>";
            echo "<input class='input' type='text' name='productVar' required style='border-radius: 5px;'><br>";

            echo "<label for='productPrice'>가격(원):</label>";
            echo "<input class='input' type='number' name='productPrice' required style='border-radius: 5px;'><br>";

            echo "<input type='hidden' name='store_id' value='$store_id'>";
            echo "<input type='submit' class='button_product' value='상품 추가하기'>";
            echo "</form>";

        } else {
            echo "Admin not found.";
        }

        oci_free_statement($stmt);
        oci_close($conn);
        ?>
    </div>
</body>
</html>