<?php
// DB 정보 불러오기
include 'db_info.php';

// POST 데이터 가져오기 (관리자 정보)
$adminName = isset($_POST['adminName']) ? $_POST['adminName'] : null;
$adminBirth = isset($_POST['adminBirth']) ? $_POST['adminBirth'] : null;
$adminPhone = isset($_POST['adminPhone']) ? $_POST['adminPhone'] : null;
$adminEmail = isset($_POST['adminEmail']) ? $_POST['adminEmail'] : null;
$adminPw = isset($_POST['adminPw']) ? $_POST['adminPw'] : null;

// 필수 값이 하나라도 누락된 경우 처리
if ($adminName === null || $adminBirth === null || $adminPhone === null || $adminEmail === null || $adminPw === null) {
    echo "<script>alert('필수 정보를 모두 입력해주세요.'); window.history.back();</script>";
    exit; // 필수 정보 누락 시 스크립트 실행 후 종료
}

// 이메일 중복 확인
$emailCheckQuery = "SELECT admin_id FROM ADMIN WHERE admin_email = '$adminEmail'";
$emailCheckResult = $conn->query($emailCheckQuery);

if ($emailCheckResult->num_rows > 0) {
    // 중복된 이메일이 존재하는 경우
    echo "<script>alert('이미 등록된 이메일 주소입니다. 다른 이메일 주소를 사용해주세요.'); window.history.back();</script>";
} else {
    // 중복된 이메일이 없는 경우

    // 새로운 PROFILE 생성
    $defaultProfilePic = 'uploads/profile_default.png';
    $sql = "INSERT INTO PROFILE (profile_pic, profile_info, is_admin) VALUES ('$defaultProfilePic', null, 1);";
    
    if ($conn->query($sql) === TRUE) {
        $profileId = $conn->insert_id;

        // INSERT 쿼리 실행 (관리자 정보)
        $sql = "INSERT INTO ADMIN (admin_name, admin_birth, admin_phone, admin_email, admin_pw, profile_id) VALUES ('$adminName', '$adminBirth', '$adminPhone', '$adminEmail', '$adminPw', '$profileId')";
        
        if ($conn->query($sql) === TRUE) {
            $admin_id = $conn->insert_id; // 새로 생성된 관리자의 ID 가져오기

            // POST 데이터 가져오기 (가게 정보)
            $storeName = isset($_POST['storeName']) ? $_POST['storeName'] : null;
            $storeInfo = isset($_POST['storeInfo']) ? $_POST['storeInfo'] : null;
            $classification = isset($_POST['classification']) ? $_POST['classification'] : null;

            // 필수 값이 하나라도 누락된 경우 처리
            if ($storeName === null || $storeInfo === null || $classification === null) {
                echo "<script>alert('필수 정보를 모두 입력해주세요.'); window.history.back();</script>";
                exit; // 필수 정보 누락 시 스크립트 실행 후 종료
            }

            // INSERT 쿼리 실행 (가게 정보)
            $sql = "INSERT INTO STORE (store_name, store_info, classification, admin_id) VALUES ('$storeName', '$storeInfo', '$classification', '$admin_id')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('관리자와 가게 정보가 성공적으로 등록되었습니다.'); window.location = 'index.php';</script>";
            } else {
                echo "가게 정보 등록 오류: " . $conn->error;
            }
        } else {
            echo "관리자 정보 등록 오류: " . $conn->error;
        }
    } else {
        echo "프로필 생성 오류: " . $conn->error;
    }
}

$conn->close();
?>