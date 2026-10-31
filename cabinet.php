<?php
// Личный кабинет: закрытый раздел, доступен только после входа.
require_once __DIR__ . '/inc/bootstrap.php';

// Гостя сразу отправляем на главную (с открытым окном входа), даже если он ввёл адрес вручную
$user = require_login();

$posted = '';
$errors = [];
$values = ['fullname' => $user['user_fullname'], 'email' => $user['user_email']];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posted = post('form');

    if (!check_csrf()) {
        $errors[] = 'Ошибка запроса. Обновите страницу и попробуйте снова.';

    } elseif ($posted === 'profile') {
        // ---------- Смена ФИО, почты и аватара (логин менять нельзя) ----------
        $values['fullname'] = post('fullname');
        $values['email']    = mb_strtolower(post('email'));

        $errors = array_filter([validate_fullname($values['fullname']), validate_email($values['email'])]);

        if (!$errors) {
            $stmt = db()->prepare('SELECT user_id FROM users WHERE user_email = ? AND user_id <> ?');
            $stmt->execute([$values['email'], $user['user_id']]);
            if ($stmt->fetch()) {
                $errors[] = 'Эта почта уже используется другим пользователем.';
            }
        }

        $newAvatar = null;
        if (!$errors) {
            list($newAvatar, $avatarError) = save_avatar($_FILES['avatar'] ?? []);
            if ($avatarError) {
                $errors[] = $avatarError;
            }
        }

        if (!$errors) {
            $stmt = db()->prepare('UPDATE users SET user_fullname = ?, user_email = ?, user_avatar = ? WHERE user_id = ?');
            $stmt->execute([$values['fullname'], $values['email'], $newAvatar ?? $user['user_avatar'], $user['user_id']]);
            if ($newAvatar) {
                delete_avatar($user['user_avatar']);    // старую картинку удаляем
            }
            flash_set('ok', 'Данные сохранены.');
            header('Location: cabinet.php');
            exit;
        }

    } elseif ($posted === 'password') {
        // ---------- Смена пароля ----------
        $oldPassword = post('current', false);
        $new     = post('password', false);
        $new2    = post('password2', false);

        if (!password_verify($oldPassword, $user['user_password'])) {
            $errors[] = 'Текущий пароль указан неверно.';
        }
        if ($problem = validate_password($new, $user['user_login'])) {
            $errors[] = $problem;
        }
        if ($new !== $new2) {
            $errors[] = 'Новые пароли не совпадают.';
        }

        if (!$errors) {
            db()->prepare('UPDATE users SET user_password = ? WHERE user_id = ?')
                ->execute([password_hash($new, PASSWORD_DEFAULT), $user['user_id']]);
            flash_set('ok', 'Пароль изменён.');
            header('Location: cabinet.php');
            exit;
        }
    }
}

$notice = flash_get();
$pageTitle = 'Личный кабинет — Dunaevskie Plastinki';
$current = 'cabinet.php';
$hideFab = true;
require __DIR__ . '/inc/header.php';
?>
    <main>
        <section class="cabinet">

            <!-- Левая колонка: аватар, имя и меню кабинета -->
            <aside class="cab-side">
                <?= avatar_html($user, 'avatar-lg') ?>
                <h2><?= e($user['user_fullname']) ?></h2>
                <p class="cab-login">@<?= e($user['user_login']) ?></p>
                <nav aria-label="Меню кабинета">
                    <a href="#profile">Профиль</a>
                    <a href="#security">Безопасность</a>
                    <a href="order.php">Заказать пластинку</a>
                    <form action="actions/logout.php" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="cab-logout">Выйти</button>
                    </form>
                </nav>
            </aside>

            <div class="cab-main">
                <h1 class="grad">Личный кабинет</h1>

<?php if ($notice): ?>
                <p class="notice <?= $notice['type'] === 'ok' ? 'ok' : 'err' ?>"><?= e($notice['text']) ?></p>
<?php endif; ?>
<?php if ($errors && $posted !== 'profile' && $posted !== 'password'): ?>
                <p class="notice err"><?= e(implode("\n", $errors)) ?></p>
<?php endif; ?>

                <!-- Данные профиля -->
                <div class="box" id="profile">
                    <h2>Мои данные</h2>
                    <p class="muted">Вы зарегистрировались <?= e(date('d.m.Y', strtotime($user['created_at']))) ?></p>
<?php if ($errors && $posted === 'profile'): ?>
                    <p class="notice err"><?= e(implode("\n", $errors)) ?></p>
<?php endif; ?>
                    <form class="stack-form" method="post" action="cabinet.php" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="form" value="profile">
                        <label class="field">Логин
                            <input type="text" value="<?= e($user['user_login']) ?>" disabled>
                            <small>Логин изменить нельзя</small>
                        </label>
                        <label class="field">ФИО
                            <input type="text" name="fullname" required minlength="2" maxlength="100" value="<?= e($values['fullname']) ?>">
                        </label>
                        <label class="field">Почта
                            <input type="email" name="email" required maxlength="100" value="<?= e($values['email']) ?>">
                        </label>
                        <label class="field">Новый аватар
                            <input type="file" name="avatar" accept="image/png,image/jpeg,image/webp,image/gif">
                            <small>Оставьте пустым, чтобы не менять. JPG, PNG, WEBP или GIF до 2 МБ</small>
                        </label>
                        <button class="btn-big" type="submit">Сохранить</button>
                    </form>
                </div>

                <!-- Смена пароля -->
                <div class="box" id="security">
                    <h2>Смена пароля</h2>
<?php if ($errors && $posted === 'password'): ?>
                    <p class="notice err"><?= e(implode("\n", $errors)) ?></p>
<?php endif; ?>
                    <form class="stack-form" method="post" action="cabinet.php#security">
                        <?= csrf_field() ?>
                        <input type="hidden" name="form" value="password">
                        <?= password_field('Текущий пароль', 'current', 'current-password') ?>
                        <?= password_field('Новый пароль', 'password', 'new-password', true) ?>
                        <?= password_field('Повторите новый пароль', 'password2', 'new-password') ?>
                        <button class="btn-big" type="submit">Изменить пароль</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
<?php require __DIR__ . '/inc/footer.php'; ?>
