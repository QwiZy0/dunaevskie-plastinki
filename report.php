<?php
// Отчёт варианта 9: «Объём скачанной информации за одну сессию».
// Сессия: подряд идущие запросы одного IP, пауза между которыми не больше 20 минут.
// Данные для таблицы берутся ОДНИМ SQL-запросом; страница получает их через AJAX (?data=1).
require_once __DIR__ . '/inc/bootstrap.php';
require_once __DIR__ . '/inc/logs_db.php';

if (LOGS_REQUIRE_LOGIN) {
    $user = require_login();
}

// ---------- Что можно выбирать (белые списки: в SQL попадает только это) ----------
// Группировка: сами сессии или сессии по периодам (по времени начала сессии)
$periods = [
    'hour'  => "DATE_FORMAT(start_time, '%Y-%m-%d %H:00')",
    'day'   => "DATE(start_time)",
    'week'  => "DATE_FORMAT(start_time, '%x-W%v')",
    'month' => "DATE_FORMAT(start_time, '%Y-%m')",
];

// Столбцы таблицы для каждого режима (по ним можно сортировать)
$columns = [
    'session' => ['ip' => 'IP-адрес', 'start_time' => 'Начало сессии', 'duration' => 'Длительность',
                  'hits' => 'Запросов', 'bytes' => 'Скачано'],
    'period'  => ['per' => 'Период', 'sessions' => 'Сессий', 'total_bytes' => 'Всего скачано',
                  'avg_bytes' => 'В среднем за сессию', 'max_bytes' => 'Максимум за сессию'],
];

// ---------- Запрос, который делит запросы на сессии ----------
// Слой 1: LAG берёт время предыдущего запроса того же IP.
// Слой 2: если предыдущего нет или пауза больше 20 минут, то это начало новой сессии (1).
// Слой 3: сумма единиц с начала даёт номер сессии внутри IP.
// Внешний GROUP BY собирает сессию целиком: начало, конец, число запросов, объём.
function sessions_sql(): string
{
    $gap = (int)SESSION_GAP_SECONDS;

    return "SELECT ip, MIN(log_date) AS start_time, MAX(log_date) AS end_time,
                   COUNT(*) AS hits, SUM(size) AS bytes
            FROM (
                SELECT ip, log_date, size,
                       SUM(is_new) OVER (PARTITION BY ip ORDER BY log_date, id) AS session_no
                FROM (
                    SELECT id, ip, log_date, size,
                           CASE WHEN prev_date IS NULL
                                  OR TIMESTAMPDIFF(SECOND, prev_date, log_date) > $gap
                                THEN 1 ELSE 0 END AS is_new
                    FROM (
                        SELECT id, ip, log_date, size,
                               LAG(log_date) OVER (PARTITION BY ip ORDER BY log_date, id) AS prev_date
                        FROM logs
                    ) a
                ) b
            ) c
            GROUP BY ip, session_no";
}

// ---------- AJAX: отдаём данные отчёта в формате JSON ----------
if (isset($_GET['data'])) {
    $group = (string)($_GET['group'] ?? 'session');
    $mode  = isset($periods[$group]) ? 'period' : 'session';
    $group = ($mode === 'period') ? $group : 'session';

    // Сортировка: столбец только из списка, направление только asc или desc
    $defaultSort = ($mode === 'period') ? 'total_bytes' : 'bytes';
    $sort = (string)($_GET['sort'] ?? $defaultSort);
    if (!isset($columns[$mode][$sort])) {
        $sort = $defaultSort;
    }
    $dir = (($_GET['dir'] ?? 'desc') === 'asc') ? 'ASC' : 'DESC';

    if ($mode === 'session') {
        $sql = "SELECT ip, start_time, TIMESTAMPDIFF(SECOND, start_time, end_time) AS duration, hits, bytes
                FROM (" . sessions_sql() . ") s
                ORDER BY $sort $dir
                LIMIT 200";
    } else {
        $sql = "SELECT {$periods[$group]} AS per, COUNT(*) AS sessions, SUM(bytes) AS total_bytes,
                       ROUND(AVG(bytes)) AS avg_bytes, MAX(bytes) AS max_bytes
                FROM (" . sessions_sql() . ") s
                GROUP BY per
                ORDER BY $sort $dir
                LIMIT 500";
    }

    try {
        $rows = logs_db()->query($sql)->fetch_all(MYSQLI_ASSOC);
    } catch (Throwable $e) {
        json_out(['ok' => false, 'error' => 'Ошибка запроса: ' . $e->getMessage()], 500);
    }

    $cols = [];
    foreach ($columns[$mode] as $key => $label) {
        $cols[] = ['key' => $key, 'label' => $label];
    }

    header('Cache-Control: no-store');
    json_out(['ok' => true, 'mode' => $mode, 'columns' => $cols,
              'sort' => $sort, 'dir' => strtolower($dir), 'rows' => $rows]);
}

// ---------- Обычная страница ----------
$pageTitle = 'Отчёт по сессиям — Dunaevskie Plastinki';
$current = 'report.php';
$hideFab = true;
require __DIR__ . '/inc/header.php';
?>
    <main>
        <section class="report">
            <h1 class="grad">Скачано за сессию</h1>
            <p class="lead">Сессия: запросы одного IP с паузой не больше <?= (int)(SESSION_GAP_SECONDS / 60) ?> минут.
                Нажмите на заголовок столбца, чтобы отсортировать таблицу.</p>

            <div class="report-controls">
                <label class="field">Группировка
                    <select id="group">
                        <option value="session">По сессиям</option>
                        <option value="hour">По часам</option>
                        <option value="day">По дням</option>
                        <option value="week">По неделям</option>
                        <option value="month">По месяцам</option>
                    </select>
                </label>
                <button type="button" class="btn ghost" id="btn-top">Сначала больше всего</button>
                <button type="button" class="btn ghost" id="btn-bottom">Сначала меньше всего</button>
                <a class="btn ghost" href="logs.php">Загрузка лога</a>
            </div>

            <p class="muted" id="report-status">Загрузка…</p>
            <div class="scroll">
                <table id="report-table">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>
    </main>
    <script src="report.js"></script>
<?php require __DIR__ . '/inc/footer.php'; ?>
