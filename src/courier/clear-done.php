<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'courier') {
    header("Location: /public/login.php");
    exit;
}

require_once "../../config/connect-db.php";

$courier_id = (int) $_POST['courier_id'];

mysqli_query($conn, "
    UPDATE orders SET id_courier = NULL
    WHERE id_courier = $courier_id AND id_status IN (4, 5)
");

header("Location: /public/courier/dashboard.php");
exit;