<?php
session_start();

require_once '../config/connect-db.php';

$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id"));
$error = $_GET['error'] ?? '';

$cities = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM cities ORDER BY city_name"), MYSQLI_ASSOC);

$categories = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM categories"), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Новый заказ</title>
    <link rel="stylesheet" href="assets/css/general.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/order-new.css">
    <link rel="icon" href="assets/image/logo-page.png" type="image/png">
</head>

<body>

    <?php include '../templates/header.php'; ?>

    <div class="page-wrap">
        <div class="order-page-inner">

            <div>
                <h1 class="page-title">Оформить <strong>доставку</strong></h1>
                <p class="page-sub">Заполните данные отправителя и получателя</p>
            </div>

            <?php if ($error != ''): ?>
                <div class="flash-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" class="form-create-order" action="../src/orders/create.php">

                <div class="form-card">
                    <p class="form-section-title">Отправитель</p>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Имя отправителя</label>
                            <input class="form-input" type="text" name="sender_name" value="<?= $user['full_name'] ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Телефон</label>
                            <input class="form-input" type="tel" name="sender_phone" value="<?= $user['phone'] ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Город отправления</label>
                            <select class="form-select" name="sender_city" id="sender_city" required>
                                <option value="">выберите</option>
                                
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?= $city['id_city'] ?>"><?= $city['city_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Пункт выдачи заказов отправителя</label>
                            <select class="form-select" id="sender_pvz" name="sender_pvz" required>
                                <option value="">сначала выберите город</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <p class="form-section-title">Получатель</p>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Имя получателя</label>
                            <input class="form-input" type="text" name="recipient_name" placeholder="Иванов Иван"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Телефон</label>
                            <input class="form-input" type="tel" name="recipient_phone" placeholder="8(XXX)XXX-XX-XX"
                                required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Город доставки</label>
                            <select class="form-select" name="recipient_city" id="recipient_city" required>
                                <option value="">выберите</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?= $city['id_city'] ?>"><?= $city['city_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Пункт выдачи заказов получателя</label>
                            <select class="form-select" id="recipient_pvz" name="recipient_pvz" required>
                                <option value="">сначала выберите город</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <p class="form-section-title">Груз</p>
                    <div class="form-row desc-order">
                        <div class="form-group">
                            <label class="form-label">Описание</label>
                            <input class="form-input" type="text" name="description"
                                placeholder="Документы, одежда..." required>
                        </div>
                        <div class="form-second-group">
                            <div class="form-group">
                                <label class="form-label">Тип посылки</label>
                                <select class="form-select" name="category" id="category">
                                    <option value="">выберите тип посылки</option>
                                    <?php foreach($categories as $category): ?>
                                        <option value="<?= $category['id_category'] ?>"><?= $category['category_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Вес (кг)</label>
                                <input class="form-input" type="number" name="weight" id="weight" step="0.1"
                                    min="0.1" placeholder="1.5" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <p class="form-section-title">Тип доставки</p>
                    <div class="delivery-types">
                        <label class="delivery-card selected">
                            <input type="radio" name="delivery_type" value="standard" checked>
                            <div class="dt-name">Стандарт</div>
                            <div class="dt-desc">5–7 рабочих дней</div>
                            <div class="dt-price">от 150 ₽/кг</div>
                        </label>
                        <label class="delivery-card">
                            <input type="radio" name="delivery_type" value="express">
                            <div class="dt-name">Экспресс</div>
                            <div class="dt-desc">3–5 рабочих дня</div>
                            <div class="dt-price">от 280 ₽/кг</div>
                        </label>
                        <label class="delivery-card">
                            <input type="radio" name="delivery_type" value="premium">
                            <div class="dt-name">Премиум</div>
                            <div class="dt-desc">До 3-х дней</div>
                            <div class="dt-price">от 550 ₽/кг</div>
                        </label>
                    </div>

                    <div class="cost-row">
                        <span class="cost-label">Расчётная стоимость</span>
                        <span class="cost-num" id="cost_display">— ₽</span>
                    </div>
                    <input type="hidden" name="cost" id="cost_val">
                </div>

                <button type="submit" class="submit-btn">Оформить заказ →</button>

            </form>
        </div>
    </div>

    <?php include '../templates/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>

</html>