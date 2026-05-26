<?php if (!isset($_SESSION)) session_start(); ?>
<header class="header">
    <div class="header-inner">
        <a class="logo" href="/index.php">JET<span>GO</span></a>

        <nav class="nav">
            <a class="nav-link" href="/index.php">Главная</a>
            <a class="nav-link" href="#">Услуги</a>
            <a class="nav-link" href="#">О нас</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a class="nav-link" href="/public/dashboard.php">Мои заказы</a>
            <?php endif; ?>
        </nav>

        <div class="nav">
            <?php if (!isset($_SESSION['user'])): ?>
                <a class="nav-link" href="/public/login.php">Войти</a>
                <a class="nav-btn" href="/public/reg.php">Регистрация</a>
            <?php else: ?>
                <span class="nav-user"><?= $_SESSION['name'] ?></span>
                <a class="nav-new-order" href="/public/order-new.php">+ Заказ</a>
                <a class="nav-logout" href="/src/auth/logout.php">Выйти</a>
            <?php endif; ?>
        </div>

        <button class="theme-toggle" id="themeToggle">☀</button>
    </div>
</header>
