<?php
session_start();

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Вход — JET GO</title>
    <link rel="stylesheet" href="assets/css/general.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body class="auth-page">

    <?php include '../templates/header.php'; ?>

    <main class="auth-main">
        <div class="auth-container">
            <div class="auth-card">
                <p class="auth-badge">JET GO / Авторизация</p>
                <h1 class="auth-title">Войдите в<br><strong>аккаунт</strong></h1>

                <?php if ($error != ''): ?>
                    <div class="flash-error"><?= $error ?></div>
                <?php endif; ?>

                <form class="auth-form" method="POST" action="../src/auth/login-db.php">
                    <div class="form-group">
                        <label class="form-label">Электронная почта</label>
                        <input class="form-input" type="email" name="email" placeholder="you@example.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Пароль</label>
                        <input class="form-input" type="password" name="password" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="auth-btn">Войти</button>
                </form>

                <p class="auth-switch">Нет аккаунта? <a href="reg.php">Зарегистрироваться</a></p>
            </div>
        </div>

    </main>

    <?php include '../templates/footer.php'; ?>
</body>
<script src="/public/assets/js/main.js"></script>
</html>