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

// POST 데이터 가져오기
$store_id = $_POST['store_id'];
$user_type = $_POST['user_type'];
$email = $_POST['email'];
$password = $_POST['password'];

if ($user_type === "admin") {
    // 관리자로 로그인
    $sql = "SELECT ADMIN.*, STORE.store_name 
            FROM ADMIN
            LEFT JOIN STORE ON ADMIN.admin_id = STORE.admin_id
            WHERE ADMIN.admin_email = '$email' AND ADMIN.admin_pw = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 관리자 정보가 일치하는 경우
        $row = $result->fetch_assoc();
        // $row 변수에 관리자 정보 및 관련 가게 정보가 들어 있습니다.
        header("Location: admin_page.php?store_id=$store_id");
    } else {
        // 관리자 정보가 일치하지 않는 경우
        echo "<script>alert('Admin login failed. Please try again.'); window.location = 'signin.php';</script>";
    }
} elseif ($user_type === "user") {
    // 일반 회원으로 로그인
    $sql = "SELECT USER.*, STORE.store_name 
            FROM USER 
            LEFT JOIN STORE ON USER.store_id = STORE.store_id
            WHERE USER.user_email = '$email' AND USER.user_pw = '$password' AND USER.store_id = '$store_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 일반 회원 정보가 일치하는 경우
        $row = $result->fetch_assoc();
        // $row 변수에 일반 회원 정보 및 관련 가게 정보가 들어 있습니다.
        $store_id = $row['store_id'];
        $user_id = $row['user_id'];
        header("Location: user_page.php?store_id=$store_id&user_id=$user_id");
    } else {
        // 일반 회원 정보가 일치하지 않는 경우
        echo "<script>alert('User login failed. Please try again.'); window.location = 'signin.php';</script>";
    }
}

$conn->close();
?>