-- База данных для лабораторной №4 (MySQL)
-- Импорт: phpMyAdmin → вкладка «Импорт» → выбрать этот файл

CREATE DATABASE IF NOT EXISTS dunaevskie_lab4
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dunaevskie_lab4;

-- Пользователи
CREATE TABLE IF NOT EXISTS users (
  user_id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_login    VARCHAR(30)  NOT NULL,            -- логин (после регистрации не меняется)
  user_password VARCHAR(255) NOT NULL,            -- хэш пароля (password_hash)
  user_fullname VARCHAR(100) NOT NULL,            -- ФИО
  user_email    VARCHAR(100) NOT NULL,            -- почта
  user_avatar   VARCHAR(64)  DEFAULT NULL,        -- имя файла аватара
  user_hash     CHAR(64)     DEFAULT NULL,        -- хэш cookie авторизации
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  UNIQUE KEY uq_login (user_login),
  UNIQUE KEY uq_email (user_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ссылки для восстановления пароля
CREATE TABLE IF NOT EXISTS password_resets (
  reset_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    INT UNSIGNED NOT NULL,
  token_hash CHAR(64)     NOT NULL,               -- хэш токена из ссылки
  expires_at DATETIME     NOT NULL,               -- до какого времени ссылка работает
  PRIMARY KEY (reset_id),
  KEY idx_token (token_hash),
  CONSTRAINT fk_reset_user FOREIGN KEY (user_id)
    REFERENCES users (user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
