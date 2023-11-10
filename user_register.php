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

// POST 데이터 가져오기
$storeID = $_POST['storeID'];
$userName = $_POST['userName'];
$userBirth = $_POST['userBirth'];
$userSex = $_POST['userSex'];
$userPhone = $_POST['userPhone'];
$userEmail = $_POST['userEmail'];
$userPw = $_POST['userPw'];

// 새로운 PROFILE 생성
$sql = "INSERT INTO PROFILE (profile_pic, profile_info) VALUES (null, null);";
if ($conn->query($sql) === TRUE) {
    $profileId = $conn->insert_id;
    echo "프로필 생성이 성공적으로 완료되었습니다.";
} else {
    echo "프로필 생성 오류: " . $conn->error;
}

// INSERT 쿼리 실행
$sql = "INSERT INTO USER (user_name, user_birth, user_sex, user_phone, user_pw, user_email, store_id, profile_id) VALUES ('$userName', '$userBirth', '$userSex', '$userPhone', '$userPw', '$userEmail', '$storeID', '$profileId')";
if ($conn->query($sql) === TRUE) {
    echo "회원가입이 성공적으로 완료되었습니다.";
} else {
    echo "회원가입 오류: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>회원가입 완료</title>
</head>
<body>
    <h1>처음으로 돌아가기</h1>
    <form action="index.php">
        <input type="submit" value="돌아가기">
    </form>
</body>
</html>