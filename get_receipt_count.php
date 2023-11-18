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

// RECEIPT 테이블의 레코드 개수를 가져오는 쿼리
$getReceiptCountQuery = "SELECT COUNT(*) AS count FROM RECEIPT";
$result = $conn->query($getReceiptCountQuery);

if ($result) {
    $row = $result->fetch_assoc();
    $count = $row['count'];
    echo $count;
} else {
    echo "Error in counting receipt records";
}

$conn->close();
?>
