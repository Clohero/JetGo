<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: /public/login.php");
    exit;
}

require_once "../../config/connect-db.php";

$total   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders"))[0];
$active  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE id_status NOT IN (4, 5)"))[0];
$done    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE id_status = 4"))[0];
$users   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE role = 'user'"))[0];

$statuses = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM status"), MYSQLI_ASSOC);

$orders = mysqli_query($conn, "
    SELECT o.id_order, o.sender_name, o.recipient_name, o.delivery_type, o.cost, o.created_at, s.state_name, o.id_status,
    c1.city_name AS sender_city,
    c2.city_name AS recipient_city
    FROM orders o
    JOIN status s ON o.id_status = s.id_status
    JOIN pvz p1 ON o.sender_pvz = p1.id_pvz
    JOIN pvz p2 ON o.recipient_pvz = p2.id_pvz
    JOIN cities c1 ON p1.id_city = c1.id_city
    JOIN cities c2 ON p2.id_city = c2.id_city
    ORDER BY o.created_at DESC
");

$type_labels = ['standard' => 'Стандарт', 'express' => 'Экспресс', 'premium' => 'Премиум'];
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Админ панель — JET GO</title>
    <link rel="stylesheet" href="../../public/assets/css/general.css">
    <link rel="stylesheet" href="../../public/assets/css/header.css">
    <link rel="stylesheet" href="../../public/assets/css/footer.css">
    <link rel="stylesheet" href="../../public/assets/css/admin.css">
</head>

<body>

    <?php include '../../templates/header.php'; ?>

    <div class="page-wrap">
        <div class="page-inner">

            <h1 class="page-title">Панель <strong>администратора</strong></h1>

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
                <div class="stat-card">
                    <div class="num"><?= $users ?></div>
                    <div class="lbl">Клиентов</div>
                </div>
            </div>

            <div class="admin-nav">
                <a class="admin-nav-link active" href="index.php">Заказы</a>
                <a class="admin-nav-link" href="users.php">Пользователи</a>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Маршрут</th>
                            <th>Отправитель</th>
                            <th>Получатель</th>
                            <th>Тип</th>
                            <th>Стоимость</th>
                            <th>Дата</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($o = mysqli_fetch_assoc($orders)): ?>
                        <tr>
                            <td class="order-id"><?= $o['id_order'] ?></td>
                            <td><?= $o['sender_city'] ?> → <?= $o['recipient_city'] ?></td>
                            <td><?= $o['sender_name'] ?></td>
                            <td><?= $o['recipient_name'] ?></td>
                            <td><?= $type_labels[$o['delivery_type']] ?></td>
                            <td class="order-cost"><?= number_format($o['cost'], 0, '.', ' ') ?> ₽</td>
                            <td class="order-date"><?= date('d.m.Y', strtotime($o['created_at'])) ?></td>
                            <td>
                                <form method="POST" action="update-status.php">
                                    <input type="hidden" name="order_id" value="<?= $o['id_order'] ?>">
                                    <select class="status-select" name="status_id" onchange="this.form.submit()">
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?= $status['id_status'] ?>" <?= $status['id_status'] == $o['id_status'] ? 'selected' : '' ?>>
                                                <?= $status['state_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <?php include '../../templates/footer.php'; ?>
</body>
<script src="/public/assets/js/main.js"></script>
</html>