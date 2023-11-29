<?php
// Oracle 데이터베이스 주소
$database = '(DESCRIPTION =
    (ADDRESS_LIST=
        (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
    )
    (CONNECT_DATA = 
        (SID = orcl)
    )
)';

// Oracle 사용자 정보
$username = "S3_502";
$password = "pw1234";

// Oracle 데이터베이스에 연결 및 UTF-8 문자셋 설정
$conn = oci_connect($username, $password, $database, 'AL32UTF8');

// 연결 검사
if (!$conn) {
    $e = oci_error();
    die("오라클 연결 실패: " . htmlentities($e['message'], ENT_QUOTES));
}

// 데이터베이스 선택
oci_set_client_identifier($conn, $database);
?>
