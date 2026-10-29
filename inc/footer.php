
    <footer class="footer">
        <nav aria-label="Меню в подвале">
            <ul class="menu">
<?= render_menu($current) ?>
            </ul>
        </nav>
        <p>© 2026 Dunaevskie Plastinki · Брянск · Обновлено 27.10.2026 · <a href="index.php">На главную</a></p>
    </footer>

<?php if (empty($hideFab)): ?>
    <!-- Плавающая кнопка заказа -->
    <a class="fab" href="order.php">
        <img src="favicon.svg" alt="Иконка заказа" width="28" height="28">
        <span>Нет нужной? Закажем!</span>
    </a>
<?php endif; ?>

<?php if (!$user) { require __DIR__ . '/modal.php'; } ?>
</body>
</html>
