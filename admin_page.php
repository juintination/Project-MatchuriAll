<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
</head>
<body>
    <h1>Welcome to the Admin Page</h1>

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

    // 관리자 정보를 데이터베이스에서 가져오는 쿼리
    $store_id = $_GET['store_id'];
    $sql = "SELECT ADMIN.*, STORE.store_name, STORE.classification
            FROM ADMIN
            LEFT JOIN STORE ON ADMIN.admin_id = STORE.admin_id
            WHERE STORE.store_id = $store_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $adminRow = $result->fetch_assoc();

        // 관리자 정보를 출력
        echo "<p><strong>Admin Name:</strong> " . $adminRow['admin_name'] . "</p>";
        echo "<p><strong>Date of Birth:</strong> " . $adminRow['admin_birth'] . "</p>";
        echo "<p><strong>Phone Number:</strong> " . $adminRow['admin_phone'] . "</p>";
        echo "<p><strong>Email:</strong> " . $adminRow['admin_email'] . "</p>";

        // 가게 정보를 출력
        echo "<p><strong>Store Name:</strong> " . $adminRow['store_name'] . "</p>";
        echo "<p><strong>Classification:</strong> " . $adminRow['classification'] . "</p>";

        // 관리자와 가게에 대한 추가 정보 및 기능을 이어서 추가할 수 있습니다.
    } else {
        echo "Admin not found.";
    }

    $conn->close();
    ?>
    <h1>처음으로 돌아가기</h1>
    <form action="index.php">
        <input type="submit" value="돌아가기">
    </form>
</body>
</html>