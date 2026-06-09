<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <h2>JET<span>GO</span></h2>
            <p>Надёжная доставка грузов<br>по всей России.</p>
        </div>
        <div class="footer-col">
            <h3>Навигация</h3>
            <a href="/index.php">Главная</a>
            <a href="#">Услуги</a>
            <a href="#">О нас</a>
        </div>
        <div class="footer-col">
            <h3>Клиентам</h3>
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="/public/login.php">Оформить заказ</a>
                <a href="/public/login.php">Мои заказы</a>
            <?php else: ?>
                <a href="/public/order-new.php">Оформить заказ</a>
                <a href="/public/dashboard.php">Мои заказы</a>
            <?php endif; ?>

        </div>
        <div class="footer-col">
            <h3>Контакты</h3>
            <p>+7 (800) 555-35-35</p>
            <p>jetgo@mail.ru</p>
            <p>Уфа, Россия</p>
        </div>
    </div>
    <div class="footer-bottom">2026 JETGO - Все права защищены</div>
</footer>