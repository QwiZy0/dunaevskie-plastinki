<?php
// Шапка сайта. Страница перед подключением задаёт:
//   $pageTitle — заголовок вкладки, $current — файл страницы (для подсветки меню)
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="Магазин виниловых пластинок в Брянске. Поп-панк, эмо, рок и инди. Достанем любую пластинку под заказ.">
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="alternate icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@400;600&family=Unbounded:wght@500;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css" id="main-style">
    <!-- jQuery (как в методичке), скрипт сайта и скрипт окна входа -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <script src="auth.js"></script>
</head>
<body>
    <header class="header">
        <a class="logo" href="index.php">
            <img class="logo-disc" src="favicon.svg" alt="Логотип" width="36" height="36">
            <span>Dunaevskie<br>Plastinki</span>
        </a>
        <nav aria-label="Главное меню">
            <ul class="menu">
<?= render_menu($current) ?>
            </ul>
        </nav>

        <!-- Вход и регистрация (для гостя) или личный кабинет (для вошедшего) -->
        <div class="auth">
<?php if ($user): ?>
            <a class="auth-user" href="cabinet.php" title="Личный кабинет">
                <?= avatar_html($user, 'avatar-sm') ?>
                <span><?= e($user['user_fullname']) ?></span>
            </a>
            <form action="actions/logout.php" method="post">
                <?= csrf_field() ?>
                <button type="submit" class="btn-small">Выйти</button>
            </form>
<?php else: ?>
            <button type="button" class="btn-small" data-modal="login">Войти</button>
            <button type="button" class="btn-small btn-fill" data-modal="register">Регистрация</button>
<?php endif; ?>
        </div>

        <!-- Кнопка переключения на версию для печати -->
        <button type="button" class="print-btn" id="print-btn">Версия для печати</button>
        <!-- Переключатель темки (чистый CSS) -->
        <div class="toggle" title="Сменить тему">
            <input type="checkbox" id="theme" aria-label="Светлая тема">
            <label for="theme"></label>
        </div>
    </header>

