<?php
include 'db.php';
session_start();

$table_id = $_SESSION['table_id'] ?? null;
$selected_items = $_SESSION['selected_items'] ?? [];

if (!$table_id || empty($selected_items)) {
    echo "Lỗi: Không tìm thấy thông tin đặt hàng.<br>Vui lòng quay lại trang menu.";
    exit;
}

$total_price = 0;
$customer_id = $_SESSION['customer_id'] ?? null;

if ($customer_id) {
    $stmt_order = $conn->prepare("INSERT INTO orders (table_id, customer_id, total_price) VALUES (?, ?, ?)");
    $stmt_order->bind_param("iii", $table_id, $customer_id, $total_price);
    $stmt_order->execute();
    $order_id = $conn->insert_id;
} else {
    echo "Lỗi: Không tìm thấy khách hàng. Vui lòng quay lại bước trước.";
    exit;
}

$ordered_items = [];

// Hàm insert các món có số lượng
function insert_items_with_qty($conn, $order_id, $type, $table, $qty_array, &$total_price, &$ordered_items) {
    foreach ($qty_array as $id => $qty) {
        $qty = intval($qty);
        $id = intval($id);
        if ($qty > 0) {
            $res = $conn->query("SELECT name, price FROM {$table} WHERE id = $id")->fetch_assoc();
            if ($res) {
                $name = $res['name'];
                $price = $res['price'];
                $stmt = $conn->prepare("INSERT INTO order_details (order_id, item_type, item_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("isidi", $order_id, $type, $id, $qty, $price);
                $stmt->execute();
                $total_price += $price * $qty;
                $ordered_items[] = ['type' => ucfirst(str_replace('_', ' ', $type)), 'name' => "$name x$qty", 'price' => $price * $qty];
            }
        }
    }
}

// Nước lẩu (checkbox)
foreach ($selected_items['soup_base'] ?? [] as $id) {
    $id = intval($id);
    $res = $conn->query("SELECT name, price FROM soup_base WHERE id = $id")->fetch_assoc();
    if ($res) {
        $name = $res['name'];
        $price = $res['price'];
        $qty = 1;
        $stmt = $conn->prepare("INSERT INTO order_details (order_id, item_type, item_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
        $type = 'hotpot_flavor';
        $stmt->bind_param("isidi", $order_id, $type, $id, $qty, $price);
        $stmt->execute();
        $total_price += $price;
        $ordered_items[] = ['type' => 'Hotpot flavor', 'name' => $name, 'price' => $price];
    }
}

insert_items_with_qty($conn, $order_id, 'topping', 'toppings', $selected_items['toppings_qty'] ?? [], $total_price, $ordered_items);
insert_items_with_qty($conn, $order_id, 'dipping_sauce', 'dipping_sauces', $selected_items['sauces_qty'] ?? [], $total_price, $ordered_items);
insert_items_with_qty($conn, $order_id, 'drink', 'drinks', $selected_items['drinks_qty'] ?? [], $total_price, $ordered_items);
insert_items_with_qty($conn, $order_id, 'side_dish', 'side_dishes', $selected_items['side_dishes_qty'] ?? [], $total_price, $ordered_items);

// Cập nhật tổng tiền
$stmt_update = $conn->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
$stmt_update->bind_param("ii", $total_price, $order_id);
$stmt_update->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f0e3;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            width: 90%;
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #d35400;
            text-align: center;
        }

        .success-message {
            color: #27ae60;
            text-align: center;
            font-size: 18px;
            margin-top: 10px;
        }

        .total-price {
            color: #c0392b;
            font-weight: bold;
            font-size: 1.4em;
            text-align: center;
            margin-top: 20px;
        }

        ul.item-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        ul.item-list li {
            border-bottom: 1px dashed #ccc;
            padding: 8px 0;
            display: flex;
            justify-content: space-between;
        }

        .back-button {
            display: block;
            margin: 30px auto 0;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
            max-width: 200px;
        }

        .back-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Đặt hàng thành công!</h2>
        <p class="success-message">Cảm ơn bạn đã đặt món tại nhà hàng của chúng tôi.</p>

        <ul class="item-list">
            <?php foreach ($ordered_items as $item): ?>
                <li>
                    <span><?= htmlspecialchars($item['type'] . ': ' . $item['name']) ?></span>
                    <span><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="total-price">Tổng cộng: <?= number_format($total_price, 0, ',', '.') ?>đ</p>

        <a href="index.php" class="back-button"> Quay lại trang menu</a>

    </div>
</body>
</html>
