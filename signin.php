<!DOCTYPE html>
<html>
<head>
    <title>로그인 페이지</title>
</head>
<body>
    <h1>로그인</h1>
    <form action="process_signin.php" method="post">
        <label for="store_id">가게 선택:</label>
        <select name="store_id" id="store_id">
            <?php
            // DB 정보 불러오기
            include 'db_info.php';

            // STORE 테이블에서 가게 목록을 가져오기
            $sql = "SELECT store_id, store_name FROM STORE";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['store_id'] . "'>" . $row['store_name'] . "</option>";
                }
            }

            $conn->close();
            ?>
        </select><br>

        <label for="user_type">사용자 유형 선택:</label>
        <select name="user_type" id="user_type">
            <option value="admin">관리자</option>
            <option value="user">일반 회원</option>
        </select><br>

        <label for="email">이메일:</label>
        <input type="text" name="email" id="email" required><br>

        <label for="password">비밀번호:</label>
        <input type="password" name="password" id="password" required><br>

        <input type="submit" value="로그인">
    </form>
</body>
</html>