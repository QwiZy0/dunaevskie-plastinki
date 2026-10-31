<?php
// Страница загрузчика: кнопки, прогресс-бар и статистика. Работу делает logs.js.
require_once __DIR__ . '/inc/bootstrap.php';
require_once __DIR__ . '/inc/logs_db.php';

if (LOGS_REQUIRE_LOGIN) {
    $user = require_login();
}

$fileOk   = is_readable(LOG_FILE);
$fileSize = $fileOk ? filesize(LOG_FILE) : 0;

$rowsInDb = 0;
$dbError  = null;
try {
    $rowsInDb = (int)logs_db()->query('SELECT COUNT(*) FROM logs')->fetch_row()[0];
} catch (Throwable $e) {
    $dbError = $e->getMessage();
}

$pageTitle = 'Загрузка лога — Dunaevskie Plastinki';
$current = 'logs.php';
$hideFab = true;
require __DIR__ . '/inc/header.php';
?>
    <main>
        <section class="loader" id="loader" data-csrf="<?= e(csrf_token()) ?>" data-size="<?= (int)$fileSize ?>">
            <h1 class="grad">Загрузка лога сервера</h1>
            <p class="lead">Лог-файл читается порциями, и каждая порция записывается в базу одним запросом.</p>

<?php if ($dbError): ?>
            <p class="notice err">Нет связи с базой <?= e(LOGS_DB_NAME) ?>: <?= e($dbError) ?></p>
<?php endif; ?>
<?php if (!$fileOk): ?>
            <p class="notice err">Лог-файл не найден: <?= e(LOG_FILE) ?>. Проверьте путь в config.php.</p>
<?php endif; ?>

            <div class="box">
                <p><strong>Файл:</strong> <code><?= e(LOG_FILE) ?></code></p>
                <p><strong>Размер:</strong> <span id="file-size"></span></p>
                <p><strong>Строк в базе сейчас:</strong> <span id="rows-in-db"><?= $rowsInDb ?></span></p>
            </div>

            <p class="loader-buttons">
                <button type="button" class="btn-big" id="btn-start">Загрузить лог</button>
                <button type="button" class="btn ghost" id="btn-stop" disabled>Остановить</button>
                <button type="button" class="btn ghost" id="btn-clear">Очистить таблицу</button>
                <a class="btn ghost" href="report.php">Открыть отчёт</a>
            </p>

            <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="progress-bar" id="bar"><span id="percent">0%</span></div>
            </div>
            <p class="loader-stats" id="stats">Нажмите «Загрузить лог».</p>
            <p class="notice err" id="loader-error" style="display: none;"></p>
        </section>
    </main>
    <script src="logs.js"></script>
<?php require __DIR__ . '/inc/footer.php'; ?>
