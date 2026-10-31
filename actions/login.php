<?php
// Вход на сайт. Ответ в формате JSON.
require_once __DIR__ . '/../inc/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf()) {
    json_out(['ok' => false, 'error' => 'Ошибка запроса. Обновите страницу и попробуйте снова.'], 400);
}

$login    = post('login');
$password = post('password', false);

// Ищем пользователя по логину (подготовленный запрос, подставить SQL нельзя)
$stmt = db()->prepare('SELECT user_id, user_password FROM users WHERE user_login = ? LIMIT 1');
$stmt->execute([$login]);
$row = $stmt->fetch();

if ($row && password_verify($password, $row['user_password'])) {
    login_user((int)$row['user_id']);
    json_out(['ok' => true, 'redirect' => 'cabinet.php']);
}

// Пауза замедляет перебор паролей. Причину не уточняем: логин или пароль.
usleep(500000);
json_out(['ok' => false, 'error' => 'Неверный логин или пароль.']);
