<?php
// DB 정보 불러오기
include 'db_info.php';

// 데이터베이스 생성
$createDatabaseQuery = "CREATE DATABASE IF NOT EXISTS $database";

if ($conn->query($createDatabaseQuery) === TRUE) {
    echo "데이터베이스가 성공적으로 생성되었습니다.<br>";
} else {
    echo "데이터베이스 생성 오류: " . $conn->error;
}

// 데이터베이스 선택
$conn->select_db($database);

// PROFILE 테이블 생성
$profileQuery = "CREATE TABLE IF NOT EXISTS PROFILE (
    profile_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    profile_pic MEDIUMBLOB NULL,
    profile_info VARCHAR(255) NULL,
    is_admin TINYINT(1)
)";

if ($conn->query($profileQuery) === TRUE) {
    echo "PROFILE 테이블이 성공적으로 생성되었습니다.<br>";
} else {
    echo "테이블 생성 오류: " . $conn->error;
}

// ADMIN 테이블 생성
$adminQuery = "CREATE TABLE IF NOT EXISTS ADMIN (
    admin_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    admin_name VARCHAR(50),
    admin_birth DATE,
    admin_phone VARCHAR(15),
    admin_email VARCHAR(50),
    admin_pw VARCHAR(20), -- 로그인을 위한 pw 속성 추가
    profile_id BIGINT,
    FOREIGN KEY (profile_id) REFERENCES PROFILE(profile_id)
)";

if ($conn->query($adminQuery) === TRUE) {
    echo "ADMIN 테이블이 성공적으로 생성되었습니다.<br>";
} else {
    echo "테이블 생성 오류: " . $conn->error;
}

// STORE 테이블 생성
$storeQuery = "CREATE TABLE IF NOT EXISTS STORE (
    store_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    store_name VARCHAR(100),
    store_info VARCHAR(100),
    classification VARCHAR(100),
    admin_id BIGINT,
    FOREIGN KEY (admin_id) REFERENCES ADMIN(admin_id)
)";

if ($conn->query($storeQuery) === TRUE) {
    echo "STORE 테이블이 성공적으로 생성되었습니다.<br>";
} else {
    echo "테이블 생성 오류: " . $conn->error;
}

// USER 테이블 생성
$userQuery = "CREATE TABLE IF NOT EXISTS USER (
    user_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(50),
    user_birth DATE,
    user_sex VARCHAR(10),
    user_phone VARCHAR(15),
    user_email VARCHAR(50), -- 로그인을 위한 유저 email 추가
    user_pw VARCHAR(20), -- 로그인을 위한 pw 속성 추가
    user_point BIGINT,
    store_id BIGINT,
    profile_id BIGINT,
    FOREIGN KEY (store_id) REFERENCES STORE(store_id),
    FOREIGN KEY (profile_id) REFERENCES PROFILE(profile_id)
)";

if ($conn->query($userQuery) === TRUE) {
    echo "USER 테이블이 성공적으로 생성되었습니다.<br>";
} else {
    echo "테이블 생성 오류: " . $conn->error;
}

// PRODUCT 테이블 생성
$productQuery = "CREATE TABLE IF NOT EXISTS PRODUCT (
    product_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(20),
    product_var VARCHAR(20),
    product_price INT,
    product_stock INT,
    store_id BIGINT,
    FOREIGN KEY (store_id) REFERENCES STORE(store_id)
)";

if ($conn->query($productQuery) === TRUE) {
    echo "PRODUCT 테이블이 성공적으로 생성되었습니다.<br>";
} else {
    echo "테이블 생성 오류: " . $conn->error;
}

// RECEIPT 테이블 생성
$receiptQuery = "CREATE TABLE IF NOT EXISTS RECEIPT (
    receipt_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    receipt_num INT,
    receipt_time TIMESTAMP,
    receipt_price INT,
    payment_method VARCHAR(20),
    store_id BIGINT,
    user_id BIGINT,
    FOREIGN KEY (store_id) REFERENCES STORE(store_id),
    FOREIGN KEY (user_id) REFERENCES USER(user_id)
)";

if ($conn->query($receiptQuery) === TRUE) {
    echo "RECEIPT 테이블이 성공적으로 생성되었습니다.<br>";
} else {
    echo "테이블 생성 오류: " . $conn->error;
}

// ORDER_DETAIL 테이블 생성
$orderDetailQuery = "CREATE TABLE IF NOT EXISTS ORDER_DETAIL (
    product_count INT,
    intermediate_price INT,
    receipt_id BIGINT,
    product_id BIGINT,
    PRIMARY KEY (receipt_id, product_id),
    FOREIGN KEY (receipt_id) REFERENCES RECEIPT(receipt_id),
    FOREIGN KEY (product_id) REFERENCES PRODUCT(product_id)
)";

if ($conn->query($orderDetailQuery) === TRUE) {
    echo "ORDER_DETAIL 테이블이 성공적으로 생성되었습니다.<br>";
} else {
    echo "테이블 생성 오류: " . $conn->error;
}

// 연결 종료
$conn->close();
?>