<?php
// DB 정보 불러오기
include '../db_info.php';

// POST 데이터 가져오기
$storeID = isset($_POST['storeID']) ? $_POST['storeID'] : null;
$customerName = isset($_POST['customerName']) ? $_POST['customerName'] : null;
$customerBirth = isset($_POST['customerBirth']) ? $_POST['customerBirth'] : null;
$customerSex = isset($_POST['customerSex']) ? $_POST['customerSex'] : null;
$customerPhone = isset($_POST['customerPhone']) ? $_POST['customerPhone'] : null;
// 정규 표현식을 사용하여 형식 변환
if ($customerPhone !== null) {
    // 숫자 이외의 문자는 제거
    $customerPhone = preg_replace("/[^0-9]/", "", $customerPhone);

    // 010-1234-5678 형식으로 변환
    if (strlen($customerPhone) === 11) {
        $customerPhone = substr($customerPhone, 0, 3) . '-' . substr($customerPhone, 3, 4) . '-' . substr($customerPhone, 7);
    }
}
$customerEmail = isset($_POST['customerEmail']) ? $_POST['customerEmail'] : null;
$customerPw = isset($_POST['customerPw']) ? $_POST['customerPw'] : null;

// 필수 값이 하나라도 누락된 경우 처리
if ($storeID === null || $customerName === null || $customerBirth === null || $customerSex === null || $customerPhone === null || $customerEmail === null || $customerPw === null) {
    echo "<script>alert('필수 정보를 모두 입력해주세요.'); window.history.back();</script>";
    exit; // 필수 정보 누락 시 스크립트 실행 후 종료
}

// 이메일 중복 확인
$emailCheckQuery = "SELECT customer_id FROM CUSTOMER WHERE customer_email = :customerEmail";
$emailCheckResult = oci_parse($conn, $emailCheckQuery);
oci_bind_by_name($emailCheckResult, ':customerEmail', $customerEmail);
oci_execute($emailCheckResult);

if (oci_fetch_assoc($emailCheckResult)) {
    // 중복된 이메일이 존재하는 경우
    echo "<script>alert('이미 등록된 이메일 주소입니다. 다른 이메일 주소를 사용해주세요.'); window.history.back();</script>";
} else {
    // 핸드폰 번호 중복 확인
    $phoneCheckQuery = "SELECT customer_id FROM CUSTOMER WHERE customer_phone = :customerPhone";
    $phoneCheckResult = oci_parse($conn, $phoneCheckQuery);
    oci_bind_by_name($phoneCheckResult, ':customerPhone', $customerPhone);
    oci_execute($phoneCheckResult);
    if (oci_fetch_assoc($phoneCheckResult)) {
        // 중복된 핸드폰 번호가 존재하는 경우
        echo "<script>alert('이미 등록된 핸드폰 번호입니다. 다른 번호를 사용해주세요.'); window.history.back();</script>";
    } else {
        // 새로운 PROFILE 생성
        $defaultProfilePicPath = '../profile/uploads/profile_default.png';
        $defaultProfilePic = file_get_contents($defaultProfilePicPath);
    
        // PROFILE 테이블에 데이터 삽입
        $insertProfileQuery = "INSERT INTO PROFILE (profile_pic, profile_info, is_admin)
                                VALUES (EMPTY_BLOB(), NULL, 0) 
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
    
        // INSERT 쿼리 실행 (회원 정보)
        $sqlCustomer = "INSERT INTO CUSTOMER (customer_name, customer_birth, customer_sex, customer_phone, customer_email, customer_pw, customer_point, store_id, profile_id)
                        VALUES (:customerName, TO_DATE(:customerBirth, 'YYYY-MM-DD'), :customerSex, :customerPhone, :customerEmail, :customerPw, 0, :storeID, :profileId)";
    
        $customerStmt = oci_parse($conn, $sqlCustomer);
        oci_bind_by_name($customerStmt, ':customerName', $customerName);
        oci_bind_by_name($customerStmt, ':customerBirth', $customerBirth);
        if ($customerSex == "남자") {
            $customerSex = "Male";
        } else if ($customerSex == "여자") {
            $customerSex = "Female";
        }
        oci_bind_by_name($customerStmt, ':customerSex', $customerSex);
        oci_bind_by_name($customerStmt, ':customerPhone', $customerPhone);
        oci_bind_by_name($customerStmt, ':customerEmail', $customerEmail);
        oci_bind_by_name($customerStmt, ':customerPw', $customerPw);
        oci_bind_by_name($customerStmt, ':storeID', $storeID);
        oci_bind_by_name($customerStmt, ':profileId', $profileId);
    
        if (oci_execute($customerStmt, OCI_COMMIT_ON_SUCCESS)) {
            echo "<script>alert('회원가입이 성공적으로 완료되었습니다.'); window.location = '../index.php';</script>";
        } else {
            $err = oci_error($customerStmt);
            echo "회원가입 오류: " . $err['message'];
            oci_rollback($conn);
        }
    
        oci_free_statement($customerStmt);
    }

}

oci_close($conn);
?>