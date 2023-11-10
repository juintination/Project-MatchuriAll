<!DOCTYPE html>
<html>
<head>
    <title>User Page</title>
</head>
<body>
    <h1>Welcome to the User Page</h1>
    
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

    // 사용자 정보를 데이터베이스에서 가져오는 쿼리
    $user_id = $_GET['user_id'];
    $sql = "SELECT * FROM USER WHERE user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // 사용자 정보를 출력
        echo "<p><strong>User Name:</strong> " . $row['user_name'] . "</p>";
        echo "<p><strong>Date of Birth:</strong> " . $row['user_birth'] . "</p>";
        echo "<p><strong>Gender:</strong> " . $row['user_sex'] . "</p>";
        echo "<p><strong>Phone Number:</strong> " . $row['user_phone'] . "</p>";
        
        // 사용자에 대한 추가 정보 및 기능을 이어서 추가할 수 있습니다.
    } else {
        echo "User not found.";
    }

    $conn->close();
    ?>

    <!-- 여기에서 사용자에 대한 다양한 정보 및 기능을 추가할 수 있습니다. -->
    <h1>처음으로 돌아가기</h1>
    <form action="index.php">
        <input type="submit" value="돌아가기">
    </form>
</body>
</html>