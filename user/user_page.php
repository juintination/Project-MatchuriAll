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
                
        /*Table Style*/
        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        th,td {
            padding: 6px 15px;
        }

        th {
            background: #4d59db;
            color: #fff;
            text-align: left;
        }

        tr:first-child th:first-child {
            border-top-left-radius: 6px;
        }

        tr:first-child th:last-child {
            border-top-right-radius: 6px;
        }

        td {
            border-right: 1px solid #c6c9cc;
            border-bottom: 1px solid #c6c9cc;
        }
        
        td:first-child {
            border-left: 1px solid #c6c9cc;
        }

        tr:nth-child(even) td {
            background: #eaeaed;
        }

        tr:last-child td:first-child {
            border-bottom-left-radius: 6px;
        }
        
        tr:last-child td:last-child {
            border-bottom-right-radius: 6px;
        }
    </style>
    <link rel="stylesheet" href="../css/userpage.css">    
</head>
<body>
    <div class="container">
        <?php
            // DB 정보 불러오기
            include '../db_info.php';

            // 고객 정보를 데이터베이스에서 가져오는 쿼리
            $customer_id = $_GET['customer_id'];
            $store_id = $_GET['store_id'];
            $sql = "SELECT CUSTOMER.*, STORE.store_name, STORE.classification
                    FROM CUSTOMER, STORE
                    WHERE CUSTOMER.store_id = STORE.store_id
                    AND CUSTOMER.customer_id = :customer_id";
            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':customer_id', $customer_id);
            oci_execute($stmt);

            $customerRow = oci_fetch_assoc($stmt);

            if ($customerRow) {
                // 고객의 프로필 정보를 데이터베이스에서 가져오는 쿼리
                $profile_id = $customerRow['PROFILE_ID'];
        ?>
        <a href='../index.php' class='button'>로그아웃</a>
        <a href="../profile/edit_profile.php?profile_id=<?php echo $profile_id; ?>&store_id=<?php echo $store_id; ?>" class='button'>프로필 수정</a>
        <header style="margin-left: 10px">Welcome to the Customer Page</header>
        <div class="store_container">
            <div class="item_store">
                <?php
                    // 가게 정보를 출력
                    echo "<p style='font-size: 50px;'><strong>" . $customerRow['STORE_NAME'] . "</strong></p>";
                    echo "<p style='font-size: 20px; color: #CCC;'>" . $customerRow['CLASSIFICATION'] . "</p>";
                ?>
            </div>
        </div>
        <div class="info_container">
            <div class="item_profile">
                <?php
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
                            
                            echo "<p><strong>Profile Info:</strong></p>";
                            echo isset($profileRow['PROFILE_INFO']) ? "<p>" . $profileRow['PROFILE_INFO'] . "</p>" : "<p>No Profile Info</p>";
                            
                        } else {
                            echo "Profile not found.";
                        }
    
                        oci_free_statement($stmtProfile);
                ?>
            </div>
            <div class="item_user">
                <?php
                        // 고객 정보를 출력
                        echo "<p><strong>Customer Name:</strong> " . $customerRow['CUSTOMER_NAME'] . "</p>";
    
                        // 날짜 포맷팅
                        $customerBirthdate = date_create_from_format("d-M-y", $customerRow['CUSTOMER_BIRTH']);
                        echo "<p><strong>Date of Birth:</strong> " . $customerBirthdate->format('Y년 m월 d일') . "</p>";
                        
                        echo "<p><strong>Gender:</strong> " . $customerRow['CUSTOMER_SEX'] . "</p>";
                        echo "<p><strong>Phone Number:</strong> " . $customerRow['CUSTOMER_PHONE'] . "</p>";
                        echo "<p><strong>Point:</strong> " . $customerRow['CUSTOMER_POINT'] . " 포인트</p>";
                        
                        } else {
                            echo "Profile ID is not set.";
                        }
                ?>
            </div>
        </div>
        <div class="item_receipt">
            <?php
                    // 해당 고객의 구매 내역을 나열하는 쿼리
                    $sqlReceipt = "SELECT * FROM RECEIPT WHERE customer_id = :customer_id ORDER BY RECEIPT_ID ASC";
                    $stmtReceipt = oci_parse($conn, $sqlReceipt);
                    oci_bind_by_name($stmtReceipt, ':customer_id', $customer_id);
                    oci_execute($stmtReceipt);

                    // 구매 내역을 스타일이 적용된 카드로 표시
                    echo '<h2 style="margin-left: 5px;">구매 내역</h2>';
                    
                    // 표 헤더 출력
                    echo "<table>";
                    echo "<tr>";
                    echo "<th>구매 번호</th>";
                    echo "<th>가격(원)</th>";
                    echo "<th>결제 방법</th>";
                    echo "<th>구매 일자</th>";
                    echo "<th>상세 내역 보기</th>";
                    echo "</tr>";
                    
                    $rowReceipt = oci_fetch_assoc($stmtReceipt);
                    if ($rowReceipt) {
    
                        do {
                            echo "<tr >";
                            echo "<td>" . $rowReceipt['RECEIPT_ID'] . "</td>";
                            echo "<td>" . $rowReceipt['RECEIPT_PRICE'] . "</td>";
                            echo "<td>" . $rowReceipt['PAYMENT_METHOD'] . "</td>";
                            
                            // 날짜 포맷팅
                            $purchaseDate = date_create_from_format("d-M-y h.i.s A", $rowReceipt['RECEIPT_TIME']);
                            echo "<td>" . $purchaseDate->format('Y년 m월 d일 H시 i분 s초') . "</td>";
    
                            // 상세 내역 보기 링크
                            echo "<td><a class='order-detail-link' href='get_order_detail.php?receipt_id=" . $rowReceipt['RECEIPT_ID'] . "'>자세히</a></td>";
    
                            echo "</tr>";
                        } while ($rowReceipt = oci_fetch_assoc($stmtReceipt));
    
                        echo "</table>";

                    } else {
                        echo "<table>";
                        echo "<tr class='receipt-item'>";
                        echo "<td colspan='5'>아직 이 가게에서 아무런 상품도 구매하지 않았네요!</td>";
                        echo "</tr>";
                        echo "</table>";
                    }

                    oci_free_statement($stmtReceipt);

                } else {
                    echo "Customer not found.";
                }

                oci_free_statement($stmt);
                oci_close($conn);
            ?>
        </div>       
    </div>
</body>
</html>
