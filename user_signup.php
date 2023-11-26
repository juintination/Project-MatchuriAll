<!DOCTYPE html>
<html>
<head>
    <title>일반 회원 회원가입</title>
</head>
<body>
    <h1>일반 회원 회원가입</h1>
    <form action="user_register.php" method="post">
        <label for="storeID">가게 선택:</label>
        <select name="storeID" id="storeID" required>
            <?php
            // DB 정보 불러오기
            include 'db_info.php';

            // 가게 목록 불러오기
            $sql = "SELECT store_id, store_name FROM STORE";
            $result = oci_parse($conn, $sql);
            oci_execute($result);

            while ($row = oci_fetch_assoc($result)) {
                $storeID = $row["STORE_ID"];
                $storeName = $row["STORE_NAME"];
                echo "<option value='$storeID'>$storeName</option>";
            }

            oci_free_statement($result);
            oci_close($conn);
            ?>
        </select>
        <br>
        <label for="customerName">이름:</label>
        <input type="text" name="customerName" id="customerName" required>
        <br>
        <label for="customerBirth">생년월일:</label>
        <input type="date" name="customerBirth" id="customerBirth" required>
        <br>
        <label for="customerSex">성별:</label>
        <select name="customerSex" id="customerSex" required>
            <option value="남자">남자</option>
            <option value="여자">여자</option>
            <option value="기타">기타</option>
        </select>
        <br>
        <label for="customerPhone">핸드폰 번호:</label>
        <input type="text" name="customerPhone" id="customerPhone" required>
        <br>
        <label for="customerEmail">이메일:</label>
        <input type="text" name="customerEmail" id="customerEmail" required>
        <br>
        <label for="customerPw">비밀번호:</label>
        <input type="text" name="customerPw" id="customerPw" required>
        <br>
        <input type="submit" value="회원가입">
    </form>
</body>
</html>