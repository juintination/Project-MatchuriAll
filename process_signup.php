<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["role"])) {
    $role = $_POST["role"];
    if ($role === "admin") {
        header("Location: admin_signup.php");
    } elseif ($role === "user") {
        header("Location: user_signup.php");
    } else {
        echo "올바른 역할을 선택하세요.";
    }
}
?>
