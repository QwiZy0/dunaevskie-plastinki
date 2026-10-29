<?php
// Подключается в самом начале каждой страницы.

define('ROOT_DIR', dirname(__DIR__));

require_once ROOT_DIR . '/config.php';
require_once ROOT_DIR . '/inc/db.php';
require_once ROOT_DIR . '/inc/helpers.php';
require_once ROOT_DIR . '/inc/auth.php';

// Сессия нужна для защиты форм (CSRF) и коротких сообщений
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => is_https()]);
session_start();

// Кто зашёл на сайт (null, если гость). Cookie проверяются до вывода страницы.
$user = current_user();
