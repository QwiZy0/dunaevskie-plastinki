<?php
// Подключение к MySQL через PDO.
// Все запросы в проекте идут через prepare() + execute() — это защита от SQL-инъекций.

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,   // настоящие подготовленные запросы
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            exit('Не удалось подключиться к базе данных. Проверьте config.php и импортируйте database.sql.');
        }
    }

    return $pdo;
}
