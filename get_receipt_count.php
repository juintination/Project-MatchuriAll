<?php
// DB 정보 불러오기
include 'db_info.php';

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
