<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>

    <?php
    // 데이터베이스 연결 설정
    $servername = "localhost";
    $username = "root";
    $password = "admin";
    $database = "demoDB";

    // 데이터베이스 연결
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("데이터베이스 연결 실패: " . $conn->connect_error);
    }

    $store_id = $_GET['store_id'];

    // GET 파라미터로 전달된 product_id 확인
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];

        // 상품 정보를 데이터베이스에서 가져오는 쿼리
        $sql = "SELECT * FROM PRODUCT WHERE product_id = $product_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // 상품 정보 수정 폼
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='product_id' value='$product_id'>";

            // 상품 이름 변경
            echo "<label for='product_name'>상품 이름:</label>";
            echo "<input type='text' name='product_name' value='" . $row['product_name'] . "'><br>";

            // 상품 가격 변경
            echo "<label for='product_price'>상품 가격:</label>";
            echo "<input type='number' name='product_price' value='" . $row['product_price'] . "'><br>";

            // 상품 재고 변경
            echo "<label for='product_stock'>상품 재고:</label>";
            echo "<input type='number' name='product_stock' value='" . $row['product_stock'] . "'><br>";

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
                    $update_query = "UPDATE PRODUCT SET product_name = '$new_product_name', product_price = $new_product_price, product_stock = $new_product_stock WHERE product_id = $product_id";
                    $conn->query($update_query);

                    echo '<script>alert("상품 정보가 성공적으로 업데이트되었습니다."); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';

                } else if (isset($_POST['delete'])) {
                    // 상품 삭제 쿼리
                    $delete_query = "DELETE FROM PRODUCT WHERE product_id = $product_id";
                    $conn->query($delete_query);

                    echo '<script>alert("상품이 성공적으로 삭제되었습니다."); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';

                }
            }
        } else {
            echo '<script>alert("Product not found."); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';
        }
    } else {
        echo '<script>alert("Product ID is not set."); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';
    }

    $conn->close();
    ?>

    <a href="admin_page.php?store_id=<?php echo $store_id; ?>">뒤로 가기</a>
</body>
</html>
