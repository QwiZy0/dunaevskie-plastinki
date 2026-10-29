<?php
require_once __DIR__ . '/inc/bootstrap.php';

$pageTitle = 'Контакты · Dunaevskie Plastinki';
$current = 'contacts.php';
require __DIR__ . '/inc/header.php';
?>
    <main>
        <section>
            <h1 class="grad">Контакты</h1>
            <div class="two">
                <div>
                    <p class="lead">Заходите в магазин или пишите, подберём пластинку под ваш вкус.</p>
                    <p>
                        <strong>Адрес:</strong> г. Брянск, остановка «Стальзавод»<br>
                        <strong>Телефон:</strong> +7 (900) 000-00-00<br>
                        <strong>Почта:</strong> hello@dunaevskie.example<br>
                        <strong>Часы работы:</strong> ежедневно, 12:00–21:00
                    </p>
                    <p><a class="btn-big" href="order.php">Заказать пластинку</a></p>
                </div>
                <div class="map">
                    <iframe title="Карта проезда: Брянск, остановка Стальзавод" loading="lazy" src="https://yandex.ru/map-widget/v1/?mode=search&amp;text=%D0%91%D1%80%D1%8F%D0%BD%D1%81%D0%BA%2C%20%D0%BE%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0%20%D0%A1%D1%82%D0%B0%D0%BB%D1%8C%D0%B7%D0%B0%D0%B2%D0%BE%D0%B4&amp;z=16"></iframe>
                </div>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/inc/footer.php'; ?>
