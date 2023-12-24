<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin : 0 auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #5865f5;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4d59db;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #666;
        }
        h1{
            font-family: 'Nova Square', sans-serif;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nova+Square&display=swap" rel="stylesheet">
</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <h1><i class="fa fa-pencil" aria-hidden="true"></i> Edit Product</h1>

    <?php
    // DB 정보 불러오기
    include '../db_info.php';

    $store_id = $_GET['store_id'];

    // GET 파라미터로 전달된 product_id 확인
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];

        // 상품 정보를 데이터베이스에서 가져오는 쿼리
        $sql = "SELECT * FROM PRODUCT WHERE product_id = $product_id";
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);

        if ($row) {
            // 상품 정보 수정 폼
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='product_id' value='$product_id'>";

            // 상품 이름 변경
            echo "<label for='product_name'>상품 이름:</label>";
            echo "<input type='text' name='product_name' value='" . $row['PRODUCT_NAME'] . "'><br>";

            // 상품 가격 변경
            echo "<label for='product_price'>상품 가격:</label>";
            echo "<input type='number' name='product_price' value='" . $row['PRODUCT_PRICE'] . "'><br>";

            // 상품 재고 변경
            echo "<label for='product_stock'>상품 재고:</label>";
            echo "<input type='number' name='product_stock' value='" . $row['PRODUCT_STOCK'] . "'><br>";

            // 수정 완료 버튼
            echo "<input type='submit' name='submit' value='상품 수정 완료'>";

            // 상품 삭제 버튼
            echo "<input type='submit' name='delete' value='상품 삭제'>";
            echo "</form>";

            // 수정 완료 또는 삭제 버튼이 눌렸을 때
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['submit'])) {
                    // 상품 정보 업데이트 쿼리
                    $new_product_name = $_POST['product_name'];
                    $new_product_price = $_POST['product_price'];
                    $new_product_stock = $_POST['product_stock'];

                    // Check if product stock is less than 0
                    if ($new_product_stock < 0) {
                        echo '<script>alert("상품 재고는 0보다 작을 수 없습니다.");</script>';
                    } else {
                        // Update the database
                        $update_query = "UPDATE PRODUCT SET PRODUCT_NAME = '$new_product_name', PRODUCT_PRICE = $new_product_price, PRODUCT_STOCK = $new_product_stock WHERE PRODUCT_ID = $product_id";
                        $stmt = oci_parse($conn, $update_query);
                        oci_execute($stmt);

                        echo '<script>alert("상품 정보가 성공적으로 업데이트되었습니다."); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';
                    }
                } else if (isset($_POST['delete'])) {
                    // 상품 삭제 쿼리
                    $delete_query = "DELETE FROM PRODUCT WHERE PRODUCT_ID = $product_id";
                    $stmt = oci_parse($conn, $delete_query);
                    oci_execute($stmt);

                    echo '<script>alert("상품이 성공적으로 삭제되었습니다."); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';
                }
            }
        } else {
            echo '<script>alert("Product not found."); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';
        }

        oci_free_statement($stmt);
    } else {
        echo '<script>alert("Product ID is not set."); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';
    }

    oci_close($conn);
    ?>

    <a href="admin_page.php?store_id=<?php echo $store_id; ?>"><i class="fa fa-arrow-left" aria-hidden="true"></i> 뒤로 가기</a>
</body>
</html>
