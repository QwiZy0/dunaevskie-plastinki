<?php
require_once __DIR__ . '/inc/bootstrap.php';

$pageTitle = 'Пластинка под заказ · Dunaevskie Plastinki';
$current = 'order.php';
$hideFab = true;
require __DIR__ . '/inc/header.php';
?>
    <main>
        <section class="order">
            <h1 class="grad">Найдём любую пластинку</h1>
            <p class="lead">Ищете альбом, которого нет в каталоге? Напишите нам. Мы поищем у поставщиков и коллекционеров и вернёмся с ценой.</p>
            <ol class="steps" id="how">
                <li>
                    <h3>Напишите, что ищете</h3>
                    <p>Исполнитель, альбом и пожелания к изданию. Если не помните название, опишите словами.</p>
                </li>
                <li>
                    <h3>Мы найдём и назовём цену</h3>
                    <p>Ответим за 1–3 дня. Вы решаете, брать или нет. Деньги вперёд мы не просим.</p>
                </li>
                <li>
                    <h3>Привезём пластинку</h3>
                    <p>Заберёте в Брянске или получите почтой по всей России.</p>
                </li>
            </ol>
        </section>

        <section>
            <h2>Что вы ищете?</h2>
            <form class="order-form" action="#" method="post">
                <label>Исполнитель
                    <input type="text" name="artist" placeholder="Например, Green Day" required>
                </label>
                <label>Альбом
                    <input type="text" name="album" placeholder="Например, Dookie">
                </label>

                <fieldset class="chips">
                    <legend>Какое издание</legend>
                    <input type="radio" name="edition" id="e1" value="любое" checked>
                    <label for="e1">Любое</label>
                    <input type="radio" name="edition" id="e2" value="новое">
                    <label for="e2">Новое</label>
                    <input type="radio" name="edition" id="e3" value="б/у">
                    <label for="e3">Б/у</label>
                    <input type="radio" name="edition" id="e4" value="цветной винил">
                    <label for="e4">Цветной винил</label>
                </fieldset>

                <label>Бюджет, ₽
                    <input type="text" name="budget" placeholder="Можно не указывать">
                </label>
                <label>Как с вами связаться
                    <input type="text" name="contact" placeholder="Телефон, Telegram или почта" required>
                </label>
                <label>Комментарий
                    <textarea name="comment" rows="4" placeholder="Год, страна издания, цвет винила, любые детали"></textarea>
                </label>

                <button class="btn-big" type="submit">Отправить заявку</button>
            </form>
        </section>
    </main>
<?php require __DIR__ . '/inc/footer.php'; ?>
