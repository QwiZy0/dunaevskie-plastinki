-- Лабораторная №5: таблица для лога (если её ещё нет) и индекс для отчёта.
-- Импорт: phpMyAdmin → база dunaevskie_db → вкладка «Импорт».

USE dunaevskie_db;

CREATE TABLE IF NOT EXISTS logs (
  id          INT          NOT NULL AUTO_INCREMENT,
  ip          VARCHAR(45)  NOT NULL,      -- IP-адрес посетителя
  log_date    DATETIME     NOT NULL,      -- время запроса
  page        VARCHAR(255) NOT NULL,      -- запрошенный адрес
  status_code INT          NOT NULL,      -- код ответа (200, 404...)
  size        INT          NOT NULL DEFAULT 0,   -- сколько байт отдал сервер
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Индекс ускоряет отчёт: запросы одного IP идут подряд по времени.
-- Если такой индекс уже есть, MySQL ответит «Duplicate key name», это не страшно.
ALTER TABLE logs ADD INDEX idx_ip_date (ip, log_date);
