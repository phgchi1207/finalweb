<?php
include 'db.php';
session_start();

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$note = $_POST['note'];
$table_id = $_POST['table_id'] ?? null;
$_SESSION['table_id'] = $table_id;


$stmt = $conn->prepare("INSERT INTO customers (name, phone, email, note) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $phone, $email, $note);
$stmt->execute();

$customer_id = $conn->insert_id; // hoặc dùng PDO thì là $conn->lastInsertId()
$_SESSION['customer_id'] = $customer_id;

// Lưu một thông báo thành công vào session (tùy chọn)
$_SESSION['customer_info_saved'] = true;

header("Location: order.php");
exit();
?>