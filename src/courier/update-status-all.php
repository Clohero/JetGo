<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'courier') {
    header("Location: /public/login.php");
    exit;
}

require_once "../../config/connect-db.php";

$courier_id = (int) $_POST['courier_id'];

$has_not_in_way = mysqli_fetch_row(mysqli_query($conn, "
    SELECT COUNT(*) FROM orders
    WHERE id_courier = $courier_id AND id_status < 3
"))[0];

if ($has_not_in_way > 0) {
    mysqli_query($conn, "
        UPDATE orders SET id_status = 3
        WHERE id_courier = $courier_id AND id_status < 3
    ");
} else {
    mysqli_query($conn, "
        UPDATE orders SET id_status = 4
        WHERE id_courier = $courier_id AND id_status = 3
    ");
}

header("Location: /public/courier/dashboard.php");
exit;