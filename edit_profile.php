<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <script>
        function checkFileSize() {
            var input = document.getElementById('profile_pic');
            var file = input.files[0];

            // 파일 크기 제한 (2MB)
            var maxSize = 2 * 1024 * 1024;

            if (file && file.size > maxSize) {
                alert('이미지 크기는 2MB를 초과할 수 없습니다.');
                input.value = '';
            }
        }
    </script>
</head>
<body>
    <h1>Edit Profile</h1>
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

    if (isset($_GET['profile_id'])) {
        $profile_id = $_GET['profile_id'];
        $sql = "SELECT * FROM PROFILE WHERE profile_id = $profile_id";

        try {
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // 사용자의 현재 프로필 정보를 출력
                echo "<p><strong>Profile ID:</strong> " . $row['profile_id'] . "</p>";
                echo "<p><strong>Profile Picture:</strong> " . $row['profile_pic'] . "</p>";
                echo "<p><strong>Profile Info:</strong> " . $row['profile_info'] . "</p>";

                // 프로필 정보 수정
                echo "<form action='' method='post' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='profile_id' value='$profile_id'>";

                // 프로필 사진 변경
                echo "<label for='profile_pic'>프로필 사진 변경:</label>";
                echo "<input type='file' name='profile_pic' id='profile_pic' accept='.png, .jpg' onchange='checkFileSize()'><br>";

                // 프로필 정보 변경
                echo "<label for='profile_info'>프로필 정보 변경:</label>";
                echo "<textarea name='profile_info' id='profile_info'>" . $row['profile_info'] . "</textarea><br>";

                // 기본 이미지로 변경 버튼
                echo "<input type='submit' name='default_pic' value='기본 이미지로 변경'><br>";

                // 수정 완료 버튼
                echo "<input type='submit' name='submit' value='프로필 수정 완료'>";
                echo "</form>";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST['default_pic'])) {
                        // 기본 이미지로 변경 버튼이 눌렸을 때
                        $update_pic_query = "UPDATE PROFILE SET profile_pic = NULL WHERE profile_id = $profile_id";
                        $conn->query($update_pic_query);

                        echo "<script>alert('프로필 사진이 기본 이미지로 변경되었습니다.'); location.reload();</script>";
                    } else if (isset($_POST['submit'])) {
                        // 프로필 수정 완료 버튼이 눌렸을 때
                        $new_profile_info = $_POST['profile_info'];

                        // 프로필 사진 업로드
                        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
                            
                            // 파일 정보 가져오기
                            $file_name = $_FILES['profile_pic']['name'];
                            $file_tmp = $_FILES['profile_pic']['tmp_name'];

                            // uploads 폴더가 없다면 생성
                            $uploadFolder = 'uploads';
                            if (!is_dir($uploadFolder)) {
                                mkdir($uploadFolder, 0777, true);
                            }

                            // 파일을 서버로 이동
                            move_uploaded_file($file_tmp, "uploads/$file_name");

                            // 업로드된 파일 이름을 변수에 저장
                            $new_profile_pic = "uploads/$file_name";
                        } else {
                            // 파일이 업로드되지 않은 경우 또는 오류가 발생한 경우
                            $new_profile_pic = null;
                        }

                        // 프로필 정보 업데이트 쿼리
                        $update_query = "UPDATE PROFILE SET profile_info = '$new_profile_info'";
                        if ($new_profile_pic !== null) {
                            $update_query .= ", profile_pic = '$new_profile_pic'";
                        }
                        $update_query .= " WHERE profile_id = $profile_id";
                        $conn->query($update_query);

                        echo "<script>alert('프로필 정보가 성공적으로 업데이트되었습니다.'); location.reload();</script>";

                    }
                }
            } else {
                echo "Profile not found.";
            }
        } catch (mysqli_sql_exception $e) {
            echo "SQL Error: " . $e->getMessage();
        }
    } else {
        echo "Profile ID is not set.";
    }

    $is_admin_query = "SELECT is_admin FROM PROFILE WHERE profile_id = $profile_id";
    $result = $conn->query($is_admin_query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $is_admin = $row['is_admin'];
        $store_id = $_GET['store_id'];

        if ($is_admin == 0) {
            $user_id = $_GET['user_id'];
            $redirect_link = "user_page.php?store_id=$store_id&user_id=$user_id";
        } else {
            $redirect_link = "admin_page.php?store_id=$store_id";
        }
    } else {
        $redirect_link = "index.php";
    }

    $conn->close();
    ?>

    <a href="<?php echo $redirect_link; ?>">뒤로 가기</a>
</body>
</html>
