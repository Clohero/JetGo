<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /public/login.php");
    exit;
}

require_once "../../config/connect-db.php";

$order_id = (int) $_POST['order_id'];
$user_id  = $_SESSION['user_id'];

$check = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT id_status FROM orders
    WHERE id_order = $order_id AND id_user = $user_id
"));

if (!$check) {
    header("Location: /public/dashboard.php");
    exit;
}

if ($check['id_status'] == 4 || $check['id_status'] == 5) {
    header("Location: /public/order-view.php?id=$order_id&error=Нельзя отменить этот заказ");
    exit;
}

mysqli_query($conn, "UPDATE orders SET id_status = 5 WHERE id_order = $order_id AND id_user = $user_id");

header("Location: /public/order-view.php?id=$order_id");
exit;