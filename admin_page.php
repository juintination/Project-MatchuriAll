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
            WHERE STORE.store_id = $store_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $adminRow = $result->fetch_assoc();

        // 관리자 정보를 출력
        echo "<p><strong>Admin Name:</strong> " . $adminRow['admin_name'] . "</p>";
        echo "<p><strong>Date of Birth:</strong> " . $adminRow['admin_birth'] . "</p>";
        echo "<p><strong>Phone Number:</strong> " . $adminRow['admin_phone'] . "</p>";
        echo "<p><strong>Email:</strong> " . $adminRow['admin_email'] . "</p>";

        // 관리자의 프로필 정보를 데이터베이스에서 가져오는 쿼리
        $profile_id = $adminRow['profile_id'];
        if (isset($profile_id)) {
            $sql = "SELECT * FROM PROFILE WHERE profile_id = $profile_id";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
        
                // 사용자의 프로필 정보를 출력
                echo "<p><strong>Profile ID:</strong> " . $row['profile_id'] . "</p>";
                echo "<p><strong>Profile Picture:</strong> " . $row['profile_pic'] . "</p>";

                // 프로필 사진을 출력
                if (!empty($row['profile_pic'])) {
                    echo "<img src='" . $row['profile_pic'] . "' alt='Profile Picture' class='profile_pic_style'>";
                } else {
                    echo "<p>No profile picture available.</p>";
                }

                echo "<p><strong>Profile Info:</strong> " . $row['profile_info'] . "</p>";

                // 프로필 수정 버튼 추가
                echo "<a href='edit_profile.php?profile_id=$profile_id&store_id=$store_id'>프로필 수정</a>";
                
            } else {
                echo "Profile not found.";
            }
        } else {
            echo "Profile ID is not set.";
        }

        // 가게 정보를 출력
        echo "<p><strong>Store Name:</strong> " . $adminRow['store_name'] . "</p>";
        echo "<p><strong>Classification:</strong> " . $adminRow['classification'] . "</p>";

        // PRODUCT 리스트 출력
        echo "<h2>Product List</h2>";
        echo "<table>";
        echo "<tr><th>상품 ID</th><th>상품명</th><th>상품 종류</th><th>가격(원)</th><th>재고</th><th>수정</th></tr>";

        // 해당 STORE에 속한 PRODUCT들을 조회하는 쿼리
        $sqlProduct = "SELECT * FROM PRODUCT WHERE store_id = $store_id";
        $resultProduct = $conn->query($sqlProduct);

        if ($resultProduct->num_rows > 0) {
            while ($rowProduct = $resultProduct->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $rowProduct['product_id'] . "</td>";
                echo "<td>" . $rowProduct['product_name'] . "</td>";
                echo "<td>" . $rowProduct['product_var'] . "</td>";
                echo "<td>" . $rowProduct['product_price'] . "</td>";
                echo "<td>" . $rowProduct['product_stock'] . "</td>";
                echo "<td><a href='edit_product.php?product_id=" . $rowProduct['product_id'] . "&store_id=$store_id'>Edit</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No products found.</td></tr>";
        }
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

    $conn->close();
    ?>
    <h1>처음으로 돌아가기</h1>
    <form action="index.php">
        <input type="submit" value="돌아가기">
    </form>
</body>
</html>