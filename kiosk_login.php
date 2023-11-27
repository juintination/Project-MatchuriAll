<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Login</title>
</head>
<body>
    <?php
    include 'db_info.php';

    $store_id = $_GET['store_id'];
    $store_name_query = "SELECT store_name FROM STORE WHERE store_id = :store_id";
    $store_name_stmt = oci_parse($conn, $store_name_query);
    oci_bind_by_name($store_name_stmt, ':store_id', $store_id);
    oci_execute($store_name_stmt);
    $store_name_row = oci_fetch_assoc($store_name_stmt);
    $store_name = ($store_name_row) ? $store_name_row['STORE_NAME'] : 'Unknown Store';

    // Welcome to [STORE_NAME]'s Kiosk!
    echo "<h1>Welcome to $store_name's Kiosk!</h1>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $customer_phone = $_POST['customer_phone'];
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
        <label for="customer_phone">Customer Phone:</label>
        <input type="text" id="customer_phone" name="customer_phone" required>
        <button type="submit">Login</button>
    </form>

    <?php oci_close($conn); ?>
</body>
</html>