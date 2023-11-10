<?php
// 데이터베이스 연결 설정
$servername = "localhost";
$username = "root"; // 실제 데이터베이스 사용자 이름
$password = "admin"; // 실제 데이터베이스 암호
$database = "demoDB"; // 실제 데이터베이스 이름

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// POST 데이터 가져오기 (관리자 정보)
$adminName = $_POST['adminName'];
$adminBirth = $_POST['adminBirth'];
$adminPhone = $_POST['adminPhone'];
$adminEmail = $_POST['adminEmail'];
$adminPw = $_POST['adminPw'];

// 새로운 PROFILE 생성
$sql = "INSERT INTO PROFILE (profile_pic, profile_info) VALUES (null, null);";
if ($conn->query($sql) === TRUE) {
    $profileId = $conn->insert_id;
    echo "프로필 생성이 성공적으로 완료되었습니다.";
} else {
    echo "프로필 생성 오류: " . $conn->error;
}

// INSERT 쿼리 실행 (관리자 정보)
$sql = "INSERT INTO ADMIN (admin_name, admin_birth, admin_phone, admin_email, admin_pw, profile_id) VALUES ('$adminName', '$adminBirth', '$adminPhone', '$adminEmail', '$adminPw', '$profileId')";
if ($conn->query($sql) === TRUE) {
    $admin_id = $conn->insert_id; // 새로 생성된 관리자의 ID 가져오기

    // POST 데이터 가져오기 (가게 정보)
    $storeName = $_POST['storeName'];
    $storeInfo = $_POST['storeInfo'];
    $classification = $_POST['classification'];

    // INSERT 쿼리 실행 (가게 정보)
    $sql = "INSERT INTO STORE (store_name, store_info, classification, admin_id) VALUES ('$storeName', '$storeInfo', '$classification', '$admin_id')";
    if ($conn->query($sql) === TRUE) {
        echo "관리자와 가게 정보가 성공적으로 등록되었습니다.";
    } else {
        echo "가게 정보 등록 오류: " . $conn->error;
    }
} else {
    echo "관리자 정보 등록 오류: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>관리자 및 가게 정보 등록 완료</title>
</head>
<body>
    <h1>관리자 및 가게 정보 등록 완료</h1>
    <p>관리자와 가게 정보가 성공적으로 등록되었습니다.</p>
    <form action="index.php">
        <input type="submit" value="돌아가기">
    </form>
</body>
</html>