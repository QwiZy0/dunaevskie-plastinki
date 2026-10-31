<?php
// Подключение к MySQL через mysqli для лабораторной №5.
// Функция названа logs_db(), чтобы не пересекаться с db() (PDO) из лабораторной №4
// и с переменной $link из старого файла db.php.

function logs_db(): mysqli
{
    static $mysqli = null;

    if ($mysqli === null) {
        // Ошибки mysqli превращаем в исключения, их ловят страницы и показывают сообщение
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, LOGS_DB_NAME);
        $mysqli->set_charset('utf8mb4');
    }

    return $mysqli;
}
