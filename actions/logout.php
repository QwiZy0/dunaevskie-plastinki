<?php
// Выход: удаляем cookie и возвращаем на главную.
require_once __DIR__ . '/../inc/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_csrf()) {
    logout_user();
}

header('Location: ../index.php');
exit;
