<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'courier') {
    header("Location: /public/login.php");
    exit;
}

require_once '../../config/connect-db.php';

$user_id = $_SESSION['user_id'];

$courier = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM couriers WHERE id_user = $user_id
"));

$orders = mysqli_query($conn, "
    SELECT o.id_order, o.sender_name, o.sender_phone, o.recipient_name, o.recipient_phone,
        o.weight, o.delivery_type, o.created_at,
        p1.address AS sender_address, p2.address AS recipient_address,
        c1.city_name AS sender_city, c2.city_name AS recipient_city
    FROM orders o
    JOIN pvz p1 ON o.sender_pvz = p1.id_pvz
    JOIN pvz p2 ON o.recipient_pvz = p2.id_pvz
    JOIN cities c1 ON p1.id_city = c1.id_city
    JOIN cities c2 ON p2.id_city = c2.id_city
    WHERE o.id_courier = $courier[id_courier] AND o.id_status NOT IN (4, 5)
    ORDER BY o.created_at ASC
");

$orders_list = mysqli_fetch_all($orders, MYSQLI_ASSOC);
$order_count = count($orders_list);
$total_cost  = array_sum(array_column($orders_list, 'cost'));

$type_labels = ['standard' => 'Стандарт', 'express' => 'Экспресс', 'premium' => 'Премиум'];
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Маршрутный лист</title>
    <link rel="stylesheet" href="/public/assets/css/route-list.css">
</head>

<body>

    <div class="route-header">
        <h1>МАРШРУТНЫЙ ЛИСТ ДОСТАВЩИКА</h1>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-key">Номер маршрутного листа</td>
            <td><?= $courier['id_courier'] ?>-<?= date('dmY') ?></td>
            <td class="info-key">Дата</td>
            <td><?= date('d.m.Y') ?></td>
        </tr>
        <tr>
            <td class="info-key">ФИО Доставщика</td>
            <td colspan="3"><?= $courier['full_name'] ?></td>
        </tr>
        <tr>
            <td class="info-key">Транспорт</td>
            <td colspan="3"><?= $courier['transport_type'] ?></td>
        </tr>
        <tr>
            <td class="info-key">Номер транспорта</td>
            <td><?= $courier['transport_number'] ?></td>
            <td class="info-key">Телефон доставщика</td>
            <td><?= $courier['phone'] ?></td>
        </tr>
    </table>

    <h2 class="section-h2">СПИСОК ДОСТАВОК</h2>

    <table class="orders-table">
        <thead>
            <tr>
                <th>Номер заказа</th>
                <th>Дата отправления</th>
                <th>Адрес отправления</th>
                <th>Адрес назначения</th>
                <th>Получатель</th>
                <th>Телефон</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders_list as $o): ?>
            <tr>
                <td><?= $o['id_order'] ?></td>
                <td><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
                <td><?= $o['sender_city'] ?>, <?= $o['sender_address'] ?></td>
                <td><?= $o['recipient_city'] ?>, <?= $o['recipient_address'] ?></td>
                <td><?= $o['recipient_name'] ?></td>
                <td><?= $o['recipient_phone'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2 class="section-h2">ФИНАНСОВАЯ ИНФОРМАЦИЯ</h2>

    <table class="info-table">
        <tr>
            <td class="info-key">Количество заказов</td>
            <td><?= $order_count ?></td>
        </tr>
        <tr>
            <td class="info-key">Сумма наличных</td>
            <td><?= number_format($total_cost, 0, '.', ' ') ?> ₽</td>
        </tr>
        <tr>
            <td class="info-key">Расходы (топливо/парковка)</td>
            <td></td>
        </tr>
        <tr>
            <td class="info-key">Итог к сдаче</td>
            <td></td>
        </tr>
    </table>

    <div class="notes">
        <p><strong>ПРИМЕЧАНИЯ:</strong></p>
        <div class="notes-line"></div>
        <div class="notes-line"></div>
    </div>

    <div class="signature">
        <p>Подпись курьера: __________</p>
    </div>

    <div class="print-btn-wrap no-print">
        <button onclick="window.print()" class="print-btn">Распечатать</button>
        <a href="/public/courier/dashboard.php" class="back-link">← Назад</a>
    </div>

</body>
</html>