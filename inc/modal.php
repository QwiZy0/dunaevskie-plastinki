<!-- Всплывающее окно: вход, регистрация, восстановление пароля.
     Открывается кнопками с data-modal, формы отправляются через AJAX (auth.js). -->
<div class="modal" id="auth-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="modal-box">
        <button type="button" class="modal-close" aria-label="Закрыть">&times;</button>

        <!-- Заголовок: текст меняется в зависимости от вкладки -->
        <div class="modal-head">
            <span class="modal-badge"><img src="favicon.svg" alt="" width="46" height="46"></span>
            <h2 id="modal-title">С возвращением</h2>
            <p id="modal-sub">Войдите, чтобы заказывать пластинки</p>
        </div>

        <div class="modal-tabs">
            <button type="button" data-tab="login">Вход</button>
            <button type="button" data-tab="register">Регистрация</button>
        </div>

        <!-- Вход -->
        <form class="modal-form" id="form-login" action="actions/login.php" method="post">
            <?= csrf_field() ?>
            <label class="field">Логин
                <input type="text" name="login" required maxlength="30" autocomplete="username">
            </label>
            <?= password_field('Пароль', 'password', 'current-password') ?>
            <button class="btn-big" type="submit">Войти</button>
            <p class="modal-links"><a href="#" data-tab-link="forgot">Забыли пароль?</a></p>
        </form>

        <!-- Регистрация -->
        <form class="modal-form" id="form-register" action="actions/register.php" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <label class="field">Логин
                <input type="text" name="login" required minlength="3" maxlength="30" pattern="[A-Za-z0-9]+" autocomplete="username">
                <small>Латинские буквы и цифры, от 3 до 30 символов</small>
            </label>
            <label class="field">ФИО
                <input type="text" name="fullname" required minlength="2" maxlength="100" autocomplete="name">
            </label>
            <label class="field">Почта
                <input type="email" name="email" required maxlength="100" autocomplete="email">
            </label>
            <?= password_field('Пароль', 'password', 'new-password', true) ?>
            <?= password_field('Повторите пароль', 'password2', 'new-password') ?>
            <label class="field">Аватар
                <input type="file" name="avatar" accept="image/png,image/jpeg,image/webp,image/gif">
                <small>Необязательно. JPG, PNG, WEBP или GIF до 2 МБ</small>
            </label>
            <button class="btn-big" type="submit">Зарегистрироваться</button>
        </form>

        <!-- Восстановление пароля -->
        <form class="modal-form" id="form-forgot" action="actions/forgot.php" method="post">
            <?= csrf_field() ?>
            <label class="field">Почта, указанная при регистрации
                <input type="email" name="email" required maxlength="100" autocomplete="email">
            </label>
            <button class="btn-big" type="submit">Получить ссылку</button>
            <p class="modal-links"><a href="#" data-tab-link="login">Назад ко входу</a></p>
        </form>

        <!-- Сюда попадают сообщения об ошибках и ссылка для смены пароля -->
        <p class="form-msg" id="form-msg" role="alert"></p>
    </div>
</div>
