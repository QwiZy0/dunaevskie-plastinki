<?php
// Регистрация. Ответ в формате JSON (его принимает окно регистрации).
require_once __DIR__ . '/../inc/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf()) {
    json_out(['ok' => false, 'error' => 'Ошибка запроса. Обновите страницу и попробуйте снова.'], 400);
}

$login     = post('login');
$password  = post('password', false);
$password2 = post('password2', false);
$fullname  = post('fullname');
$email     = mb_strtolower(post('email'));

// 1. Проверяем поля
$errors = array_filter([
    validate_login($login),
    validate_password($password, $login),
    validate_fullname($fullname),
    validate_email($email),
]);
if ($password !== $password2) {
    $errors[] = 'Пароли не совпадают.';
}

// 2. Логин и почта не должны быть заняты
if (!$errors) {
    $stmt = db()->prepare('SELECT user_login, user_email FROM users WHERE user_login = ? OR user_email = ?');
    $stmt->execute([$login, $email]);
    foreach ($stmt->fetchAll() as $row) {
        if (strcasecmp($row['user_login'], $login) === 0) {
            $errors[] = 'Такой логин уже занят.';
        }
        if (strcasecmp($row['user_email'], $email) === 0) {
            $errors[] = 'Эта почта уже зарегистрирована.';
        }
    }
}

// 3. Аватар сохраняем, только когда остальное в порядке
$avatar = null;
if (!$errors) {
    list($avatar, $avatarError) = save_avatar($_FILES['avatar'] ?? []);
    if ($avatarError) {
        $errors[] = $avatarError;
    }
}

if ($errors) {
    json_out(['ok' => false, 'error' => implode("\n", array_unique($errors))]);
}

// 4. Записываем пользователя в БД (подготовленный запрос)
try {
    $stmt = db()->prepare(
        'INSERT INTO users (user_login, user_password, user_fullname, user_email, user_avatar)
         VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$login, password_hash($password, PASSWORD_DEFAULT), $fullname, $email, $avatar]);
} catch (PDOException $e) {
    delete_avatar($avatar);
    json_out(['ok' => false, 'error' => 'Логин или почта уже заняты.']);
}

// 5. Сразу входим на сайт
login_user((int)db()->lastInsertId());
json_out(['ok' => true, 'redirect' => 'cabinet.php']);
