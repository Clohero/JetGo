<?php
session_start();

require_once "../../config/connect-db.php";

$user_id = $_SESSION['user_id'];
$sender_name = trim($_POST['sender_name'] ?? '');
$sender_phone = trim($_POST['sender_phone'] ?? '');
$sender_city = trim($_POST['sender_city'] ?? '');
$sender_pvz = trim($_POST['sender_pvz'] ?? '');
$recip_name = trim($_POST['recipient_name'] ?? '');
$recip_phone = trim($_POST['recipient_phone'] ?? '');
$recip_city = trim($_POST['recipient_city'] ?? '');
$recip_pvz = trim($_POST['recipient_pvz'] ?? '');
$cargo_desc = trim($_POST['cargo_description'] ?? '');
$category = trim($_POST['category'] ?? '');
$cargo_weight = floatval($_POST['cargo_weight'] ?? 1);
$delivery_type = $_POST['delivery_type'] ?? 'standard';
$cost = floatval($_POST['cost'] ?? 0);

$values = [$sender_name, $sender_city, $sender_pvz, $recip_name, $recip_city, $recip_pvz, $cargo_desc];
foreach($values as $value){
    if ($value == '') {
        header("Location: /public/order-new.php?error=Заполните все поля");
        exit;
    }
}

$query = mysqli_query($conn, "INSERT INTO orders (id_user, sender_name, sender_phone, sender_pvz,
                                recipient_name, recipient_phone, recipient_pvz,
                                cargo_description, id_category, cargo_weight, delivery_type, cost)
                                VALUES ($user_id, '$sender_name', '$sender_phone', '$sender_pvz',
                                '$recip_name', '$recip_phone', '$recip_pvz',
                                '$cargo_desc', $category, $cargo_weight, '$delivery_type', $cost)");

if ($query) {
    $order_id = mysqli_insert_id($conn);
    header("Location: /public/order-view.php?id=$order_id&success=1");
} else {
    header("Location: /public/order-new.php?error=Ошибка при создании заказа");
}
exit;
