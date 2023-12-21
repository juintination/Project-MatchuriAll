<?php
// DB 정보 불러오기
include 'db_info.php';

// PROFILE 시퀀스 생성
$createProfileSequenceQuery = "CREATE SEQUENCE profile_sequence START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE";
if (oci_execute(oci_parse($conn, $createProfileSequenceQuery))) {
    echo "프로필 시퀀스가 성공적으로 생성되었습니다.<br>";
} else {
    $error = oci_error($conn);
    echo "시퀀스 생성 오류: " . $error['message'];
}

// PROFILE 테이블 생성
$profileQuery = "CREATE TABLE PROFILE (
    profile_id NUMBER DEFAULT profile_sequence.NEXTVAL PRIMARY KEY,
    profile_pic BLOB,
    profile_info VARCHAR2(255),
    is_admin NUMBER(1)
)";

try {
    $stmt = oci_parse($conn, $profileQuery);
    oci_execute($stmt);
    echo "PROFILE 테이블이 성공적으로 생성되었습니다.<br>";
} catch (Exception $e) {
    echo "PROFILE 테이블 생성 오류: " . $e->getMessage() . "<br>";
}

// ADMIN 시퀀스 생성
$createAdminSequenceQuery = "CREATE SEQUENCE admin_sequence START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE";
if (oci_execute(oci_parse($conn, $createAdminSequenceQuery))) {
    echo "관리자 시퀀스가 성공적으로 생성되었습니다.<br>";
} else {
    $error = oci_error($conn);
    echo "시퀀스 생성 오류: " . $error['message'];
}

// ADMIN 테이블 생성
$adminQuery = "CREATE TABLE ADMIN (
    admin_id NUMBER DEFAULT admin_sequence.NEXTVAL PRIMARY KEY,
    admin_name VARCHAR2(50),
    admin_birth DATE,
    admin_phone VARCHAR2(15),
    admin_email VARCHAR2(50),
    admin_pw VARCHAR2(20),
    profile_id NUMBER NOT NULL,
    FOREIGN KEY (profile_id) REFERENCES PROFILE(profile_id)
)";

try {
    $stmt = oci_parse($conn, $adminQuery);
    oci_execute($stmt);
    echo "ADMIN 테이블이 성공적으로 생성되었습니다.<br>";
} catch (Exception $e) {
    echo "ADMIN 테이블 생성 오류: " . $e->getMessage() . "<br>";
}

// STORE 시퀀스 생성
$createStoreSequenceQuery = "CREATE SEQUENCE store_sequence START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE";
if (oci_execute(oci_parse($conn, $createStoreSequenceQuery))) {
    echo "가게 시퀀스가 성공적으로 생성되었습니다.<br>";
} else {
    $error = oci_error($conn);
    echo "시퀀스 생성 오류: " . $error['message'];
}

// STORE 테이블 생성
$storeQuery = "CREATE TABLE STORE (
    store_id NUMBER DEFAULT store_sequence.NEXTVAL PRIMARY KEY,
    store_name VARCHAR2(100),
    store_info VARCHAR2(100),
    classification VARCHAR2(100),
    admin_id NUMBER NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES ADMIN(admin_id)
)";

try {
    $stmt = oci_parse($conn, $storeQuery);
    oci_execute($stmt);
    echo "STORE 테이블이 성공적으로 생성되었습니다.<br>";
} catch (Exception $e) {
    echo "STORE 테이블 생성 오류: " . $e->getMessage() . "<br>";
}

// CUSTOMER 시퀀스 생성
$createCustomerSequenceQuery = "CREATE SEQUENCE customer_sequence START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE";
if (oci_execute(oci_parse($conn, $createCustomerSequenceQuery))) {
    echo "고객 시퀀스가 성공적으로 생성되었습니다.<br>";
} else {
    $error = oci_error($conn);
    echo "시퀀스 생성 오류: " . $error['message'];
}

// CUSTOMER 테이블 생성
$customerQuery = "CREATE TABLE CUSTOMER (
    customer_id NUMBER DEFAULT customer_sequence.NEXTVAL PRIMARY KEY,
    customer_name VARCHAR2(50),
    customer_birth DATE,
    customer_sex VARCHAR2(10),
    customer_phone VARCHAR2(15),
    customer_email VARCHAR2(50),
    customer_pw VARCHAR2(20),
    customer_point NUMBER,
    store_id NUMBER NOT NULL,
    profile_id NUMBER NOT NULL,
    FOREIGN KEY (store_id) REFERENCES STORE(store_id),
    FOREIGN KEY (profile_id) REFERENCES PROFILE(profile_id)
)";

try {
    $stmt = oci_parse($conn, $customerQuery);
    oci_execute($stmt);
    echo "CUSTOMER 테이블이 성공적으로 생성되었습니다.<br>";
} catch (Exception $e) {
    $error = oci_error($stmt);
    echo "CUSTOMER 테이블 생성 오류: " . $error['message'] . "<br>";
}

// PRODUCT 시퀀스 생성
$createProductSequenceQuery = "CREATE SEQUENCE product_sequence START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE";
if (oci_execute(oci_parse($conn, $createProductSequenceQuery))) {
    echo "상품 시퀀스가 성공적으로 생성되었습니다.<br>";
} else {
    $error = oci_error($conn);
    echo "시퀀스 생성 오류: " . $error['message'];
}

// PRODUCT 테이블 생성
$productQuery = "CREATE TABLE PRODUCT (
    product_id NUMBER DEFAULT product_sequence.NEXTVAL PRIMARY KEY,
    product_name VARCHAR2(20),
    product_var VARCHAR2(20),
    product_price NUMBER,
    product_stock NUMBER,
    store_id NUMBER NOT NULL,
    FOREIGN KEY (store_id) REFERENCES STORE(store_id) ON DELETE CASCADE
)";

try {
    $stmt = oci_parse($conn, $productQuery);
    oci_execute($stmt);
    echo "PRODUCT 테이블이 성공적으로 생성되었습니다.<br>";
} catch (Exception $e) {
    echo "PRODUCT 테이블 생성 오류: " . $e->getMessage() . "<br>";
}

// RECEIPT 시퀀스 생성
$createReceiptSequenceQuery = "CREATE SEQUENCE receipt_sequence START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE";
if (oci_execute(oci_parse($conn, $createReceiptSequenceQuery))) {
    echo "구매 정보 시퀀스가 성공적으로 생성되었습니다.<br>";
} else {
    $error = oci_error($conn);
    echo "시퀀스 생성 오류: " . $error['message'];
}

// RECEIPT 테이블 생성
$receiptQuery = "CREATE TABLE RECEIPT (
    receipt_id NUMBER DEFAULT receipt_sequence.NEXTVAL PRIMARY KEY,
    receipt_num NUMBER,
    receipt_time TIMESTAMP,
    receipt_price NUMBER,
    payment_method VARCHAR2(20),
    store_id NUMBER NOT NULL,
    customer_id NUMBER NOT NULL,
    FOREIGN KEY (store_id) REFERENCES STORE(store_id),
    FOREIGN KEY (customer_id) REFERENCES CUSTOMER(customer_id)
)";

try {
    $stmt = oci_parse($conn, $receiptQuery);
    oci_execute($stmt);
    echo "RECEIPT 테이블이 성공적으로 생성되었습니다.<br>";
} catch (Exception $e) {
    echo "RECEIPT 테이블 생성 오류: " . $e->getMessage() . "<br>";
}

// ORDER_DETAIL 테이블 생성
$orderDetailQuery = "CREATE TABLE ORDER_DETAIL (
    receipt_id NUMBER NOT NULL,
    product_id NUMBER NOT NULL,
    product_count NUMBER,
    intermediate_price NUMBER,
    PRIMARY KEY (receipt_id, product_id),
    FOREIGN KEY (receipt_id) REFERENCES RECEIPT(receipt_id),
    FOREIGN KEY (product_id) REFERENCES PRODUCT(product_id)
)";

try {
    $stmt = oci_parse($conn, $orderDetailQuery);
    oci_execute($stmt);
    echo "ORDER_DETAIL 테이블이 성공적으로 생성되었습니다.<br>";
} catch (Exception $e) {
    echo "ORDER_DETAIL 테이블 생성 오류: " . $e->getMessage() . "<br>";
}

// 연결 종료
oci_close($conn);
?>