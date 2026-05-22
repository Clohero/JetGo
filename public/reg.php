<?php
session_start();

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Регистрация — JET GO</title>
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
                <p class="auth-badge">JET GO / Новый аккаунт</p>
                <div class="auth-title">
                    <h1 class="auth-title-first">Зарегестрируйте новый</h1>
                    <div class="auth-title-second">аккаунт</div>
                </div>

                <?php if ($error != ''): ?>
                    <div class="flash-error"><?= $error ?></div>
                <?php endif; ?>

                <form class="auth-form" method="POST" action="../src/auth/reg-db.php" autocomplete="off">
                    <div class="form-group">
                        <label class="form-label">Электронная почта</label>
                        <input class="form-input" type="email" name="email" placeholder="you@example.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Пароль</label>
                        <input class="form-input" type="password" name="password" placeholder="минимум 5 символов"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Полное имя (ФИО)</label>
                        <input class="form-input" type="text" name="full_name" placeholder="Иванов Иван Иванович"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Телефон</label>
                        <input class="form-input" type="tel" name="phone" placeholder="8(XXX)XXX-XX-XX" required>
                    </div>
                    <button type="submit" class="auth-btn">Зарегистрироваться</button>
                </form>

                <p class="auth-switch">Уже есть аккаунт? <a href="login.php">Войти</a></p>
            </div>
        </div>

    </main>

    <?php include '../templates/footer.php'; ?>
</body>

</html>