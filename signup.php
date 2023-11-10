<!DOCTYPE html>
<html>
<head>
    <title>회원가입</title>
</head>
<body>
    <h2>회원가입</h2>
    <form action="process_signup.php" method="post">
        <label for="role">역할을 선택하세요:</label>
        <select id="role" name="role">
            <option value="admin">관리자</option>
            <option value="user">일반 사용자</option>
        </select>
        <br>
        <input type="submit" value="계속하기">
    </form>
</body>
</html>