<?php
// DB 정보 불러오기
include '../db_info.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// POST 데이터 가져오기 (관리자 정보)
$adminName = isset($_POST['adminName']) ? $_POST['adminName'] : null;
$adminBirth = isset($_POST['adminBirth']) ? $_POST['adminBirth'] : null;
$adminPhone = isset($_POST['adminPhone']) ? $_POST['adminPhone'] : null;
// 정규 표현식을 사용하여 형식 변환
if ($adminPhone !== null) {
    // 숫자 이외의 문자는 제거
    $adminPhone = preg_replace("/[^0-9]/", "", $adminPhone);

    // 010-1234-5678 형식으로 변환
    if (strlen($adminPhone) === 11) {
        $adminPhone = substr($adminPhone, 0, 3) . '-' . substr($adminPhone, 3, 4) . '-' . substr($adminPhone, 7);
    }
}
$adminEmail = isset($_POST['adminEmail']) ? $_POST['adminEmail'] : null;
$adminPw = isset($_POST['adminPw']) ? $_POST['adminPw'] : null;

// 필수 값이 하나라도 누락된 경우 처리
if ($adminName === null || $adminBirth === null || $adminPhone === null || $adminEmail === null || $adminPw === null) {
    echo "<script>alert('필수 정보를 모두 입력해주세요.'); window.history.back();</script>";
    exit; // 필수 정보 누락 시 스크립트 실행 후 종료
}

// 이메일 중복 확인
$emailCheckQuery = "SELECT admin_id FROM ADMIN WHERE admin_email = :adminEmail";
$emailCheckResult = oci_parse($conn, $emailCheckQuery);
oci_bind_by_name($emailCheckResult, ':adminEmail', $adminEmail);
oci_execute($emailCheckResult);

if (oci_fetch_assoc($emailCheckResult)) {
    // 중복된 이메일이 존재하는 경우
    echo "<script>alert('이미 등록된 이메일 주소입니다. 다른 이메일 주소를 사용해주세요.'); window.history.back();</script>";
} else {
    // 중복된 이메일이 없는 경우

    try {
        // 새로운 PROFILE 생성
        $defaultProfilePicPath = '../profile/uploads/profile_default.png';
        $defaultProfilePic = file_get_contents($defaultProfilePicPath);

        // PROFILE 테이블에 데이터 삽입
        $insertProfileQuery = "INSERT INTO PROFILE (profile_pic, profile_info, is_admin)
                                VALUES (EMPTY_BLOB(), NULL, 1)
                                RETURNING profile_pic, profile_id INTO :blobData, :profileId";
        $profileStmt = oci_parse($conn, $insertProfileQuery);

        $blobDescriptor = oci_new_descriptor($conn, OCI_D_LOB);
        oci_bind_by_name($profileStmt, ':blobData', $blobDescriptor, -1, OCI_B_BLOB);
        oci_bind_by_name($profileStmt, ':profileId', $profileId, 32);

        oci_execute($profileStmt, OCI_DEFAULT);

        if ($defaultProfilePic) {
            $blobDescriptor->save($defaultProfilePic);
        }

        oci_commit($conn);

        oci_free_statement($profileStmt);
        oci_free_descriptor($blobDescriptor);

        // INSERT 쿼리 실행 (관리자 정보)
        $sql = "INSERT INTO ADMIN (admin_name, admin_birth, admin_phone, admin_email, admin_pw, profile_id) 
                VALUES (:adminName, TO_DATE(:adminBirth, 'YYYY-MM-DD'), :adminPhone, :adminEmail, :adminPw, :profileId)";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':adminName', $adminName);
        oci_bind_by_name($stmt, ':adminBirth', $adminBirth);
        oci_bind_by_name($stmt, ':adminPhone', $adminPhone);
        oci_bind_by_name($stmt, ':adminEmail', $adminEmail);
        oci_bind_by_name($stmt, ':adminPw', $adminPw);
        oci_bind_by_name($stmt, ':profileId', $profileId, 32);

        oci_execute($stmt);

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
        $sql = "INSERT INTO STORE (store_name, store_info, classification, admin_id) 
        VALUES (:storeName, :storeInfo, :classification, 
        (SELECT admin_id FROM ADMIN WHERE admin_email = :adminEmail))";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':storeName', $storeName);
        oci_bind_by_name($stmt, ':storeInfo', $storeInfo);
        oci_bind_by_name($stmt, ':classification', $classification);
        oci_bind_by_name($stmt, ':adminEmail', $adminEmail);
        oci_execute($stmt);

        oci_commit($conn);

        echo "<script>alert('관리자와 가게 정보가 성공적으로 등록되었습니다.'); window.location = '../index.php';</script>";
    } catch (Exception $e) {
        // 롤백
        oci_rollback($conn);
        echo "오류 발생: " . $e->getMessage() . "<br>";
    }

    // 리소스 해제
    oci_free_statement($emailCheckResult);
    oci_free_statement($stmt);
}

oci_close($conn);
?>