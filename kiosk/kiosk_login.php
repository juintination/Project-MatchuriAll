<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin : 0 auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        button {
            background-color: #5865f5;
            color: white;
            cursor: pointer;
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        button:hover {
            background-color: #4d59db;
        }
        h1{
            font-family: 'Gothic A1', sans-serif;
        }
        label{
            text-align: center;
        }

    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Gothic+A1:wght@900&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    include '../db_info.php';

    // Retrieve store name based on store_id
    $store_id = $_GET['store_id'];
    $store_name_query = "SELECT store_name FROM STORE WHERE store_id = :store_id";
    $store_name_stmt = oci_parse($conn, $store_name_query);
    oci_bind_by_name($store_name_stmt, ':store_id', $store_id);
    oci_execute($store_name_stmt);
    $store_name_row = oci_fetch_assoc($store_name_stmt);
    $store_name = ($store_name_row) ? $store_name_row['STORE_NAME'] : 'Unknown Store';

    // Set the title dynamically
    echo "<h1>Welcome to $store_name's Kiosk!</h1>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $customer_phone = $_POST['customer_phone'];

        // 정규 표현식을 사용하여 형식 변환
        if ($customer_phone !== null) {
            // 숫자 이외의 문자는 제거
            $customer_phone = preg_replace("/[^0-9]/", "", $customer_phone);

            // 010-1234-5678 형식으로 변환
            if (strlen($customer_phone) === 11) {
                $customer_phone = substr($customer_phone, 0, 3) . '-' . substr($customer_phone, 3, 4) . '-' . substr($customer_phone, 7);
            }
        }

        $store_id = $_GET['store_id'];

        $sql = "SELECT customer_id FROM CUSTOMER WHERE customer_phone = :customer_phone AND store_id = :store_id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':customer_phone', $customer_phone);
        oci_bind_by_name($stmt, ':store_id', $store_id);
        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);

        if ($row) {
            $customer_id = $row['CUSTOMER_ID'];
            header("Location: kiosk.php?store_id=$store_id&customer_id=$customer_id");
            exit();
        } else {
            echo "<script>alert('전화번호를 확인하고 다시 시도하세요.'); window.location.href = 'kiosk_login.php?store_id=$store_id';</script>";
            exit();
        }

        oci_free_statement($stmt);
    }
    ?>

    <form method="post" action="">
        <label for="customer_phone">Customer Phone</label>
        <input type="text" id="customer_phone" name="customer_phone" required>
        <button type="submit">LOGIN</button>
    </form>


    <?php oci_close($conn); ?>
</body>
</html>