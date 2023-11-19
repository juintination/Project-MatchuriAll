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
$storeID = isset($_POST['storeID']) ? $_POST['storeID'] : null;
$userName = isset($_POST['userName']) ? $_POST['userName'] : null;
$userBirth = isset($_POST['userBirth']) ? $_POST['userBirth'] : null;
$userSex = isset($_POST['userSex']) ? $_POST['userSex'] : null;
$userPhone = isset($_POST['userPhone']) ? $_POST['userPhone'] : null;
$userEmail = isset($_POST['userEmail']) ? $_POST['userEmail'] : null;
$userPw = isset($_POST['userPw']) ? $_POST['userPw'] : null;

// 필수 값이 하나라도 누락된 경우 처리
if ($storeID === null || $userName === null || $userBirth === null || $userSex === null || $userPhone === null || $userEmail === null || $userPw === null) {
    echo "<script>alert('필수 정보를 모두 입력해주세요.'); window.history.back();</script>";
    exit; // 필수 정보 누락 시 스크립트 실행 후 종료
}

// 이메일 중복 확인
$emailCheckQuery = "SELECT user_id FROM USER WHERE user_email = '$userEmail'";
$emailCheckResult = $conn->query($emailCheckQuery);

if ($emailCheckResult->num_rows > 0) {
    // 중복된 이메일이 존재하는 경우
    echo "<script>alert('이미 등록된 이메일 주소입니다. 다른 이메일 주소를 사용해주세요.'); window.history.back();</script>";
} else {
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
        echo "<script>alert('회원가입이 성공적으로 완료되었습니다.'); window.location = 'index.php';</script>";
    } else {
        echo "회원가입 오류: " . $conn->error;
    }
}

$conn->close();
?>
