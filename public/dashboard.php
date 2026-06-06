<?php
session_start();

require_once '../config/connect-db.php';

$user_id = $_SESSION['user_id'];

$total = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE id_user = $user_id"))[0];
$active = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE id_user = $user_id AND id_status NOT IN (5, 6)"))[0];
$done = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE id_user = $user_id AND id_status = 5"))[0];

$orders = mysqli_query($conn, "
    SELECT o.id_order, o.delivery_type, o.created_at, s.state_name,
    c1.city_name AS sender_city,
    c2.city_name AS recipient_city
    FROM orders o
    JOIN status s ON o.id_status = s.id_status
    JOIN pvz p1 ON o.sender_pvz = p1.id_pvz
    JOIN pvz p2 ON o.recipient_pvz = p2.id_pvz
    JOIN cities c1 ON p1.id_city = c1.id_city
    JOIN cities c2 ON p2.id_city = c2.id_city
    WHERE o.id_user = $user_id
    ORDER BY o.created_at DESC
");

$type_labels = ['standard' => 'Стандарт', 'express' => 'Экспресс', 'premium' => 'Премиум'];
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Мои заказы</title>
    <link rel="stylesheet" href="assets/css/general.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="icon" href="assets/image/logo-page.png" type="image/png">
</head>

<body>

    <?php include '../templates/header.php'; ?>

    <div class="page-wrap">
        <div class="page-inner">

            <div>
                <h1 class="page-title">Здравствуйте <strong><?= $_SESSION['name'] ?></strong></h1>
                <p class="page-sub"><?= $_SESSION['user'] ?></p>
            </div>

            <div class="stats-row">
                <div class="stat-card">
                    <div class="num"><?= $total ?></div>
                    <div class="lbl">Всего заказов</div>
                </div>
                <div class="stat-card">
                    <div class="num"><?= $active ?></div>
                    <div class="lbl">В процессе</div>
                </div>
                <div class="stat-card">
                    <div class="num"><?= $done ?></div>
                    <div class="lbl">Доставлено</div>
                </div>
                <div class="stat-action">
                    <a class="btn-orange" href="/public/order-new.php">+ Новый заказ</a>
                </div>
            </div>

            <p class="section-tag">История заказов</p>

            <?php if (mysqli_num_rows($orders) == 0): ?>
                <div class="empty-box">
                    <h3 class="empty-title">Заказов пока нет</h3>
                    <p class="empty-sub">Оформите первую доставку</p>
                    <a class="btn-orange" href="/public/order-new.php">Оформить заказ</a>
                </div>
            <?php else: ?>
                <div class="orders-list">
                    <?php while ($o = mysqli_fetch_assoc($orders)): ?>
                        <a class="order-row" href="/public/order-view.php?id=<?= $o['id_order'] ?>">
                            <div class="order-id"># <?= $o['id_order'] ?></div>
                            <div class="order-route">
                                <?= $o['sender_city'] ?>
                                <span class="order-arrow"> → </span>
                                <?= $o['recipient_city'] ?>
                            </div>
                            <div class="order-type"><?= $type_labels[$o['delivery_type']] ?></div>
                            <span class="badge"><?= $o['state_name'] ?></span>
                            <div class="order-date"><?= date('d.m.Y', strtotime($o['created_at'])) ?></div>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php include '../templates/footer.php'; ?>
</body>
<script src="/public/assets/js/main.js"></script>
</html>