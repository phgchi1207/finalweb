
<?php
$host = "localhost";
$db = "hotpot_app";
$user = "root";
$pass = ""; // cập nhật nếu có mật khẩu

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
