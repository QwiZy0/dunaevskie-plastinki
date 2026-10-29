<?php
require_once __DIR__ . '/inc/bootstrap.php';

$pageTitle = 'Главная — Dunaevskie Plastinki';
$current = 'index.php';
require __DIR__ . '/inc/header.php';
?>
    <main>

        <!-- Первый экран -->
        <section class="hero">
            <div>
                <h1 class="grad">Винил, который ты ищешь, найдётся у нас</h1>
                <p class="lead">В каталоге поп-панк, эмо и русская альтернатива. А если нужной пластинки нет, напиши нам, и мы её достанем.</p>
                <p>
                    <a class="btn" href="order.php">Заказать любую пластинку</a>
                    <a class="btn ghost" href="catalog.php">Открыть каталог</a>
                </p>
            </div>
            <div class="record-wrap" aria-hidden="true">
                <div class="record"><span>DP<br>33⅓</span></div>
            </div>
        </section>

        <!-- Бегущая строка с исполнителями -->
        <div class="ticker" aria-hidden="true">
            <div><span>Кишлак</span><span>Lil Peep</span><span>blink-182</span><span>Ногу Свело!</span><span>Fall Out Boy</span><span>Конец солнечных дней</span><span>Green Day</span><span>Paramore</span><span>My Chemical Romance</span><span>Кишлак</span><span>Lil Peep</span><span>blink-182</span><span>Ногу Свело!</span><span>Fall Out Boy</span><span>Конец солнечных дней</span><span>Green Day</span><span>Paramore</span><span>My Chemical Romance</span></div>
        </div>

        <!-- Главная фишка: заказ любой пластинки -->
        <section class="wanted">
            <h2>Нет нужной пластинки?<br>Мы достанем любую.</h2>
            <p class="wanted-line">Хочу найти:</p>
            <div class="rotator" aria-hidden="true">
                <ul>
                    <li>Dookie, Green Day</li>
                    <li>Редкое издание на цветном виниле</li>
                    <li>Альбом из детства</li>
                    <li>Пластинку, которой нет нигде</li>
                    <li>Dookie, Green Day</li>
                </ul>
            </div>
            <p class="wanted-buttons">
                <a class="btn-big" href="order.php">Заказать пластинку</a>
                <a class="btn-line" href="order.php#how">Как это работает</a>
            </p>
            <ul class="quick">
                <li><a href="order.php">Редкое издание</a></li>
                <li><a href="order.php">Цветной винил</a></li>
                <li><a href="order.php">Русский рок</a></li>
                <li><a href="order.php">Альбомы 90-х</a></li>
                <li><a href="order.php">Из-за границы</a></li>
            </ul>
        </section>

        <!-- Как мы работаем -->
        <section>
            <h2>Как мы работаем</h2>
            <div class="grid">
                <article class="box">
                    <h3>Проверка винила</h3>
                    <p>Смотрим диск на свет и слушаем обе стороны. Все дефекты указываем в описании.</p>
                </article>
                <article class="box">
                    <h3>Доставка по России</h3>
                    <p>Упаковываем в жёсткий картон и отправляем за 1–2 дня после оплаты.</p>
                </article>
                <article class="box">
                    <h3>Заказ под вас</h3>
                    <p>Нужной пластинки нет в каталоге? Найдём и привезём.</p>
                </article>
            </div>
        </section>

        <!-- Новые поступления -->
        <section>
            <h2>Новые поступления</h2>
            <div class="grid">
                    <article class="card">
                                            <div class="cover-img">
                                                <img src="images/blink182.webp" alt="blink-182 — Enema of the State">
                                            </div>
                                            <h2>blink-182</h2>
                                            <p class="album">Enema of the State, 1999</p>
                                            <p>Тот самый альбом с «All the Small Things». Классика поп-панка.</p>
                                            <p class="buy">
                                                <strong>7 400 ₽</strong>
                                                <span class="tag in">В наличии</span>
                                            </p>
                                        </article>

                    <article class="card">
                                            <div class="cover-img">
                                                <img src="images/Lil_Peep1.webp" alt="Lil Peep — Come Over When You're Sober, Pt. 1">
                                            </div>
                                            <h2>Lil Peep</h2>
                                            <p class="album">Come Over When You're Sober, Pt. 1, 2017</p>
                                            <p>Дебютный студийный альбом: гитарный эмо-рэп и мрачная нежность.</p>
                                            <p class="buy">
                                                <strong>8 900 ₽</strong>
                                                <span class="tag in">В наличии</span>
                                            </p>
                                        </article>

                    <article class="card">
                                            <div class="cover-img">
                                                <img src="images/lilpeep2.webp" alt="Lil Peep — Come Over When You're Sober, Pt. 2">
                                            </div>
                                            <h2>Lil Peep</h2>
                                            <p class="album">Come Over When You're Sober, Pt. 2, 2018</p>
                                            <p>Выпущен после ухода артиста, продолжает историю первой части.</p>
                                            <p class="buy">
                                                <strong>9 500 ₽</strong>
                                                <span class="tag few">Осталось 1–2</span>
                                            </p>
                                        </article>
            </div>
            <p><a class="btn ghost" href="catalog.php">Все пластинки</a></p>
        </section>

        <!-- Новости -->
        <section>
            <h2>Новости магазина</h2>
            <div class="grid">
                <article class="box">
                    <h3>Цветной винил</h3>
                    <p>В каталоге появились розовые и персиковые издания тиражом до 300 штук.</p>
                </article>
                <article class="box">
                    <h3>Предзаказ открыт</h3>
                    <p>Кишлак и Конец солнечных дней: бронируйте до конца ноября.</p>
                </article>
                <article class="box">
                    <h3>Скидка 10%</h3>
                    <p>На вторую пластинку в заказе. Акция действует всю осень.</p>
                </article>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/inc/footer.php'; ?>
