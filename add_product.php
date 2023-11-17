<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // POST 데이터 가져오기
    $productName = $_POST['productName'];
    $productVar = $_POST['productVar'];
    $productPrice = $_POST['productPrice'];
    $store_id = $_POST['store_id'];

    // 상품 추가 쿼리 실행
    $insertQuery = "INSERT INTO PRODUCT (product_name, product_var, product_price, product_stock, store_id) 
                    VALUES ('$productName', '$productVar', $productPrice, 0, $store_id)";

    if ($conn->query($insertQuery) === TRUE) {
        echo '<script>alert("상품이 성공적으로 추가되었습니다."); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';
    } else {
        echo '<script>alert("상품 추가 오류: ' . $conn->error . '"); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';
    }

    $conn->close();
} else {
    echo '<script>alert("잘못된 요청입니다."); window.location.href = "admin_page.php?store_id=' . $store_id . '";</script>';
}
