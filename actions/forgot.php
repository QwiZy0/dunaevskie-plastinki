<?php
// «Забыли пароль?»: создаём ссылку для смены пароля и показываем её на экране.
// В настоящем магазине вместо вывода ссылку отправляли бы письмом через mail().
require_once __DIR__ . '/../inc/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf()) {
    json_out(['ok' => false, 'error' => 'Ошибка запроса. Обновите страницу и попробуйте снова.'], 400);
}

$email = mb_strtolower(post('email'));
if ($error = validate_email($email)) {
    json_out(['ok' => false, 'error' => $error]);
}

$stmt = db()->prepare('SELECT user_id FROM users WHERE user_email = ? LIMIT 1');
$stmt->execute([$email]);
$row = $stmt->fetch();

if (!$row) {
    json_out(['ok' => false, 'error' => 'Пользователь с такой почтой не найден.']);
}

// Старые ссылки этого пользователя удаляем, делаем новую одноразовую
$userId = (int)$row['user_id'];
$token  = bin2hex(random_bytes(32));

db()->prepare('DELETE FROM password_resets WHERE user_id = ?')->execute([$userId]);
db()->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at)
               VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ' . (int)RESET_MINUTES . ' MINUTE))')
    ->execute([$userId, hash('sha256', $token)]);

json_out([
    'ok'      => true,
    'message' => 'Ссылка для смены пароля (действует ' . RESET_MINUTES . ' мин.):',
    'link'    => base_url() . 'reset.php?token=' . $token,
]);
