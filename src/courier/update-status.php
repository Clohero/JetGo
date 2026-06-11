<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'courier') {
    header("Location: /public/login.php");
    exit;
}

require_once "../../config/connect-db.php";

$order_id  = (int) $_POST['order_id'];
$status_id = (int) $_POST['status_id'];

mysqli_query($conn, "UPDATE orders SET id_status = $status_id WHERE id_order = $order_id");

header("Location: /public/courier/dashboard.php");
exit;