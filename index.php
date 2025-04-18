<?php
include 'db.php';
session_start();

if (isset($_POST['confirm_order'])) {
    $_SESSION['selected_items'] = $_POST;
    header("Location: customer_info.php");
    exit();
}

$selected_items = $_SESSION['selected_items'] ?? [];

function get_quantity($array, $id) {
    return isset($array[$id]) ? intval($array[$id]) : 0;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hotpot Menu</title>
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
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
        }

        .menu-section {
            margin-bottom: 25px;
        }

        .menu-section h3 {
            margin-bottom: 12px;
            color: #d35400;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #ccc;
        }

        .item-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        input[type='number'], input[type='text'], input[type='tel'] {
            padding: 6px;
            font-size: 14px;
            width: 80px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
<header>
    <h2>🥘 HOTPOT MENU 🥢</h2>
    <p>Tuỳ chỉnh bữa ăn của bạn</p>
</header>

<div class="container">
<form method="POST">

    <!-- NƯỚC LẨU -->
    <div class="menu-section">
        <h3>1. Chọn nước lẩu</h3>
        <?php
        $result = $conn->query("SELECT * FROM soup_base");
        while($row = $result->fetch_assoc()) {
            $checked = isset($selected_items['soup_base']) && in_array($row['id'], $selected_items['soup_base']) ? 'checked' : '';
            echo "<div class='item'>
                    <label><input type='checkbox' name='soup_base[]' value='{$row['id']}' {$checked}> {$row['name']}</label>
                    <span>{$row['price']}đ</span>
                </div>";
        }
        ?>
    </div>

    <!-- TOPPING -->
    <div class="menu-section">
        <h3>2. Chọn topping</h3>
        <?php
        $result = $conn->query("SELECT * FROM toppings");
        while($row = $result->fetch_assoc()) {
            $qty = get_quantity($selected_items['toppings_qty'] ?? [], $row['id']);
            echo "<div class='item'>
                    <span>{$row['name']}</span>
                    <div class='item-right'>
                        <input type='number' name='toppings_qty[{$row['id']}]' min='0' value='{$qty}'>
                        <span>{$row['price']}đ</span>
                    </div>
                </div>";
        }
        ?>
    </div>

    <!-- NƯỚC CHẤM -->
    <div class="menu-section">
        <h3>3. Chọn nước chấm</h3>
        <?php
        $result = $conn->query("SELECT * FROM dipping_sauces");
        while($row = $result->fetch_assoc()) {
            $qty = get_quantity($selected_items['sauces_qty'] ?? [], $row['id']);
            echo "<div class='item'>
                    <span>{$row['name']}</span>
                    <div class='item-right'>
                        <input type='number' name='sauces_qty[{$row['id']}]' min='0' value='{$qty}'>
                        <span>{$row['price']}đ</span>
                    </div>
                </div>";
        }
        ?>
    </div>

    <!-- ĐỒ UỐNG -->
    <div class="menu-section">
        <h3>4. Chọn đồ uống</h3>
        <?php
        $result = $conn->query("SELECT * FROM drinks");
        while($row = $result->fetch_assoc()) {
            $qty = get_quantity($selected_items['drinks_qty'] ?? [], $row['id']);
            echo "<div class='item'>
                    <span>{$row['name']}</span>
                    <div class='item-right'>
                        <input type='number' name='drinks_qty[{$row['id']}]' min='0' value='{$qty}'>
                        <span>{$row['price']}đ</span>
                    </div>
                </div>";
        }
        ?>
    </div>

    <!-- MÓN ĂN KÈM -->
    <div class="menu-section">
        <h3>5. Món ăn kèm</h3>
        <?php
        $result = $conn->query("SELECT * FROM side_dishes");
        while($row = $result->fetch_assoc()) {
            $qty = get_quantity($selected_items['side_dishes_qty'] ?? [], $row['id']);
            echo "<div class='item'>
                    <span>{$row['name']}</span>
                    <div class='item-right'>
                        <input type='number' name='side_dishes_qty[{$row['id']}]' min='0' value='{$qty}'>
                        <span>{$row['price']}đ</span>
                    </div>
                </div>";
        }
        ?>
    </div>

    <input type="hidden" name="table_id" value="1">

    <div style="text-align: center;">
        <button type="submit" name="confirm_order">Xác nhận đơn hàng</button>
    </div>
</form>
</div>
</body>
</html>
