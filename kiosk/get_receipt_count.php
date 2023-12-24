<?php
// DB 정보 불러오기
include '../db_info.php';

// RECEIPT 테이블의 레코드 개수를 가져오는 쿼리
$getReceiptCountQuery = "SELECT COUNT(*) AS count FROM RECEIPT";
$stmt = oci_parse($conn, $getReceiptCountQuery);
oci_execute($stmt);

if ($row = oci_fetch_assoc($stmt)) {
    $count = $row['COUNT'];
    echo $count;
} else {
    echo "Error in counting receipt records";
}

oci_free_statement($stmt);
oci_close($conn);
?>