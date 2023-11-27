<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
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
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Admin Page</h1>

    <?php
    // DB 정보 불러오기
    include 'db_info.php';

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
        // 관리자 정보를 출력
        echo "<p><strong>Admin Name:</strong> " . $adminRow['ADMIN_NAME'] . "</p>";
        echo "<p><strong>Date of Birth:</strong> " . $adminRow['ADMIN_BIRTH'] . "</p>";
        echo "<p><strong>Phone Number:</strong> " . $adminRow['ADMIN_PHONE'] . "</p>";
        echo "<p><strong>Email:</strong> " . $adminRow['ADMIN_EMAIL'] . "</p>";

        // 관리자의 프로필 정보를 데이터베이스에서 가져오는 쿼리
        $profile_id = $adminRow['PROFILE_ID'];
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

            echo "<p><strong>Profile Info:</strong> " . $rowProfile['PROFILE_INFO'] . "</p>";

            // 프로필 수정 버튼
            echo "<a href='edit_profile.php?profile_id=$profile_id&store_id=$store_id'>프로필 수정</a>";

            oci_free_statement($stmtProfile);
        } else {
            echo "Profile ID is not set.";
        }

        // 가게 정보를 출력
        echo "<p><strong>Store Name:</strong> " . $adminRow['STORE_NAME'] . "</p>";
        echo "<p><strong>Classification:</strong> " . $adminRow['CLASSIFICATION'] . "</p>";

        // PRODUCT 리스트 출력
        echo "<h2>Product List</h2>";
        echo "<table>";
        echo "<tr><th>상품 ID</th><th>상품명</th><th>상품 종류</th><th>가격(원)</th><th>재고</th><th>수정</th></tr>";

        // 해당 STORE에 속한 PRODUCT들을 조회하는 쿼리
        $sqlProduct = "SELECT * FROM PRODUCT WHERE store_id = :store_id";
        $stmtProduct = oci_parse($conn, $sqlProduct);
        oci_bind_by_name($stmtProduct, ':store_id', $store_id);
        oci_execute($stmtProduct);

        while ($rowProduct = oci_fetch_assoc($stmtProduct)) {
            echo "<tr>";
            echo "<td>" . $rowProduct['PRODUCT_ID'] . "</td>";
            echo "<td>" . $rowProduct['PRODUCT_NAME'] . "</td>";
            echo "<td>" . $rowProduct['PRODUCT_VAR'] . "</td>";
            echo "<td>" . $rowProduct['PRODUCT_PRICE'] . "</td>";
            echo "<td>" . $rowProduct['PRODUCT_STOCK'] . "</td>";
            echo "<td><a href='edit_product.php?product_id=" . $rowProduct['PRODUCT_ID'] . "&store_id=$store_id'>Edit</a></td>";
            echo "</tr>";
        }

        oci_free_statement($stmtProduct);
        echo "</table>";

        // 상품 추가 버튼
        echo "<h2>Add Product</h2>";
        echo "<form action='add_product.php' method='post'>";
        echo "<label for='productName'>Product Name:</label>";
        echo "<input type='text' name='productName' required><br>";

        echo "<label for='productVar'>Product Variant:</label>";
        echo "<input type='text' name='productVar' required><br>";

        echo "<label for='productPrice'>Price:</label>";
        echo "<input type='number' name='productPrice' required><br>";

        echo "<input type='hidden' name='store_id' value='$store_id'>";
        echo "<input type='submit' value='Add Product'>";
        echo "</form>";

    } else {
        echo "Admin not found.";
    }

    oci_free_statement($stmt);
    oci_close($conn);
    ?>

    <h1>키오스크 들어가기</h1>
    <form action="kiosk_login.php" method="get">
        <input type="hidden" name="store_id" value="<?php echo $store_id; ?>">
        <input type="submit" value="키오스크 들어가기">
    </form>

    <h1>처음으로 돌아가기</h1>
    <form action="index.php">
        <input type="submit" value="처음으로 돌아가기">
    </form>
</body>
</html>