<?php 
if (!isset($_SESSION)) session_start(); 
?>
<header class="header">
    <div class="header-inner">
        <a class="logo" href="/src/admin/index.php">JET<span>GO</span></a>
        <h1 class="page-title">Панель <strong>Администратора</strong></h1>
        <nav class="nav">
            <a class="nav-link" href="/src/admin/index.php">Заказы</a>
            <a class="nav-link" href="/src/admin/users.php">Пользователи</a>
        </nav>

        <div class="nav">
            <span class="nav-user"><?= $_SESSION['name'] ?></span>
            <a class="nav-logout" href="/src/auth/logout.php">Выйти</a>
        </div>

        <button class="theme-toggle" id="themeToggle">
            <img class="theme-logo" src="/public/assets/image/theme.png" alt="">
        </button>
    </div>
</header>
<script>
if (localStorage.getItem('theme') == 'light') {
    document.body.classList.add('light')
}
</script>