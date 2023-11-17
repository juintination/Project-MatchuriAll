<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
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
    <h1>Welcome to the Admin Page</h1>

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

    // 관리자 정보를 데이터베이스에서 가져오는 쿼리
    $store_id = $_GET['store_id'];
    $sql = "SELECT ADMIN.*, STORE.store_name, STORE.classification
            FROM ADMIN
            LEFT JOIN STORE ON ADMIN.admin_id = STORE.admin_id
            WHERE STORE.store_id = $store_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $adminRow = $result->fetch_assoc();

        // 관리자 정보를 출력
        echo "<p><strong>Admin Name:</strong> " . $adminRow['admin_name'] . "</p>";
        echo "<p><strong>Date of Birth:</strong> " . $adminRow['admin_birth'] . "</p>";
        echo "<p><strong>Phone Number:</strong> " . $adminRow['admin_phone'] . "</p>";
        echo "<p><strong>Email:</strong> " . $adminRow['admin_email'] . "</p>";

        // 관리자의 프로필 정보를 데이터베이스에서 가져오는 쿼리
        $profile_id = $adminRow['profile_id'];
        if (isset($profile_id)) {
            $sql = "SELECT * FROM PROFILE WHERE profile_id = $profile_id";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
        
                // 사용자의 프로필 정보를 출력
                echo "<p><strong>Profile ID:</strong> " . $row['profile_id'] . "</p>";
                echo "<p><strong>Profile Picture:</strong> " . $row['profile_pic'] . "</p>";

                // 프로필 사진을 출력
                if (!empty($row['profile_pic'])) {
                    echo "<img src='" . $row['profile_pic'] . "' alt='Profile Picture' class='profile_pic_style'>";
                } else {
                    echo "<p>No profile picture available.</p>";
                }

                echo "<p><strong>Profile Info:</strong> " . $row['profile_info'] . "</p>";

                // 프로필 수정 버튼 추가
                echo "<a href='edit_profile.php?profile_id=$profile_id&store_id=$store_id'>프로필 수정</a>";
                
            } else {
                echo "Profile not found.";
            }
        } else {
            echo "Profile ID is not set.";
        }

        // 가게 정보를 출력
        echo "<p><strong>Store Name:</strong> " . $adminRow['store_name'] . "</p>";
        echo "<p><strong>Classification:</strong> " . $adminRow['classification'] . "</p>";
    } else {
        echo "Admin not found.";
    }

    $conn->close();
    ?>
    <h1>처음으로 돌아가기</h1>
    <form action="index.php">
        <input type="submit" value="돌아가기">
    </form>
</body>
</html>