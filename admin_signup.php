<!DOCTYPE html>
<html>
<head>
    <title>관리자 및 가게 정보 입력</title>
</head>
<body>
    <h1>관리자 및 가게 정보 입력</h1>
    <form action="admin_register.php" method="post">
        <h2>관리자 정보</h2>
        <label for="adminName">이름:</label>
        <input type="text" name="adminName" id="adminName" required>
        <br>
        <label for="adminBirth">생년월일:</label>
        <input type="date" name="adminBirth" id="adminBirth" required>
        <br>
        <label for="adminPhone">핸드폰 번호:</label>
        <input type="text" name="adminPhone" id="adminPhone" required>
        <br>
        <label for="adminEmail">이메일:</label>
        <input type="email" name="adminEmail" id="adminEmail" required>
        <br>
        <label for="adminPw">비밀번호:</label>
        <input type="Pw" name="adminPw" id="adminPw" required>
        <br>

        <h2>가게 정보</h2>
        <label for="storeName">가게 이름:</label>
        <input type="text" name="storeName" id="storeName" required>
        <br>
        <label for="storeInfo">가게 정보:</label>
        <input type="text" name="storeInfo" id="storeInfo" required>
        <br>
        <label for="classification">가게 분류:</label>
        <input type="text" name="classification" id="classification" required>
        <br>
        <input type="submit" value="등록">
    </form>
</body>
</html>