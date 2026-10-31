<?php
require_once __DIR__ . '/inc/bootstrap.php';

$pageTitle = 'Каталог — Dunaevskie Plastinki';
$current = 'catalog.php';
require __DIR__ . '/inc/header.php';
?>
    <main>
        <section>
            <h1 class="grad">Каталог</h1>
            <p class="lead">20 пластинок: от зарубежного поп-панка до русского инди. Наведите курсор на обложку, и диск выглянет из конверта.</p>
            
            <div class="catalog-layout">

                <!-- Блок фильтра: чекбоксы создаёт script.js -->
                <aside class="filters" aria-label="Фильтр пластинок">
                    <h2>Фильтр</h2>
                    <div id="filters"></div>
                    <p class="found">Найдено: <strong id="found-count">0</strong> из <span id="total-count">0</span></p>
                    <button type="button" class="btn ghost" id="reset-filters">Сбросить</button>
                </aside>

                <div>
                    <p class="empty" id="empty-message" style="display: none;">По таким условиям ничего нет. Сбросьте часть галочек или <a href="order.php">закажите пластинку</a>.</p>
                    <div class="grid" id="elements">
                        <article class="card"
                                 data-artist="blink-182"
                                 data-genre="Поп-панк"
                                 data-decade="90-е"
                                 data-status="В наличии">
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

                        <article class="card"
                                 data-artist="Lil Peep"
                                 data-genre="Эмо"
                                 data-decade="2010-е"
                                 data-status="В наличии">
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

                        <article class="card"
                                 data-artist="Lil Peep"
                                 data-genre="Эмо"
                                 data-decade="2010-е"
                                 data-status="Осталось 1–2">
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

                        <article class="card"
                                 data-artist="blink-182"
                                 data-genre="Поп-панк"
                                 data-decade="2000-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/orig.webp" alt="blink-182 — Take Off Your Pants and Jacket">
                            </div>
                            <h2>blink-182</h2>
                            <p class="album">Take Off Your Pants and Jacket, 2001</p>
                            <p>Быстрые и смешные песни, внутри «The Rock Show» и «First Date».</p>
                            <p class="buy">
                                <strong>7 600 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="blink-182"
                                 data-genre="Поп-панк"
                                 data-decade="2010-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/hah.webp" alt="blink-182 — California">
                            </div>
                            <h2>blink-182</h2>
                            <p class="album">California, 2016</p>
                            <p>Свежий альбом с яркими мелодиями и песней «Bored to Death».</p>
                            <p class="buy">
                                <strong>7 900 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Fall Out Boy"
                                 data-genre="Поп-панк"
                                 data-decade="2000-е"
                                 data-status="Осталось 1–2">
                            <div class="cover-img">
                                <img src="images/haha.webp" alt="Fall Out Boy — From Under the Cork Tree">
                            </div>
                            <h2>Fall Out Boy</h2>
                            <p class="album">From Under the Cork Tree, 2005</p>
                            <p>Эпоха подводки и скинни-джинс: «Sugar, We're Goin Down».</p>
                            <p class="buy">
                                <strong>7 900 ₽</strong>
                                <span class="tag few">Осталось 1–2</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Fall Out Boy"
                                 data-genre="Поп-панк"
                                 data-decade="2000-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/Fall_Out_Boy_-_Infinity_On_High.jpg" alt="Fall Out Boy — Infinity on High">
                            </div>
                            <h2>Fall Out Boy</h2>
                            <p class="album">Infinity on High, 2007</p>
                            <p>Театральный и бодрый, с хитом «This Ain't a Scene, It's an Arms Race».</p>
                            <p class="buy">
                                <strong>8 100 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Fall Out Boy"
                                 data-genre="Рок"
                                 data-decade="2020-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/hahah.webp" alt="Fall Out Boy — So Much (For) Stardust">
                            </div>
                            <h2>Fall Out Boy</h2>
                            <p class="album">So Much (For) Stardust, 2023</p>
                            <p>Современный альбом группы, красивый и немного драматичный.</p>
                            <p class="buy">
                                <strong>8 800 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Green Day"
                                 data-genre="Панк-рок"
                                 data-decade="2000-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/hahaha.webp" alt="Green Day — American Idiot">
                            </div>
                            <h2>Green Day</h2>
                            <p class="album">American Idiot, 2004</p>
                            <p>Рок-опера о бунте. Двойной винил и плотный звук.</p>
                            <p class="buy">
                                <strong>8 200 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Green Day"
                                 data-genre="Панк-рок"
                                 data-decade="90-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/hahahah.webp" alt="Green Day — Dookie">
                            </div>
                            <h2>Green Day</h2>
                            <p class="album">Dookie, 1994</p>
                            <p>Альбом, с которого поп-панк стал массовым.</p>
                            <p class="buy">
                                <strong>8 000 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="My Chemical Romance"
                                 data-genre="Эмо"
                                 data-decade="2000-е"
                                 data-status="Осталось 1–2">
                            <div class="cover-img">
                                <img src="images/111.webp" alt="My Chemical Romance — The Black Parade">
                            </div>
                            <h2>My Chemical Romance</h2>
                            <p class="album">The Black Parade, 2006</p>
                            <p>Громко, театрально и красиво. Для тех, кто любит драму.</p>
                            <p class="buy">
                                <strong>9 300 ₽</strong>
                                <span class="tag few">Осталось 1–2</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="My Chemical Romance"
                                 data-genre="Эмо"
                                 data-decade="2000-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/222.webp" alt="My Chemical Romance — Three Cheers for Sweet Revenge">
                            </div>
                            <h2>My Chemical Romance</h2>
                            <p class="album">Three Cheers for Sweet Revenge, 2004</p>
                            <p>С песнями «Helena» и «I'm Not Okay». Тёмный и честный альбом.</p>
                            <p class="buy">
                                <strong>8 700 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Paramore"
                                 data-genre="Поп-панк"
                                 data-decade="2000-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/333.webp" alt="Paramore — Riot!">
                            </div>
                            <h2>Paramore</h2>
                            <p class="album">Riot!, 2007</p>
                            <p>Яркий и быстрый, внутри «Misery Business».</p>
                            <p class="buy">
                                <strong>7 600 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Paramore"
                                 data-genre="Инди"
                                 data-decade="2010-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/444.webp" alt="Paramore — After Laughter">
                            </div>
                            <h2>Paramore</h2>
                            <p class="album">After Laughter, 2017</p>
                            <p>Солнечные синты и грустные тексты. Хорошо играет вечером.</p>
                            <p class="buy">
                                <strong>8 300 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Sum 41"
                                 data-genre="Поп-панк"
                                 data-decade="2000-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/555.webp" alt="Sum 41 — All Killer No Filler">
                            </div>
                            <h2>Sum 41</h2>
                            <p class="album">All Killer No Filler, 2001</p>
                            <p>С песнями «Fat Lip» и «In Too Deep». Энергия начала 2000-х.</p>
                            <p class="buy">
                                <strong>7 300 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Neck Deep"
                                 data-genre="Поп-панк"
                                 data-decade="2010-е"
                                 data-status="Предзаказ">
                            <div class="cover-img">
                                <img src="images/777.webp" alt="Neck Deep — Life's Not Out to Get You">
                            </div>
                            <h2>Neck Deep</h2>
                            <p class="album">Life's Not Out to Get You, 2014</p>
                            <p>Современный британский поп-панк с живым звуком.</p>
                            <p class="buy">
                                <strong>7 100 ₽</strong>
                                <span class="tag pre">Предзаказ</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Machine Gun Kelly"
                                 data-genre="Поп-панк"
                                 data-decade="2020-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/666.webp" alt="Machine Gun Kelly — Tickets to My Downfall">
                            </div>
                            <h2>Machine Gun Kelly</h2>
                            <p class="album">Tickets to My Downfall, 2020</p>
                            <p>Разворот рэпера в сторону поп-панка. Быстрый и дерзкий.</p>
                            <p class="buy">
                                <strong>8 600 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Ногу Свело!"
                                 data-genre="Рок"
                                 data-decade="90-е"
                                 data-status="В наличии">
                            <div class="cover-img">
                                <img src="images/888.webp" alt="Ногу Свело! — Хару Мамбуру">
                            </div>
                            <h2>Ногу Свело!</h2>
                            <p class="album">Хару Мамбуру, 1991</p>
                            <p>Дебютный альбом группы с одноимённой песней.</p>
                            <p class="buy">
                                <strong>6 900 ₽</strong>
                                <span class="tag in">В наличии</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Кишлак"
                                 data-genre="Инди"
                                 data-decade="2020-е"
                                 data-status="Предзаказ">
                            <div class="cover-img">
                                <img src="images/kishlak_-_shik2.jpg" alt="Кишлак — Виниловое издание">
                            </div>
                            <h2>Кишлак</h2>
                            <p class="album">Виниловое издание СХИК2, 2023</p>
                            <p>Русскоязычная альтернатива с мягким, эмоциональным звуком.</p>
                            <p class="buy">
                                <strong>6 500 ₽</strong>
                                <span class="tag pre">Предзаказ</span>
                            </p>
                        </article>

                        <article class="card"
                                 data-artist="Конец солнечных дней"
                                 data-genre="Инди"
                                 data-decade="2020-е"
                                 data-status="Предзаказ">
                            <div class="cover-img">
                                <img src="images/осень.webp" alt="Конец солнечных дней — Виниловое издание">
                            </div>
                            <h2>Конец солнечных дней</h2>
                            <p class="album">Виниловое издание Осень, 2020</p>
                            <p>Атмосферный и немного грустный инди для долгих вечеров.</p>
                            <p class="buy">
                                <strong>6 400 ₽</strong>
                                <span class="tag pre">Предзаказ</span>
                            </p>
                        </article>

                        <!-- Плашка, если ничего не нашли -->
                        <article class="card card-order">
                            <h2>Не нашли свою?</h2>
                            <p>Напишите исполнителя и альбом. Мы найдём пластинку и привезём в Брянск.</p>
                            <p><a class="btn-big" href="order.php">Заказать</a></p>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/inc/footer.php'; ?>
