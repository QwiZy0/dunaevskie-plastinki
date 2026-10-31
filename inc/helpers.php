<?php
// Вспомогательные функции: защита от XSS и CSRF, проверка данных, аватары.

// ---------- Защита от XSS ----------
// Любой текст пользователя перед выводом на страницу пропускаем через e().
// Символы < > " ' & превращаются в безопасные коды, и скрипт выполниться не может.
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ---------- Защита от CSRF (подделки запроса с чужого сайта) ----------
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function check_csrf(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && is_string($_POST['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

// ---------- Работа с данными формы ----------
// Берёт строку из $_POST. Пароли не обрезаем пробелами ($trim = false).
function post(string $key, bool $trim = true): string
{
    if (!isset($_POST[$key]) || !is_string($_POST[$key])) {
        return '';
    }
    return $trim ? trim($_POST[$key]) : $_POST[$key];
}

function json_out(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Одноразовые сообщения («Данные сохранены»), которые показываются после перехода
function flash_set(string $type, string $text): void
{
    $_SESSION['flash'] = ['type' => $type, 'text' => $text];
}

function flash_get(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

// ---------- Проверка полей (возвращают текст ошибки или null) ----------
function validate_login(string $v): ?string
{
    if (!preg_match('/^[a-zA-Z0-9]{3,30}$/', $v)) {
        return 'Логин: 3–30 символов, только латинские буквы и цифры.';
    }
    return null;
}

// Правила надёжного пароля (по рекомендациям OWASP и NIST):
// длина от 10 символов, заглавные и строчные буквы, цифра, спецсимвол,
// без логина внутри, без типичных слов и без «ааа» подряд.
function validate_password(string $v, string $login = ''): ?string
{
    if (mb_strlen($v) < 10) {
        return 'Пароль: не короче 10 символов.';
    }
    if (strlen($v) > 72) {
        return 'Пароль слишком длинный (до 72 байт).';
    }

    $missing = [];
    if (!preg_match('/\p{Ll}/u', $v))    $missing[] = 'строчной буквы';
    if (!preg_match('/\p{Lu}/u', $v))    $missing[] = 'заглавной буквы';
    if (!preg_match('/\d/', $v))         $missing[] = 'цифры';
    if (!preg_match('/[^\p{L}\d]/u', $v)) $missing[] = 'спецсимвола (! ? # $ % и т.п.)';
    if ($missing) {
        return 'В пароле не хватает: ' . implode(', ', $missing) . '.';
    }

    if ($login !== '' && mb_stripos($v, $login) !== false) {
        return 'Пароль не должен содержать логин.';
    }

    // Самые частые слабые пароли и части пароля, которые подбирают первыми
    $weak = ['password', 'passw0rd', 'qwerty', 'asdfgh', 'zxcvbn', '123456', '654321', '111111',
             '000000', 'admin', 'letmein', 'welcome', 'iloveyou', 'monkey', 'dragon',
             'dunaevskie', 'plastinki', 'пароль', 'йцукен', 'привет'];
    foreach ($weak as $word) {
        if (mb_stripos($v, $word) !== false) {
            return 'Слишком простой пароль: не используйте типичные слова и последовательности.';
        }
    }

    if (preg_match('/(.)\1{2,}/su', $v)) {
        return 'Не повторяйте один и тот же символ три раза подряд.';
    }
    return null;
}

function validate_fullname(string $v): ?string
{
    $len = mb_strlen($v);
    if ($len < 2 || $len > 100 || !preg_match('/^[\p{L}][\p{L}\s\.\-\']*$/u', $v)) {
        return 'ФИО: только буквы, пробелы и дефис (2–100 символов).';
    }
    return null;
}

function validate_email(string $v): ?string
{
    if (strlen($v) > 100 || !filter_var($v, FILTER_VALIDATE_EMAIL)) {
        return 'Введите корректную почту.';
    }
    return null;
}

// ---------- Аватар ----------
// Возвращает [имя_файла|null, текст_ошибки|null].
// Файл не выбран — это не ошибка (аватар необязателен).
function save_avatar(array $file): array
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [null, null];
    }
    if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        return [null, 'Не удалось загрузить аватар.'];
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return [null, 'Аватар должен быть не больше 2 МБ.'];
    }

    // Тип определяем по содержимому файла, а не по имени, которое можно подделать
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];

    if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
        return [null, 'Аватар: только картинки JPG, PNG, WEBP или GIF.'];
    }

    if (!is_dir(AVATAR_DIR)) {
        mkdir(AVATAR_DIR, 0755, true);
    }

    // Своё случайное имя: имя файла от пользователя не используем вообще
    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], AVATAR_DIR . $name)) {
        return [null, 'Не удалось сохранить аватар.'];
    }
    return [$name, null];
}

function delete_avatar(?string $name): void
{
    if ($name && preg_match('/^[a-f0-9]{32}\.(jpg|png|webp|gif)$/', $name) && is_file(AVATAR_DIR . $name)) {
        unlink(AVATAR_DIR . $name);
    }
}

// Картинка-аватар или круг с первой буквой имени
function avatar_html(array $user, string $class = ''): string
{
    if (!empty($user['user_avatar'])) {
        return '<img class="avatar ' . e($class) . '" src="' . e(AVATAR_URL . $user['user_avatar']) . '" alt="">';
    }
    $letter = mb_strtoupper(mb_substr($user['user_fullname'], 0, 1));
    return '<span class="avatar avatar-empty ' . e($class) . '">' . e($letter) . '</span>';
}

// ---------- Адреса ----------
function is_https(): bool
{
    return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
}

// Адрес папки сайта, например http://localhost/dunaevskie-plastinki/
function base_url(): string
{
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    if (!preg_match('/^[a-z0-9.\-:\[\]]+$/i', $host)) {
        $host = 'localhost';
    }

    $root = rtrim(str_replace('\\', '/', (string)realpath($_SERVER['DOCUMENT_ROOT'] ?? '')), '/');
    $dir  = rtrim(str_replace('\\', '/', ROOT_DIR), '/');
    $path = ($root !== '' && strncasecmp($dir, $root, strlen($root)) === 0) ? substr($dir, strlen($root)) : '';

    return (is_https() ? 'https' : 'http') . '://' . $host . $path . '/';
}

// ---------- Меню сайта (одинаковое в шапке и в подвале) ----------
// $current — файл открытой страницы, его пункт подсвечивается
function render_menu(string $current): string
{
    $items = [
        'index.php'    => 'Главная',
        'catalog.php'  => 'Каталог',
        'order.php'    => 'Под заказ',
        'price.php'    => 'Прайс-лист',
        'about.php'    => 'О магазине',
        'contacts.php' => 'Контакты',
    ];

    $html = '';
    foreach ($items as $file => $title) {
        $class = ($file === 'order.php') ? ' class="hot"' : '';
        $aria  = ($file === $current) ? ' aria-current="page"' : '';
        $html .= '                <li' . $class . '><a href="' . $file . '"' . $aria . '>' . $title . '</a></li>' . "\n";
    }
    return $html;
}

// ---------- Поле пароля с кнопкой «Показать» и (по желанию) шкалой надёжности ----------
function password_field(string $label, string $name, string $autocomplete, bool $meter = false): string
{
    $html  = '<label class="field">' . e($label) . "\n";
    $html .= '    <span class="pw-wrap"><input type="password" name="' . e($name) . '" required maxlength="72"'
           . ' autocomplete="' . e($autocomplete) . '"' . ($meter ? ' data-strength' : '') . '>'
           . '<button type="button" class="pw-toggle" aria-label="Показать пароль">Показать</button></span>' . "\n";
    $html .= "</label>\n";

    if ($meter) {
        $html .= '<div class="strength" aria-live="polite">
    <div class="strength-bar"><i></i></div>
    <p class="strength-text">Надёжность пароля</p>
    <ul class="rules">
        <li data-rule="len">не короче 10 символов</li>
        <li data-rule="lower">строчная буква</li>
        <li data-rule="upper">заглавная буква</li>
        <li data-rule="digit">цифра</li>
        <li data-rule="special">спецсимвол (! ? # $ %)</li>
    </ul>
</div>' . "\n";
    }
    return $html;
}
