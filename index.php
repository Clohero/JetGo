<?php
session_start();
require_once 'config/connect-db.php';

$cities = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM cities ORDER BY city_name"), MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JET GO</title>
    <link rel="stylesheet" href="public/assets/css/general.css">
    <link rel="stylesheet" href="public/assets/css/header.css">
    <link rel="stylesheet" href="public/assets/css/footer.css">
    <link rel="stylesheet" href="public/assets/css/index.css">
</head>

<body>

    <?php include 'templates/header.php'; ?>

    <main>

        <section class="hero">
            <div class="hero-content">
                <div class="hero-left">
                    <img class="hero-logo" src="public/assets/image/title01.png" alt="JET GO">
                    <div>
                        <h1 class="hero-title">Доставка грузов<br><strong>по России</strong></h1>
                        <p class="hero-subtitle">Экспресс, стандарт, любой тип доставки.<br>Доставка в точные сроки.</p>
                    </div>
                    <div class="hero-btns">
                        <?php if (!isset($_SESSION['user'])): ?>
                            <a class="btn-orange" href="public/reg.php">Начать доставку</a>
                            <a class="btn-outline" href="public/login.php">Войти</a>
                        <?php else: ?>
                            <a class="btn-orange" href="public/order-new.php">+ Новый заказ</a>
                            <a class="btn-outline" href="public/dashboard.php">Мои заказы</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <div id="services">
            <div class="section">
                <p class="section-tag">Услуги</p>
                <h2 class="section-title">Что мы <strong>предлагаем</strong></h2>
                <div class="services-grid">
                    <div class="service-card">
                        <h3>Стандартная доставка</h3>
                        <p>Оптимальный баланс цены и скорости. 5–7 рабочих дней.</p>
                        <span class="service-price">от 150 ₽/кг</span>
                    </div>
                    <div class="service-card">
                        <h3>Экспресс</h3>
                        <p>Срочные отправления. Приоритетная обработка. 3–5 дня.</p>
                        <span class="service-price">от 280 ₽/кг</span>
                    </div>
                    <div class="service-card">
                        <h3>Премиум доставка</h3>
                        <p>Персональное сопровождение и страховка груза. до 3-х дней</p>
                        <span class="service-price">от 550 ₽/кг</span>
                    </div>
                    <div class="service-card">
                        <h3>Личный кабинет</h3>
                        <p>Все ваши заказы в одном месте. Статус в реальном времени.</p>
                        <?php if (!isset($_SESSION['user'])): ?>
                            <a class="service-price btn" href="public/logingit.php">Перейти</a>
                        <?php else: ?>
                            <a class="service-price btn" href="public/dashboard.php">Перейти</a>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>

        <div id="cities">
            <div class="section">
                <p class="section-tag">Города</p>
                <h2 class="section-title">Доставляем <strong>по всей России</strong></h2>
                <div class="cities-grid">
                    <?php
                    foreach ($cities as $city) { ?>
                        <div class="city-tag"><?= $city['city_name'] ?></div>
                    <?php } ?>
                </div>
            </div>
        </div>

        

    </main>

    <?php include 'templates/footer.php'; ?>

    <script src="/public/assets/js/main.js"></script>
</body>
</html>