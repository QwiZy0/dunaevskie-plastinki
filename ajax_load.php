<?php
// Серверная часть загрузчика: каждый AJAX-запрос читает порцию строк лог-файла
// и записывает её в таблицу logs одним INSERT. Ответ в формате JSON.
require_once __DIR__ . '/inc/bootstrap.php';
require_once __DIR__ . '/inc/logs_db.php';

set_time_limit(120);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !check_csrf()) {
    json_out(['ok' => false, 'error' => 'Ошибка запроса. Обновите страницу.'], 400);
}
if (LOGS_REQUIRE_LOGIN && !$user) {
    json_out(['ok' => false, 'error' => 'Войдите на сайт, чтобы загружать лог.'], 403);
}

// ---------- Разбор одной строки лога ----------
// Пример: ::1 - - [29/Oct/2026:08:26:41 +0300] "GET /dunaevskie/ HTTP/1.1" 200 12345
// Возвращает [ip, дата, страница, код, размер] или null, если строка не подошла.
function parse_line(string $line): ?array
{
    static $months = ['Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05', 'Jun' => '06',
                      'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'];

    if (!preg_match('/^(\S+) \S+ \S+ \[([^\]]+)\] "\S+ (\S+)[^"]*" (\d{3}) (\d+|-)/', $line, $m)) {
        return null;
    }

    // Дату разбираем вручную по позициям: это быстрее, чем DateTime, а строк очень много
    // 29/Oct/2026:08:26:41 → 2026-10-29 08:26:41
    $month = $months[substr($m[2], 3, 3)] ?? null;
    if ($month === null) {
        return null;
    }
    $date = substr($m[2], 7, 4) . '-' . $month . '-' . substr($m[2], 0, 2) . ' ' . substr($m[2], 12, 8);

    // Адрес без параметров после «?», обрезаем до размера столбца
    $page = $m[3];
    $pos = strpos($page, '?');
    if ($pos !== false) {
        $page = substr($page, 0, $pos);
    }
    $page = mb_convert_encoding(substr($page, 0, 255), 'UTF-8', 'UTF-8');

    return [substr($m[1], 0, 45), $date, $page, (int)$m[4], $m[5] === '-' ? 0 : (int)$m[5]];
}

// ---------- Массовая вставка: все строки порции одним запросом ----------
function insert_rows(mysqli $db, array $rows): void
{
    if (!$rows) {
        return;
    }

    // INSERT INTO logs (...) VALUES (?,?,?,?,?), (?,?,?,?,?), ... — по одной скобке на строку
    $sql = 'INSERT INTO logs (ip, log_date, page, status_code, size) VALUES '
         . implode(',', array_fill(0, count($rows), '(?,?,?,?,?)'));

    $params = [];
    foreach ($rows as $row) {
        array_push($params, $row[0], $row[1], $row[2], $row[3], $row[4]);
    }

    $stmt = $db->prepare($sql);
    $stmt->bind_param(str_repeat('sssii', count($rows)), ...$params);
    $stmt->execute();
    $stmt->close();
}

try {
    $action = $_POST['action'] ?? 'load';

    // ---------- Очистка таблицы перед новой загрузкой ----------
    if ($action === 'reset') {
        logs_db()->query('TRUNCATE TABLE logs');
        json_out(['ok' => true]);
    }

    // ---------- Сколько строк в таблице ----------
    if ($action === 'count') {
        json_out(['ok' => true, 'count' => (int)logs_db()->query('SELECT COUNT(*) FROM logs')->fetch_row()[0]]);
    }

    // ---------- Загрузка очередной порции ----------
    if (!is_readable(LOG_FILE)) {
        json_out(['ok' => false, 'error' => 'Лог-файл не найден или недоступен: ' . LOG_FILE]);
    }

    clearstatcache();
    $size   = filesize(LOG_FILE);
    $offset = max(0, (int)($_POST['offset'] ?? 0));   // с какого байта читать

    $rows = [];
    $lines = 0;
    $skipped = 0;
    $newOffset = $offset;

    if ($offset < $size) {
        $file = fopen(LOG_FILE, 'rb');
        fseek($file, $offset);

        while ($lines < LOG_CHUNK_LINES && ($line = fgets($file)) !== false) {
            $lines++;
            $row = parse_line($line);
            if ($row) {
                $rows[] = $row;
            } else {
                $skipped++;
            }
        }

        $newOffset = ftell($file);    // следующий запрос начнёт отсюда
        fclose($file);
    }

    insert_rows(logs_db(), $rows);

    json_out([
        'ok'      => true,
        'offset'  => $newOffset,
        'size'    => $size,
        'percent' => $size > 0 ? round($newOffset / $size * 100, 1) : 100,
        'rows'    => count($rows),
        'skipped' => $skipped,
        'done'    => $newOffset >= $size,
    ]);
} catch (Throwable $e) {
    json_out(['ok' => false, 'error' => 'Ошибка: ' . $e->getMessage()], 500);
}
