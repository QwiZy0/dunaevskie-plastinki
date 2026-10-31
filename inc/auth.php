<?php
// Авторизация через cookie (как в методичке): в cookie лежат id пользователя и hash.
// В БД хранится только SHA-256 от этого hash. Если базу украдут, подделать cookie по ней нельзя.

function cookie_options(int $expires): array
{
    return [
        'expires'  => $expires,
        'path'     => '/',
        'secure'   => is_https(),
        'httponly' => true,     // JavaScript не видит cookie, поэтому XSS не украдёт её
        'samesite' => 'Lax',    // cookie не отправляется с чужих сайтов
    ];
}

function clear_auth_cookies(): void
{
    setcookie('id', '', cookie_options(time() - 3600));
    setcookie('hash', '', cookie_options(time() - 3600));
}

// Кто сейчас на сайте: массив с данными пользователя или null
function current_user(): ?array
{
    static $user = false;       // false = ещё не проверяли
    if ($user !== false) {
        return $user;
    }
    $user = null;

    $id   = $_COOKIE['id'] ?? null;
    $hash = $_COOKIE['hash'] ?? null;

    if (is_string($id) && is_string($hash) && ctype_digit($id) && preg_match('/^[a-f0-9]{64}$/', $hash)) {
        $stmt = db()->prepare('SELECT * FROM users WHERE user_id = ? LIMIT 1');
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch();

        if ($row && $row['user_hash'] && hash_equals($row['user_hash'], hash('sha256', $hash))) {
            $user = $row;
        } else {
            clear_auth_cookies();   // cookie устарела или подделана
        }
    }

    return $user;
}

// Вход: создаём случайный hash, кладём его хэш в БД, а сам hash в cookie на AUTH_DAYS дней
function login_user(int $userId): void
{
    $hash = bin2hex(random_bytes(32));

    $stmt = db()->prepare('UPDATE users SET user_hash = ? WHERE user_id = ?');
    $stmt->execute([hash('sha256', $hash), $userId]);

    $options = cookie_options(time() + AUTH_DAYS * 86400);
    setcookie('id', (string)$userId, $options);
    setcookie('hash', $hash, $options);

    session_regenerate_id(true);
}

// Выход: стираем hash в БД и удаляем cookie
function logout_user(): void
{
    $user = current_user();
    if ($user) {
        $stmt = db()->prepare('UPDATE users SET user_hash = NULL WHERE user_id = ?');
        $stmt->execute([$user['user_id']]);
    }
    clear_auth_cookies();
}

// Закрытый раздел: гостей отправляем на главную и открываем окно входа
function require_login(): array
{
    $user = current_user();
    if (!$user) {
        header('Location: index.php?login=1');
        exit;
    }
    return $user;
}
