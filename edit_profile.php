<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        /* 프로필 사진 이미지 스타일 */
        img.profile_pic_style {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
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

        function confirmDelete() {
            var result = confirm("정말로 회원을 탈퇴하시겠습니까?");
            if (result) {
                document.getElementById("deleteForm").submit(); // 확인을 선택한 경우
            } else {
                // 취소를 선택한 경우(아무 동작 없음)
            }
        }
    </script>
</head>
<body>
    <h1>Edit Profile</h1>
    <?php
    // DB 정보 불러오기
    include 'db_info.php';

    if (isset($_GET['profile_id'])) {
        $profile_id = $_GET['profile_id'];
        $store_id = $_GET['store_id'];
        $sql = "SELECT * FROM PROFILE WHERE profile_id = $profile_id";

        // 프로필 사진 파일 삭제
        function deleteProfilePicture($profile_id, $conn) {
            $profile_pic_query = "SELECT * FROM PROFILE WHERE profile_id = $profile_id";
            $profile_pic_result = $conn->query($profile_pic_query);

            if ($profile_pic_result->num_rows > 0) {
                $row = $profile_pic_result->fetch_assoc();
                $profile_pic_path = $row['profile_pic'];
                // 파일 이름이 uploads/profile_default.png가 아닌 경우에만 삭제
                if ($profile_pic_path !== null && $profile_pic_path !== 'uploads/profile_default.png' && file_exists($profile_pic_path)) {
                    unlink($profile_pic_path);
                } else {
                    // echo "<script>alert('" . addslashes(error_get_last()['message']) . "');</script>";
                }
            }
        }

        // 파일 업로드
        function moveUploadedFile($profile_id, $file) {
            // 파일 정보 가져오기
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
        
            // uploads 폴더가 없다면 생성
            $uploadFolder = 'uploads';
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0777, true);
            }
        
            // 파일을 서버로 이동
            $new_profile_pic = "$uploadFolder/profile_pic_$profile_id." . pathinfo($file_name, PATHINFO_EXTENSION);
            
            if (move_uploaded_file($file_tmp, $new_profile_pic)) {
                return $new_profile_pic;
            } else {
                return null;
            }
        }

        try {
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // 사용자의 현재 프로필 정보를 출력
                echo "<p><strong>Profile ID:</strong> " . $row['profile_id'] . "</p>";
                echo "<p><strong>Profile Picture:</strong> " . $row['profile_pic'] . "</p>";
                if (!empty($row['profile_pic'])) {
                    echo "<img src='" . $row['profile_pic'] . "' alt='Profile Picture' class='profile_pic_style'>";
                } else {
                    echo "<p>No profile picture available.</p>";
                }
                echo "<p><strong>Profile Info:</strong> " . $row['profile_info'] . "</p>";

                // 프로필 정보 수정
                echo "<form action='' method='post' enctype='multipart/form-data'>";

                // 프로필 사진 변경
                echo "<label for='profile_pic'>프로필 사진 변경:</label>";
                echo "<input type='file' name='profile_pic' id='profile_pic' accept='.png, .jpg' onchange='checkFileSize()'><br>";

                // 프로필 정보 변경
                echo "<label for='profile_info'>프로필 정보 변경:</label>";
                echo "<textarea name='profile_info' id='profile_info'>" . $row['profile_info'] . "</textarea><br>";

                // 기본 이미지로 변경 버튼
                echo "<input type='submit' name='default_pic' value='기본 이미지로 변경'>";

                // 수정 완료 버튼
                echo "<input type='submit' name='submit' value='프로필 수정 완료'>";

                // 회원 탈퇴 버튼
                echo "<button type='button' onclick='confirmDelete()'>회원 탈퇴</button>";
                
                echo "</form>";

                // 삭제를 위한 별도의 폼
                echo "<form id='deleteForm' action='' method='post'>";
                echo "<input type='hidden' name='delete_user'>";
                echo "</form>";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST['default_pic'])) {
                        // 기본 이미지 복사
                        $default_profile_pic = 'uploads/profile_default.png';
                        $new_profile_pic = "uploads/profile_pic_$profile_id.png";
                        
                        if (copy($default_profile_pic, $new_profile_pic)) {
                            // 프로필 사진 업데이트 쿼리
                            $update_pic_query = "UPDATE PROFILE SET profile_pic = '$new_profile_pic' WHERE profile_id = $profile_id";
                            $conn->query($update_pic_query);
                            echo "<script>alert('프로필 사진이 기본 이미지로 변경되었습니다.'); window.location = 'edit_profile.php?profile_id=$profile_id&store_id=$store_id';</script>";
                        } else {
                            echo "<script>alert('프로필 사진을 기본 이미지로 변경하는 중에 오류가 발생했습니다.'); window.location = 'edit_profile.php?profile_id=$profile_id&store_id=$store_id';</script>";
                        }

                    } else if (isset($_POST['submit'])) {
                        // 프로필 수정 완료 버튼이 눌렸을 때
                        $new_profile_info = $_POST['profile_info'];

                        // 프로필 사진 업로드
                        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {

                            // 프로필 사진 파일 삭제 후 파일 업로드
                            deleteProfilePicture($profile_id, $conn);
                            $new_profile_pic = moveUploadedFile($profile_id, $_FILES['profile_pic']);

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

                        echo "<script>alert('프로필 정보가 성공적으로 업데이트되었습니다.'); window.location = 'edit_profile.php?profile_id=$profile_id&store_id=$store_id';</script>";

                    } else if (isset($_POST['delete_user'])) {
                        // 회원 탈퇴 버튼이 눌렸을 때
                        $is_admin_query = "SELECT * FROM PROFILE WHERE profile_id = $profile_id";
                        $result = $conn->query($is_admin_query);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            if ($row['is_admin'] == 1) {
                                echo "<script>alert('관리자에게 문의하시오.'); window.location = 'edit_profile.php?profile_id=$profile_id&store_id=$store_id';</script>";
                            }
                        }

                        $user_id_query = "SELECT user_id FROM USER WHERE profile_id = $profile_id";
                        $user_id_result = $conn->query($user_id_query);

                        if ($user_id_result->num_rows > 0) {
                            $row = $user_id_result->fetch_assoc();
                            $user_id = $row['user_id'];

                            // USER 테이블에서 삭제
                            $delete_user_query = "DELETE FROM USER WHERE user_id = $user_id";
                            $conn->query($delete_user_query);

                            // 프로필 사진 파일 삭제
                            deleteProfilePicture($profile_id, $conn);

                            // PROFILE 테이블에서 삭제
                            $delete_profile_query = "DELETE FROM PROFILE WHERE profile_id = $profile_id";
                            $conn->query($delete_profile_query);

                            echo "<script>alert('회원 탈퇴가 성공적으로 완료되었습니다.'); window.location = 'index.php';</script>";
                        } else {
                            echo "<script>alert('사용자 정보를 찾을 수 없습니다.'); window.location = 'index.php';</script>";
                        }
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

    $is_admin_query = "SELECT * FROM PROFILE WHERE profile_id = $profile_id";
    $result = $conn->query($is_admin_query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['is_admin'] == 0) {
            // 사용자인 경우 user_id를 가져오기
            $user_id_query = "SELECT user_id FROM USER WHERE profile_id = $profile_id";
            $user_id_result = $conn->query($user_id_query);

            if ($user_id_result->num_rows > 0) {
                $user_row = $user_id_result->fetch_assoc();
                $user_id = $user_row['user_id'];
                $redirect_link = "user_page.php?store_id=$store_id&user_id=$user_id";
            } else {
                echo "<script>alert('사용자 정보를 찾을 수 없습니다.'); window.location = 'index.php';</script>";
                exit;
            }
        } else {
            // 관리자인 경우
            $redirect_link = "admin_page.php?store_id=$store_id";
        }
    } else {
        // 프로필 정보를 찾을 수 없는 경우
        $redirect_link = "index.php";
    }

    $conn->close();
    ?>

    <a href="<?php echo $redirect_link; ?>">뒤로 가기</a>
</body>
</html>