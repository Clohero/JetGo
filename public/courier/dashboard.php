<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'courier') {
    header("Location: /public/login.php");
    exit;
}

require_once '../../config/connect-db.php';

$user_id = $_SESSION['user_id'];

$courier_info = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM couriers WHERE id_user = $user_id
"));

if (!$courier_info) {
    echo "Курьер не найден в системе. id_user = $user_id";
    exit;
}

$statuses = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM status WHERE id_status IN (3, 4)"), MYSQLI_ASSOC);

$orders = mysqli_query($conn, "
    SELECT o.id_order, o.sender_name, o.recipient_name, o.sender_phone, o.recipient_phone,
        o.description, o.weight, o.delivery_type, o.created_at, s.state_name, o.id_status,
        p1.address AS sender_address, p2.address AS recipient_address,
        c1.city_name AS sender_city, c2.city_name AS recipient_city
    FROM orders o
    JOIN status s ON o.id_status = s.id_status
    JOIN pvz p1 ON o.sender_pvz = p1.id_pvz
    JOIN pvz p2 ON o.recipient_pvz = p2.id_pvz
    JOIN cities c1 ON p1.id_city = c1.id_city
    JOIN cities c2 ON p2.id_city = c2.id_city
    WHERE o.id_courier = $courier_info[id_courier]
    ORDER BY o.created_at DESC
");

$orders_count = mysqli_num_rows($orders);

$type_labels = ['standard' => 'Стандарт', 'express' => 'Экспресс', 'premium' => 'Премиум'];
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Курьер — JET GO</title>
    <link rel="stylesheet" href="/public/assets/css/general.css">
    <link rel="stylesheet" href="/public/assets/css/header.css">
    <link rel="stylesheet" href="/public/assets/css/footer.css">
    <link rel="stylesheet" href="/public/assets/css/courier.css">
    <link rel="icon" href="../assets/image/logo-page.png" type="image/png">
</head>

<body>

    <?php include '../../templates/header-courier.php'; ?>

    <div class="page-wrap">
        <div class="courier-wrap">

            <div class="courier-content">
                <div class="courier-inner">

                    <h1 class="page-title">Панель курьера <strong><?= $_SESSION['name'] ?></strong></h1>

                    <?php if ($courier_info): ?>
                        <div class="courier-info-card">
                            <div class="courier-info-row">
                                <span class="courier-info-key">Транспорт</span>
                                <span><?= $courier_info['transport_type'] ?></span>
                            </div>
                            <div class="courier-info-row">
                                <span class="courier-info-key">Номер</span>
                                <span><?= $courier_info['transport_number'] ?></span>
                            </div>
                            <div class="courier-info-row">
                                <span class="courier-info-key">Телефон</span>
                                <span><?= $courier_info['phone'] ?></span>
                            </div>
                            <div class="courier-info-row">
                                <span class="courier-info-key">Количество заказов</span>
                                <span><?= $orders_count ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <a href="/public/courier/route-list.php" class="btn-orange">Сформировать маршрутный лист</a>
                    <p class="section-tag">Список заказов</p>
                    
                    <div class="button-functional">
                        <form method="POST" action="/src/courier/update-status-all.php">
                            <input type="hidden" name="courier_id" value="<?= $courier_info['id_courier'] ?>">
                            <button type="submit" class="status-btn">Обновить все статусы</button>
                        </form>
    
                        <form method="POST" action="/src/courier/clear-done.php">
                            <input type="hidden" name="courier_id" value="<?= $courier_info['id_courier'] ?>">
                            <button type="submit" class="status-btn">Очистить завершённые</button>
                        </form>
                    </div>

                    <div class="courier-table-wrap">
                        <table class="courier-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Маршрут</th>
                                    <th>Отправитель</th>
                                    <th>Получатель</th>
                                    <th>Тип</th>
                                    <th>Статус</th>
                                    <th>Смена статуса</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($o = mysqli_fetch_assoc($orders)): ?>
                                    <tr>
                                        <td class="order-id"><?= $o['id_order'] ?></td>
                                        <td><?= $o['sender_city'] ?> → <?= $o['recipient_city'] ?></td>
                                        <td>
                                            <div><?= $o['sender_name'] ?></div>
                                            <div class="order-sub"><?= $o['sender_phone'] ?></div>
                                        </td>
                                        <td>
                                            <div><?= $o['recipient_name'] ?></div>
                                            <div class="order-sub"><?= $o['recipient_phone'] ?></div>
                                        </td>
                                        <td><?= $type_labels[$o['delivery_type']] ?></td>
                                        <td class="order-status"><?= $o['state_name'] ?></td>
                                        <td>
                                            <?php if ($o['id_status'] == 4 || $o['id_status'] == 5): ?>
                                                <span style="font-size:12px; color:var(--muted)">Завершён</span>
                                            <?php else: ?>
                                                <form method="POST" action="/src/courier/update-status.php">
                                                    <input type="hidden" name="order_id" value="<?= $o['id_order'] ?>">
                                                    <?php if ($o['id_status'] < 3): ?>
                                                        <input type="hidden" name="status_id" value="3">
                                                        <button type="submit" class="status-btn">В пути</button>
                                                    <?php else: ?>
                                                        <input type="hidden" name="status_id" value="4">
                                                        <button type="submit" class="status-btn">Доставлен</button>
                                                    <?php endif; ?>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php include '../../templates/footer.php'; ?>
</body>
<script src="/public/assets/js/main.js"></script>

</html>