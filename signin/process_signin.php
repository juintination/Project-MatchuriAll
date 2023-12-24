<?php
// DB 정보 불러오기
include '../db_info.php';

// POST 데이터 가져오기
$store_id = $_POST['store_id'];
$user_type = $_POST['user_type'];
$email = $_POST['email'];
$password = $_POST['password'];

if ($user_type === "admin") {
    // 관리자로 로그인
    $sql = "SELECT ADMIN.*, STORE.store_name
            FROM ADMIN, STORE
            WHERE ADMIN.admin_id = STORE.admin_id
            AND ADMIN.admin_email = :email AND ADMIN.admin_pw = :password AND STORE.store_id = :store_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':password', $password);
    oci_bind_by_name($stmt, ':store_id', $store_id);
    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);

    if ($row) {
        // 관리자 정보가 일치하는 경우
        header("Location: ../admin/admin_page.php?store_id=$store_id");
    } else {
        // 관리자 정보가 일치하지 않는 경우
        echo "<script>alert('관리자 로그인에 실패했습니다. 다시 시도하십시오.'); window.location = 'signin.php';</script>";
    }
    oci_free_statement($stmt);
} else if ($user_type === "user") {
    // 일반 회원으로 로그인
    $sql = "SELECT CUSTOMER.*, STORE.store_name 
            FROM CUSTOMER, STORE
            WHERE CUSTOMER.customer_email = :email AND CUSTOMER.customer_pw = :password AND CUSTOMER.store_id = :store_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':password', $password);
    oci_bind_by_name($stmt, ':store_id', $store_id);
    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);

    if ($row) {
        // 일반 회원 정보가 일치하는 경우
        $store_id = $row['STORE_ID'];
        $customer_id = $row['CUSTOMER_ID'];
        header("Location: ../user/user_page.php?store_id=$store_id&customer_id=$customer_id");
    } else {
        // 일반 회원 정보가 일치하지 않는 경우
        echo "<script>alert('일반 회원 로그인에 실패했습니다. 다시 시도하십시오.'); window.location = 'signin.php';</script>";
    }
    oci_free_statement($stmt);
}

oci_close($conn);
?>
