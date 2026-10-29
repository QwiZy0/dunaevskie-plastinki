<?php
// =====================================================
// config.php — настройки сайта. Поменяйте под свой сервер.
// =====================================================

// Подключение к MySQL (в XAMPP по умолчанию root без пароля)
const DB_HOST = 'localhost';
const DB_NAME = 'dunaevskie_lab4';
const DB_USER = 'root';
const DB_PASS = '';

// Куда сохраняются аватары (папка и адрес для браузера)
const AVATAR_DIR = __DIR__ . '/uploads/avatars/';
const AVATAR_URL = 'uploads/avatars/';

// Сколько дней пользователь остаётся авторизованным, если не нажал «Выйти»
const AUTH_DAYS = 30;

// Сколько минут действует ссылка для восстановления пароля
const RESET_MINUTES = 60;
