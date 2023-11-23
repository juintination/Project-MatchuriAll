<?php
$servername = "localhost"; // MySQL 서버 호스트
$username = "root"; // MySQL 사용자 이름
$password = "admin"; // MySQL 비밀번호
$database = "demoDB"; // 데이터베이스 이름

// MySQL에 연결
$conn = new mysqli($servername, $username, $password);

// 연결 검사
if ($conn->connect_error) {
   die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// DB 선택
$conn->select_db($database);
?>