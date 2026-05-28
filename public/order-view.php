<?php
session_start();

require_once '../config/connect-db.php';

$user_id = $_SESSION['user_id'];
$order_id = intval($_GET['id'] ?? 0);
$success = $_GET['success'] ?? '';

$result = mysqli_query($conn, "
    SELECT o.*, s.state_name
    FROM orders o
    JOIN status s ON o.id_status = s.id_status
    WHERE o.id_order = $order_id AND o.id_user = $user_id
    LIMIT 1
");

if (mysqli_num_rows($result) == 0) {
    header("Location: /public/dashboard.php");
    exit;
}

$o = mysqli_fetch_assoc($result);

$sender_pvz_info = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT p.address, c.city_name
    FROM pvz p
    JOIN cities c ON p.id_city = c.id_city
    WHERE p.id_pvz = $o[sender_pvz]
"));

$recipient_pvz_info = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT p.address, c.city_name
    FROM pvz p
    JOIN cities c ON p.id_city = c.id_city
    WHERE p.id_pvz = $o[recipient_pvz]
"));

$category_info = mysqli_fetch_assoc((mysqli_query($conn, "
    SELECT category_name
    FROM categories
    JOIN orders ON categories.id_category = orders.id_category
    WHERE categories.id_category = $o[id_category]
")));

$type_labels = ['standard' => 'Стандарт (5–7 дней)', 'express' => 'Экспресс (3–5 дня)', 'premium' => 'premium (1 - 3 дня)'];
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Заказ #<?= $o['id_order'] ?></title>
    <link rel="stylesheet" href="assets/css/general.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/order-view.css">
</head>

<body>

    <?php include '../templates/header.php'; ?>

    <div class="page-wrap">
        <div class="page-inner">

            <div class="page-header">
                <a class="back-link" href="/public/dashboard.php">Назад</a>
                <h1 class="page-title">Заказ <strong># <?= $o['id_order'] ?></strong></h1>
                <span class="badge"><?= $o['state_name'] ?></span>
            </div>

            <?php if ($success != ''): ?>
                <div class="flash-success">Заказ успешно оформлен!</div>
            <?php endif; ?>

            <div class="detail-grid">

                <div class="detail-left">
                    <div class="detail-card">
                        <p class="detail-section-title">Отправитель</p>
                        <div class="detail-row">
                            <span class="detail-key">Имя</span>
                            <span><?= $o['sender_name'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Телефон</span>
                            <span><?= $o['sender_phone'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Город</span>
                            <span><?= $sender_pvz_info['city_name'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Адрес</span>
                            <span><?= $sender_pvz_info['address'] ?></span>
                        </div>
                    </div>

                    <div class="detail-card">
                        <p class="detail-section-title">Получатель</p>
                        <div class="detail-row">
                            <span class="detail-key">Имя</span>
                            <span><?= $o['recipient_name'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Телефон</span>
                            <span><?= $o['recipient_phone'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Город</span>
                            <span><?= $sender_pvz_info['city_name'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Адрес</span>
                            <span><?= $sender_pvz_info['address'] ?></span>
                        </div>
                    </div>

                    <div class="detail-card">
                        <p class="detail-section-title">Груз</p>
                        <div class="detail-row">
                            <span class="detail-key">Описание</span>
                            <span><?= $o['description'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Тип посылки</span>
                            <span><?= $category_info['category_name'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Вес</span>
                            <span><?= $o['weight'] ?> кг</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Тип</span>
                            <span><?= $type_labels[$o['delivery_type']] ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Стоимость</span>
                            <span class="detail-cost"><?= number_format($o['cost'], 0, '.', ' ') ?> ₽</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-key">Оформлен</span>
                            <span><?= date('d.m.Y H:i', strtotime($o['created_at'])) ?></span>
                        </div>
                    </div>
                </div>

                <div class="detail-right">
                    <div class="detail-card">
                        <p class="detail-section-title">Статус заказа</p>
                        <span class="badge"><?= $o['state_name'] ?></span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include '../templates/footer.php'; ?>
</body>
<script src="/public/assets/js/main.js"></script>   
</html>