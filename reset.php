<?php
// Страница, на которую ведёт ссылка из «Забыли пароль?»: здесь задаём новый пароль.
require_once __DIR__ . '/inc/bootstrap.php';

$token = (isset($_REQUEST['token']) && is_string($_REQUEST['token'])) ? $_REQUEST['token'] : '';
$valid = null;      // данные пользователя, если ссылка рабочая
$error = null;
$done  = false;

// Ищем в БД хэш токена (сам токен в базе не хранится), ссылка не должна быть просрочена
if (preg_match('/^[a-f0-9]{64}$/', $token)) {
    $stmt = db()->prepare(
        'SELECT r.user_id, u.user_login
         FROM password_resets r JOIN users u ON u.user_id = r.user_id
         WHERE r.token_hash = ? AND r.expires_at > NOW() LIMIT 1'
    );
    $stmt->execute([hash('sha256', $token)]);
    $valid = $stmt->fetch() ?: null;
}

if ($valid && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $password  = post('password', false);
    $password2 = post('password2', false);

    if (!check_csrf()) {
        $error = 'Ошибка запроса. Обновите страницу и попробуйте снова.';
    } elseif ($problem = validate_password($password, $valid['user_login'])) {
        $error = $problem;
    } elseif ($password !== $password2) {
        $error = 'Пароли не совпадают.';
    } else {
        // Новый пароль сохраняем, ссылку удаляем (она одноразовая), везде разлогиниваем
        db()->prepare('UPDATE users SET user_password = ?, user_hash = NULL WHERE user_id = ?')
            ->execute([password_hash($password, PASSWORD_DEFAULT), $valid['user_id']]);
        db()->prepare('DELETE FROM password_resets WHERE user_id = ?')->execute([$valid['user_id']]);
        $done = true;
    }
}

$pageTitle = 'Новый пароль — Dunaevskie Plastinki';
$current = 'reset.php';
$hideFab = true;
require __DIR__ . '/inc/header.php';
?>
    <main>
        <section class="narrow">
            <h1 class="grad">Новый пароль</h1>

<?php if ($done): ?>
            <p class="notice ok">Пароль изменён. Теперь можно войти с новым паролем.</p>
            <p><a class="btn" href="index.php?login=1">Войти</a></p>

<?php elseif (!$valid): ?>
            <p class="notice err">Ссылка недействительна или устарела. Запросите новую в окне входа.</p>
            <p><a class="btn" href="index.php?login=forgot">Забыли пароль?</a></p>

<?php else: ?>
            <p class="lead">Придумайте новый пароль для логина <strong><?= e($valid['user_login']) ?></strong>.</p>
<?php if ($error): ?>
            <p class="notice err"><?= e($error) ?></p>
<?php endif; ?>
            <form class="stack-form" method="post" action="reset.php">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= e($token) ?>">
                <?= password_field('Новый пароль', 'password', 'new-password', true) ?>
                <?= password_field('Повторите пароль', 'password2', 'new-password') ?>
                <button class="btn-big" type="submit">Сохранить пароль</button>
            </form>
<?php endif; ?>
        </section>
    </main>
<?php require __DIR__ . '/inc/footer.php'; ?>
