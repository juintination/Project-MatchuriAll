<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>edit profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        function previewImageAndCheckSize(event) {
            previewImage(event);
            checkFileSize();
        }

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

        function previewImage(event) {
            var input = event.target;

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var imagePreview = document.getElementById('profile_pic_preview');
                    imagePreview.innerHTML = '<img src="' + e.target.result + '" class="profile_pic_style" alt="uploaded image">';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function checkProfileInfo() {
            var profileInfo = document.getElementById('profile_info').value;

            // 프로필 정보 길이 확인
            if (profileInfo.length > 10) {
                alert('프로필 정보는 10글자 이하로 입력해주세요.');
                return false; // 변경 취소
            }

            return true; // 변경 진행
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            margin-top: 30px;
            background: #4d59db;
        }

        .card {
            border-radius: 3%;
            min-width: 800px;
        }

        img.profile_pic_style {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;

            border: 4px double #0073CF;
        }

        .btn-upload {
            width: 200px;
            height: 40px;
            background: #fff;
            border: 1px solid rgb(77, 77, 77);
            border-radius: 10px;
            font-weight: 400;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-upload:hover {
            background: rgb(77, 77, 77);
            color: #fff;
        }

        .text-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 150px 10px 150px;
        }

        .centered-link {
            display: flex;
            justify-content: center;
            margin-left: 670px;
        }

        #profile_pic {
            display: none;
        }
    </style>
    <script src='https://code.jquery.com/jquery-1.10.2.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@4.1.1/dist/js/bootstrap.bundle.min.js'></script>
    <script type='text/javascript'></script>
</head>

<body>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <div class="container">
        <div class="row flex-lg-nowrap">
            <?php
            // DB 정보 불러오기
            include '../db_info.php';

            if (isset($_GET['profile_id'])) {
                $profile_id = $_GET['profile_id'];
                $store_id = $_GET['store_id'];
                $sql = "SELECT * FROM PROFILE WHERE profile_id = :profile_id";
                try {
                    $stmt = oci_parse($conn, $sql);
                    oci_bind_by_name($stmt, ':profile_id', $profile_id);
                    oci_execute($stmt);

                    if (oci_fetch($stmt)) {
                        // Fetch BLOB data
                        $profile_pic = oci_result($stmt, 'PROFILE_PIC');

                        // 사용자의 현재 프로필 정보를 출력
                        echo "
                        <div class='col' style='padding: 10px 180px;'>
                            <div class='row'>
                                <div class='col mb-3'>
                                    <div class='card' style='padding: 10px'>
                                        <div class='card-body'>
                                            <div class='e-profile'>
                                                <div class='row'>
                                                    <div class='col-12 col-sm-auto mb-3 text-center' style='margin-left:130px;'>
                                                        <div class='mx-auto' style='display: flex; justify-content:center;'>
                                                            <div id='profile_pic_preview' style='position: absolute;'></div>";

                        // 프로필 사진 출력
                        if (!empty($profile_pic)) {
                            $profile_pic_base64 = base64_encode($profile_pic->load());
                            echo "<img src='data:image/png;base64, $profile_pic_base64' alt='Profile Picture' class='profile_pic_style'>";
                        } else {
                            echo "<p>No profile picture available.</p>";
                        }

                        echo "<form action='' method='post' enctype='multipart/form-data'></div></div></div>";

                        // 프로필 사진 변경
                        echo "<div class='col d-flex flex-column flex-sm-row justify-content-between mb-3'>
                            <div class='text-center text-sm-left mb-2 mb-sm-0'>
                                <div class='text-center text-sm-center'>
                                    <span class='badge badge-secondary'><p class='mb-0''>Profile ID: " . oci_result($stmt, 'PROFILE_ID') . "</p></span><br>
                                </div>
                                <label for='profile_pic'><div class='btn-upload'><i class='fa fa-fw fa-camera'></i>프로필 사진 수정</div></label>
                                <input type='file' name='profile_pic' id='profile_pic' accept='.png, .jpg' onchange='previewImageAndCheckSize(event)'>
                                <input type='submit' name='default_pic' class='btn-upload' value='기본 이미지로 변경'>
                            </div>
                        </div>";

                        // 프로필 정보 수정
                        echo "<ul class='nav nav-tabs'>
                                <li class='nav-item'><a class='active nav-link'>Edit Profile</a></li>
                            </ul>
                            <div class='tab-content pt-3'>
                                <div class='tab-pane active'>
                                    <form class='form' novalidate>
                                        <div class='row'>
                                            <div class='col'>
                                                <div class='row'>
                                                    <div class='col mb-3'>
                                                        <div class='form-group'>
                                                            <label>프로필 정보</label>
                                                            <textarea class='form-control' name='profile_info' id='profile_info' rows='4'>" . oci_result($stmt, 'PROFILE_INFO') . "</textarea><br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>";
            
                        $is_admin_query = "SELECT IS_ADMIN FROM PROFILE WHERE PROFILE_ID = :profile_id";
                        $stmtIsAdmin = oci_parse($conn, $is_admin_query);
                        oci_bind_by_name($stmtIsAdmin, ':profile_id', $profile_id);
                        oci_execute($stmtIsAdmin);
            
                        $rowIsAdmin = oci_fetch_assoc($stmtIsAdmin);
            
                        if ($rowIsAdmin) {
                            if ($rowIsAdmin['IS_ADMIN'] == 0) {
                                // 사용자인 경우 customer_id를 가져오기
                                $customer_id_query = "SELECT CUSTOMER_ID FROM CUSTOMER WHERE PROFILE_ID = :profile_id";
                                $stmtCustomerId = oci_parse($conn, $customer_id_query);
                                oci_bind_by_name($stmtCustomerId, ':profile_id', $profile_id);
                                oci_execute($stmtCustomerId);
            
                                $rowCustomerId = oci_fetch_assoc($stmtCustomerId);
            
                                if ($rowCustomerId) {
                                    $customer_id = $rowCustomerId['CUSTOMER_ID'];
                                    $redirect_link = "../user/user_page.php?store_id=$store_id&customer_id=$customer_id";
                                } else {
                                    echo "<script>alert('사용자 정보를 찾을 수 없습니다.'); window.location = '../index.php';</script>";
                                    exit;
                                }
            
                                oci_free_statement($stmtCustomerId);
                            } else if ($rowIsAdmin['IS_ADMIN'] == 1) {
                                // 관리자인 경우
                                $redirect_link = "../admin/admin_page.php?store_id=$store_id";
                            }
                        } else {
                            // 프로필 정보를 찾을 수 없는 경우
                            $redirect_link = "../index.php";
                        }

                        // 수정 완료 및 회원 탈퇴 버튼
                        echo "<div class='row'>
                                        <div class='col d-flex justify-content-end'>
                                            <div class='px-xl-3'>
                                                <a href='$redirect_link' class='btn btn-block btn-secondary'>
                                                    <i class='fa fa-arrow-left'></i>
                                                    <span>뒤로 가기</span>
                                                </a>
                                            </div>
                                            <div class='px-xl-3'>
                                                <button class='btn btn-block btn-secondary' onclick='confirmDelete()' name='delete_user'>
                                                    <i class='fa fa-sign-out'></i>
                                                    <span>회원 탈퇴</span>
                                                </button>
                                            </div>
                                            <div class='px-xl-3'>
                                                <button class='btn btn-primary' type='submit' name='submit' onclick='return checkProfileInfo()'>프로필 변경</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        ";
                        echo "</div></div></div><div>";

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
                                    // BLOB 데이터 초기화
                                    $blobData = null;

                                    // 파일 내용을 BLOB으로 읽기
                                    if ($new_profile_pic) {
                                        $blobData = file_get_contents($new_profile_pic);
                                    }

                                    // PROFILE 테이블 업데이트
                                    $updateProfileQuery = "UPDATE PROFILE SET profile_pic = EMPTY_BLOB() WHERE profile_id = :profile_id RETURNING profile_pic INTO :blobData";
                                    $profileStmt = oci_parse($conn, $updateProfileQuery);

                                    $blobDescriptor = oci_new_descriptor($conn, OCI_D_LOB);
                                    oci_bind_by_name($profileStmt, ':profile_id', $profile_id);
                                    oci_bind_by_name($profileStmt, ':blobData', $blobDescriptor, -1, OCI_B_BLOB);

                                    oci_execute($profileStmt, OCI_DEFAULT);

                                    if ($blobData) {
                                        $blobDescriptor->save($blobData);
                                    }

                                    oci_commit($conn);

                                    oci_free_statement($profileStmt);
                                    oci_free_descriptor($blobDescriptor);

                                    echo "<script>alert('프로필 사진이 기본 이미지로 변경되었습니다.'); window.location = 'edit_profile.php?profile_id=$profile_id&store_id=$store_id';</script>";
                                } else {
                                    $error = error_get_last();
                                    echo "<script>alert('프로필 사진을 기본 이미지로 변경하는 중에 오류가 발생했습니다. Error: " . addslashes($error['message']) . "');
                                    window.location = 'edit_profile.php?profile_id=$profile_id&store_id=$store_id';</script>";
                                }

                            } else if (isset($_POST['submit'])) {
                                // 프로필 수정 완료 버튼이 눌렸을 때
                                $new_profile_info = $_POST['profile_info'];

                                // 프로필 사진 업로드
                                if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                                    // uploads 디렉토리가 있는지 확인하고 없으면 생성
                                    $uploadFolder = 'uploads';
                                    if (!is_dir($uploadFolder)) {
                                        mkdir($uploadFolder, 0777, true);
                                    }

                                    // 고유한 파일 이름 생성 (덮어쓰기 방지)
                                    $new_profile_pic = $uploadFolder . "/profile_pic_$profile_id." . pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);

                                    // 업로드한 파일을 목적지로 이동
                                    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $new_profile_pic)) {
                                        // 데이터베이스에서 프로필 사진 업데이트
                                        $update_pic_query = "UPDATE PROFILE SET profile_pic = EMPTY_BLOB()
                                                                WHERE profile_id = :profile_id
                                                                RETURNING profile_pic INTO :blobData";
                                        $stmt_update_pic = oci_parse($conn, $update_pic_query);
                                        oci_bind_by_name($stmt_update_pic, ':profile_id', $profile_id);
                                        $blobDescriptor = oci_new_descriptor($conn, OCI_D_LOB);
                                        oci_bind_by_name($stmt_update_pic, ':blobData', $blobDescriptor, -1, OCI_B_BLOB);

                                        if (oci_execute($stmt_update_pic, OCI_DEFAULT)) {
                                            // BLOB 데이터 초기화
                                            $blobData = null;

                                            // 파일 내용을 BLOB으로 읽기
                                            if ($new_profile_pic) {
                                                $blobData = file_get_contents($new_profile_pic);
                                            }

                                            // BLOB 업데이트
                                            if ($blobData) {
                                                $blobDescriptor->save($blobData);
                                            }

                                            // 커밋
                                            oci_commit($conn);

                                            oci_free_statement($stmt_update_pic);
                                            oci_free_descriptor($blobDescriptor);
                                        } else {
                                            // 롤백을 수행하고 오류 메시지 표시
                                            oci_rollback($conn);
                                            oci_free_statement($stmt_update_pic);
                                            oci_free_descriptor($blobDescriptor);

                                            echo "<script>alert('프로필 사진을 업데이트하는 중에 오류가 발생했습니다.'); window.location = 'edit_profile.php?profile_id=$profile_id&store_id=$store_id';</script>";
                                        }
                                    } else {
                                        echo "<script>alert('프로필 사진을 업로드하는 중에 오류가 발생했습니다.'); window.location = 'edit_profile.php?profile_id=$profile_id&store_id=$store_id';</script>";
                                        exit;
                                    }
                                }

                                // 프로필 정보 업데이트 쿼리
                                $update_query = "UPDATE PROFILE SET profile_info = :new_profile_info WHERE profile_id = :profile_id";
                                $stmt_update = oci_parse($conn, $update_query);
                                oci_bind_by_name($stmt_update, ':new_profile_info', $new_profile_info);
                                oci_bind_by_name($stmt_update, ':profile_id', $profile_id);
                                oci_execute($stmt_update);
                                oci_free_statement($stmt_update);

                                echo "<script>alert('프로필 정보가 성공적으로 업데이트되었습니다.'); window.location = 'edit_profile.php?profile_id=$profile_id&store_id=$store_id';</script>";

                            } else if (isset($_POST['delete_user'])) {
                                // 회원 탈퇴 버튼이 눌렸을 때
                                $is_admin_query = "SELECT IS_ADMIN FROM PROFILE WHERE PROFILE_ID = :profile_id";
                                $stmtIsAdmin = oci_parse($conn, $is_admin_query);
                                oci_bind_by_name($stmtIsAdmin, ':profile_id', $profile_id);
                                oci_execute($stmtIsAdmin);

                                $rowIsAdmin = oci_fetch_assoc($stmtIsAdmin);

                                if ($rowIsAdmin) {
                                    if ($rowIsAdmin['IS_ADMIN'] == 1) {
                                        echo "<script>alert('관리자에게 문의해주세요.'); window.location = 'edit_profile.php?profile_id=$profile_id&store_id=$store_id';</script>";
                                        exit;
                                    }
                                }

                                $customer_id_query = "SELECT customer_id FROM CUSTOMER WHERE profile_id = :profile_id";
                                $stmt_customer_id = oci_parse($conn, $customer_id_query);
                                oci_bind_by_name($stmt_customer_id, ':profile_id', $profile_id);
                                oci_execute($stmt_customer_id);

                                if (oci_fetch($stmt_customer_id)) {
                                    $customer_id = oci_result($stmt_customer_id, 'CUSTOMER_ID');

                                    // CUSTOMER 테이블에서 삭제
                                    $delete_customer_query = "DELETE FROM CUSTOMER WHERE customer_id = :customer_id";
                                    $stmt_delete_customer = oci_parse($conn, $delete_customer_query);
                                    oci_bind_by_name($stmt_delete_customer, ':customer_id', $customer_id);
                                    oci_execute($stmt_delete_customer);
                                    oci_free_statement($stmt_delete_customer);

                                    // 파일 경로 설정
                                    $filePath = "uploads/profile_pic_$profile_id.png";

                                    // 파일이 존재하면 삭제
                                    if (file_exists($filePath)) {
                                        unlink($filePath);
                                    }

                                    // PROFILE 테이블에서 삭제
                                    $delete_profile_query = "DELETE FROM PROFILE WHERE profile_id = :profile_id";
                                    $stmt_delete_profile = oci_parse($conn, $delete_profile_query);
                                    oci_bind_by_name($stmt_delete_profile, ':profile_id', $profile_id);
                                    oci_execute($stmt_delete_profile);
                                    oci_free_statement($stmt_delete_profile);

                                    echo "<script>alert('회원 탈퇴가 성공적으로 완료되었습니다.'); window.location = '../index.php';</script>";
                                } else {
                                    echo "<script>alert('사용자 정보를 찾을 수 없습니다.'); window.location = '../index.php';</script>";
                                }
                                oci_free_statement($stmt_customer_id);
                            }
                        }
                    } else {
                        echo "Profile not found.";
                    }
                    oci_free_statement($stmt);
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Profile ID is not set.";
            }

            oci_free_statement($stmtIsAdmin);
            oci_close($conn);
            ?>
</body>
</html>