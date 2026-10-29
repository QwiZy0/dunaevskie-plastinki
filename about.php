<?php
require_once __DIR__ . '/inc/bootstrap.php';

$pageTitle = 'О магазине · Dunaevskie Plastinki';
$current = 'about.php';
require __DIR__ . '/inc/header.php';
?>
    <main>
        <section>
            <h1 class="grad">О магазине</h1>
            <div class="two">
                <div>
                    <p class="lead">Dunaevskie Plastinki начались с полки дисков в комнате и ящика с пластинками, которые никто не хотел продавать.</p>
                    <p>Мы продаём винил для тех, кто вырос на поп-панке и эмо и слушает русскую альтернативу. Каждую пластинку проверяем, фотографируем конверт и честно описываем состояние.</p>
                    <p>А ещё мы достаём любые пластинки под заказ. Если вы давно ищете альбом, просто <a href="order.php">напишите нам</a>.</p>
                </div>
                <div class="stats">
                    <div class="box"><strong>20</strong><span>позиций в каталоге</span></div>
                    <div class="box"><strong>2023</strong><span>год основания</span></div>
                    <div class="box"><strong>1–3 дня</strong><span>ответ на заказ</span></div>
                    <div class="box"><strong>100%</strong><span>проверка перед отправкой</span></div>
                </div>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/inc/footer.php'; ?>
