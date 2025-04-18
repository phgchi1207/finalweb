<?php
include 'db.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thông tin khách hàng</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f0e3;
            margin: 0;
            padding: 0;
        }

        header {
            text-align: center;
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .container {
            background-color: #fff;
            width: 90%;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input[type='text'], input[type='tel'] {
            width: 100%;
            padding: 10px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<header>
    <h2>NHẬP THÔNG TIN KHÁCH HÀNG</h2>
    <p>Vui lòng điền chính xác để tiếp tục đặt món</p>
</header>

<div class="container">
    <form action="customer_save.php" method="POST">
        <div class="form-group">
            <label for="name">Họ và tên:</label>
            <input type="text" name="name" id="name" required placeholder="Nhập họ tên của bạn">
        </div>

        <div class="form-group">
            <label for="phone">Số điện thoại:</label>
            <input type="tel" name="phone" id="phone" required placeholder="VD: 0901234567">
        </div>

        <input type="hidden" name="table_id" value="1">

        <button type="submit">Tiếp tục đặt món</button>
    </form>
</div>

</body>
</html>
