<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: /public/login.php");
    exit;
}

require_once "../../config/connect-db.php";

$users = mysqli_query($conn, "
    SELECT u.*, COUNT(o.id_order) AS orders_count
    FROM users u
    LEFT JOIN orders o ON u.id = o.id_user
    GROUP BY u.id
    ORDER BY u.id
");
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Пользователи — JET GO</title>
    <link rel="stylesheet" href="../../public/assets/css/general.css">
    <link rel="stylesheet" href="../../public/assets/css/header.css">
    <link rel="stylesheet" href="../../public/assets/css/footer.css">
    <link rel="stylesheet" href="../../public/assets/css/admin.css">
    <link rel="icon" href="../../public/assets/image/logo-page.png" type="image/png">
</head>

<body>

    <?php include '../../templates/header.php'; ?>

    <div class="page-wrap">
        <div class="page-inner">

            <h1 class="page-title">Пользователи <strong>системы</strong></h1>

            <div class="admin-nav">
                <a class="admin-nav-link" href="index.php">Заказы</a>
                <a class="admin-nav-link active" href="users.php">Пользователи</a>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ФИО</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Заказов</th>
                            <th>Роль</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($u = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td class="order-id"><?= $u['id'] ?></td>
                            <td><?= $u['full_name'] ?></td>
                            <td><?= $u['email'] ?></td>
                            <td><?= $u['phone'] ?></td>
                            <td class="order-cost"><?= $u['orders_count'] ?></td>
                            <td><span class="badge"><?= $u['role'] ?></span></td>
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