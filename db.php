<?php
// Настройки подключения к базе данных XAMPP
$host = 'localhost';
$user = 'root';      // В XAMPP по умолчанию root
$pass = '';          // Пароля нет (пустая строка)
$db_name = 'dunaevskie_db'; // Имя базы, которую ты создал в phpMyAdmin

// Подключаемся
$link = mysqli_connect($host, $user, $pass, $db_name);

// Если подключение не удалось — останавливаем сайт и пишем ошибку
if (!$link) {
    die('Ошибка подключения к базе: ' . mysqli_connect_error());
}

// Устанавливаем кодировку UTF-8 (чтобы русские буквы не ломались)
mysqli_set_charset($link, 'utf8mb4');
?>