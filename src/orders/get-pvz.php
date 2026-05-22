<?php
require "../../config/connect-db.php";

$id_city = (int) $_GET['id_city'];
$result = mysqli_query($conn, "SELECT * FROM pvz WHERE id_city = $id_city");
$pvz = mysqli_fetch_all($result, MYSQLI_ASSOC);
echo json_encode($pvz);