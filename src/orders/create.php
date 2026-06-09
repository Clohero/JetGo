<?php
session_start();

require_once "../../config/connect-db.php";

$user_id = $_SESSION['user_id'];
$sender_name = trim($_POST['sender_name'] ?? '');
$sender_phone = trim($_POST['sender_phone'] ?? '');
$sender_city = trim($_POST['sender_city'] ?? '');
$sender_pvz = (int) $_POST['sender_pvz'];
$recip_name = trim($_POST['recipient_name'] ?? '');
$recip_phone = trim($_POST['recipient_phone'] ?? '');
$recip_city = trim($_POST['recipient_city'] ?? '');
$recip_pvz = (int) $_POST['recipient_pvz'];
$desc = trim($_POST['description'] ?? '');
$category = $_POST['category'] != '' ? (int) $_POST['category'] : 'NULL';
$weight = floatval($_POST['weight'] ?? 1);
$delivery_type = $_POST['delivery_type'] ?? 'standard';
$cost = floatval($_POST['cost'] ?? 0);

$values = [$sender_name, $sender_city, $sender_pvz, $recip_name, $recip_city, $recip_pvz, $desc];
foreach ($values as $value) {
    if ($value == '') {
        header("Location: /public/order-new.php?error=Заполните все поля");
        exit;
    }
}

$id_courier = 'NULL';

$free_courier = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT c.id_courier,
        COUNT(o.id_order) AS order_count
    FROM couriers c
    LEFT JOIN orders o ON c.id_courier = o.id_courier
        AND o.id_status NOT IN (4, 5)
    WHERE c.courier_status = 'active'
    GROUP BY c.id_courier
    HAVING COUNT(o.id_order) < 10
    ORDER BY COUNT(o.id_order) ASC
    LIMIT 1
"));

if ($free_courier) {
    $id_courier = $free_courier['id_courier'];
} else {
    header("Location: /public/order-new.php?error=Все курьеры заняты, попробуйте позже");
    exit;
}

$query = mysqli_query($conn, "INSERT INTO orders (id_user, sender_name, sender_phone, sender_pvz,
                                recipient_name, recipient_phone, recipient_pvz,
                                description, id_category, weight, delivery_type, cost, id_courier)
                                VALUES ($user_id, '$sender_name', '$sender_phone', $sender_pvz,
                                '$recip_name', '$recip_phone', $recip_pvz,
                                '$desc', $category, $weight, '$delivery_type', $cost, $id_courier)");

if ($query) {
    $order_id = mysqli_insert_id($conn);
    header("Location: /public/order-view.php?id=$order_id&success=1");
} else {
    header("Location: /public/order-new.php?error=Ошибка при создании заказа");
}
exit;
