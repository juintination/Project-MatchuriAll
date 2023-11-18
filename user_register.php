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

// 이메일 중복 확인
$emailCheckQuery = "SELECT user_id FROM USER WHERE user_email = '$userEmail'";
$emailCheckResult = $conn->query($emailCheckQuery);

if ($emailCheckResult->num_rows > 0) {
    // 중복된 이메일이 존재하는 경우
    echo "<script>alert('이미 등록된 이메일 주소입니다. 다른 이메일 주소를 사용해주세요.'); window.history.back();</script>";
} else {
    // 중복된 이메일이 없는 경우

    // 새로운 PROFILE 생성
    $sql = "INSERT INTO PROFILE (profile_pic, profile_info, is_admin) VALUES (null, null, 0);";
    if ($conn->query($sql) === TRUE) {
        $profileId = $conn->insert_id;
        echo "프로필 생성이 성공적으로 완료되었습니다.";
    } else {
        echo "프로필 생성 오류: " . $conn->error;
    }

    // INSERT 쿼리 실행
    $sql = "INSERT INTO USER (user_name, user_birth, user_sex, user_phone, user_email, user_pw, user_point, store_id, profile_id) VALUES ('$userName', '$userBirth', '$userSex', '$userPhone', '$userEmail', '$userPw', 0, '$storeID', '$profileId')";
    if ($conn->query($sql) === TRUE) {
        echo "회원가입이 성공적으로 완료되었습니다.";
    } else {
        echo "회원가입 오류: " . $conn->error;
    }
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
