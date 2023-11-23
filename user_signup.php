<!DOCTYPE html>
<html>
<head>
    <title>일반 회원 회원가입</title>
</head>
<body>
    <h1>일반 회원 회원가입</h1>
    <form action="user_register.php" method="post">
        <label for="storeID">가게 선택:</label>
        <select name="storeID" id="storeID">
            <?php
            // DB 정보 불러오기
            include 'db_info.php';

            // 가게 목록 불러오기
            $sql = "SELECT store_id, store_name FROM STORE";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $storeID = $row["store_id"];
                    $storeName = $row["store_name"];
                    echo "<option value='$storeID'>$storeName</option>";
                }
            }

            $conn->close();
            ?>
        </select>
        <br>
        <label for="userName">이름:</label>
        <input type="text" name="userName" id="userName" required>
        <br>
        <label for="userBirth">생년월일:</label>
        <input type="date" name="userBirth" id="userBirth" required>
        <br>
        <label for="userSex">성별:</label>
        <select name="userSex" id="userSex" required>
            <option value="남자">남자</option>
            <option value="여자">여자</option>
            <option value="기타">기타</option>
        </select>
        <br>
        <label for="userPhone">핸드폰 번호:</label>
        <input type="text" name="userPhone" id="userPhone" required>
        <br>
        <label for="userEmail">이메일:</label>
        <input type="text" name="userEmail" id="userEmail" required>
        <br>
        <label for="userPw">비밀번호:</label>
        <input type="text" name="userPw" id="userPw" required>
        <br>
        <input type="submit" value="회원가입">
    </form>
</body>
</html>