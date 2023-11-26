<!DOCTYPE html>
<html>
<head>
    <title>Customer Page</title>
    <style>
        /* 프로필 사진 이미지 스타일 */
        img.profile_pic_style {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Customer Page</h1>
    
    <?php
    // DB 정보 불러오기
    include 'db_info.php';

    // 고객 정보를 데이터베이스에서 가져오는 쿼리
    $customer_id = $_GET['customer_id'];
    $store_id = $_GET['store_id'];

    // Use bind variables to prevent SQL injection
    $sql = "SELECT * FROM CUSTOMER WHERE customer_id = :customer_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':customer_id', $customer_id);
    oci_execute($stmt);

    $customerRow = oci_fetch_assoc($stmt);

    if ($customerRow) {
        // 고객 정보를 출력
        echo "<p><strong>Customer Name:</strong> " . $customerRow['CUSTOMER_NAME'] . "</p>";
        echo "<p><strong>Date of Birth:</strong> " . $customerRow['CUSTOMER_BIRTH'] . "</p>";
        echo "<p><strong>Gender:</strong> " . $customerRow['CUSTOMER_SEX'] . "</p>";
        echo "<p><strong>Phone Number:</strong> " . $customerRow['CUSTOMER_PHONE'] . "</p>";

        // 고객의 프로필 정보를 데이터베이스에서 가져오는 쿼리
        $profile_id = $customerRow['PROFILE_ID'];

        if (isset($profile_id)) {
            $sqlProfile = "SELECT * FROM PROFILE WHERE profile_id = :profile_id";
            $stmtProfile = oci_parse($conn, $sqlProfile);
            oci_bind_by_name($stmtProfile, ':profile_id', $profile_id);
            oci_execute($stmtProfile);

            $profileRow = oci_fetch_assoc($stmtProfile);

            if ($profileRow) {
                // 고객의 프로필 정보를 출력
                echo "<p><strong>Profile ID:</strong> " . $profileRow['PROFILE_ID'] . "</p>";
                
                // 프로필 사진을 출력
                if (!empty($profileRow['PROFILE_PIC'])) {
                    $profile_pic = base64_encode($profileRow['PROFILE_PIC']->load());
                    echo "<img src='data:image/png;base64, $profile_pic' alt='Profile Picture' class='profile_pic_style'>";
                } else {
                    echo "<p>No profile picture available.</p>";
                }
                
                echo "<p><strong>Profile Info:</strong> " . $profileRow['PROFILE_INFO'] . "</p>";

                // 프로필 수정 버튼
                echo "<a href='edit_profile.php?profile_id=$profile_id&store_id=$store_id&customer_id=$customer_id'>프로필 수정</a>";
                
            } else {
                echo "Profile not found.";
            }

            oci_free_statement($stmtProfile);
        } else {
            echo "Profile ID is not set.";
        }

    } else {
        echo "Customer not found.";
    }

    oci_free_statement($stmt);
    oci_close($conn);
    ?>
    <h1>처음으로 돌아가기</h1>
    <form action="index.php">
        <input type="submit" value="돌아가기">
    </form>
</body>
</html>