<?php
require_once __DIR__ . '/inc/bootstrap.php';

$pageTitle = 'Прайс-лист · Dunaevskie Plastinki';
$current = 'price.php';
require __DIR__ . '/inc/header.php';
?>
    <main>
        <section>
            <h1 class="grad">Прайс-лист</h1>
            <p class="lead">Цены в рублях. Цвет ячейки в последнем столбце показывает наличие. Нужной позиции нет? <a href="order.php">Закажите её</a>.</p>
            <div class="scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Исполнитель</th>
                            <th>Альбом</th>
                            <th>Жанр</th>
                            <th>Формат</th>
                            <th>Цена</th>
                            <th>Наличие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lil Peep</td>
                            <td>Come Over When You're Sober, Pt. 1</td>
                            <td>Эмо-рэп</td>
                            <td>LP, 12″</td>
                            <td class="price">8 900 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Lil Peep</td>
                            <td>Come Over When You're Sober, Pt. 2</td>
                            <td>Эмо-рэп</td>
                            <td>LP, 12″</td>
                            <td class="price">9 500 ₽</td>
                            <td class="few">Осталось 1–2</td>
                        </tr>
                        <tr>
                            <td>blink-182</td>
                            <td>Enema of the State</td>
                            <td>Поп-панк</td>
                            <td>LP, 12″</td>
                            <td class="price">7 400 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>blink-182</td>
                            <td>Take Off Your Pants and Jacket</td>
                            <td>Поп-панк</td>
                            <td>LP, 12″</td>
                            <td class="price">7 600 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>blink-182</td>
                            <td>California</td>
                            <td>Поп-панк</td>
                            <td>LP, 12″</td>
                            <td class="price">7 900 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Fall Out Boy</td>
                            <td>From Under the Cork Tree</td>
                            <td>Поп-панк</td>
                            <td>LP, 12″</td>
                            <td class="price">7 900 ₽</td>
                            <td class="few">Осталось 1–2</td>
                        </tr>
                        <tr>
                            <td>Fall Out Boy</td>
                            <td>Infinity on High</td>
                            <td>Поп-панк</td>
                            <td>LP, 12″</td>
                            <td class="price">8 100 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Fall Out Boy</td>
                            <td>So Much (For) Stardust</td>
                            <td>Поп-рок</td>
                            <td>LP, 12″</td>
                            <td class="price">8 800 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Green Day</td>
                            <td>American Idiot</td>
                            <td>Панк-рок</td>
                            <td>LP, 12″</td>
                            <td class="price">8 200 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Green Day</td>
                            <td>Dookie</td>
                            <td>Панк-рок</td>
                            <td>LP, 12″</td>
                            <td class="price">8 000 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>My Chemical Romance</td>
                            <td>The Black Parade</td>
                            <td>Эмо / рок</td>
                            <td>LP, 12″</td>
                            <td class="price">9 300 ₽</td>
                            <td class="few">Осталось 1–2</td>
                        </tr>
                        <tr>
                            <td>My Chemical Romance</td>
                            <td>Three Cheers for Sweet Revenge</td>
                            <td>Эмо / рок</td>
                            <td>LP, 12″</td>
                            <td class="price">8 700 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Paramore</td>
                            <td>Riot!</td>
                            <td>Поп-панк</td>
                            <td>LP, 12″</td>
                            <td class="price">7 600 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Paramore</td>
                            <td>After Laughter</td>
                            <td>Синти-поп</td>
                            <td>LP, 12″</td>
                            <td class="price">8 300 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Sum 41</td>
                            <td>All Killer No Filler</td>
                            <td>Поп-панк</td>
                            <td>LP, 12″</td>
                            <td class="price">7 300 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Neck Deep</td>
                            <td>Life's Not Out to Get You</td>
                            <td>Поп-панк</td>
                            <td>LP, 12″</td>
                            <td class="price">7 100 ₽</td>
                            <td class="pre">Предзаказ</td>
                        </tr>
                        <tr>
                            <td>Machine Gun Kelly</td>
                            <td>Tickets to My Downfall</td>
                            <td>Поп-панк</td>
                            <td>LP, 12″</td>
                            <td class="price">8 600 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Ногу Свело!</td>
                            <td>Хару Мамбуру</td>
                            <td>Русский рок</td>
                            <td>LP, 12″</td>
                            <td class="price">6 900 ₽</td>
                            <td class="in">В наличии</td>
                        </tr>
                        <tr>
                            <td>Кишлак</td>
                            <td>Виниловое издание (уточнить релиз)</td>
                            <td>Русская альтернатива</td>
                            <td>LP, 12″</td>
                            <td class="price">6 500 ₽</td>
                            <td class="pre">Предзаказ</td>
                        </tr>
                        <tr>
                            <td>Конец солнечных дней</td>
                            <td>Виниловое издание (уточнить релиз)</td>
                            <td>Русский инди</td>
                            <td>LP, 12″</td>
                            <td class="price">6 400 ₽</td>
                            <td class="pre">Предзаказ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="legend">
                <span class="tag in">В наличии</span>
                <span class="tag few">Мало на складе</span>
                <span class="tag pre">Предзаказ</span>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/inc/footer.php'; ?>
