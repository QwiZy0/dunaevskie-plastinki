# Лабораторная №4 — регистрация и авторизация (PHP + MySQL)

## Запуск на своём компьютере (XAMPP, порт 8080)

1. Папку сайта положите в `C:\xampp\htdocs\dunaevskie-plastinki`.
2. Порт 8080: в XAMPP нажмите Apache → Config → `httpd.conf`, найдите `Listen 80`, замените на `Listen 8080`,
   а `ServerName localhost:80` на `ServerName localhost:8080`. Перезапустите Apache.
3. Откройте `http://localhost:8080/phpmyadmin`, вкладка «Импорт», выберите `database.sql`.
   Создастся база `dunaevskie_lab4` с таблицами `users` и `password_resets`.
4. Если у MySQL есть пароль или другой пользователь, поправьте их в `config.php`.
5. Откройте сайт: `http://localhost:8080/dunaevskie-plastinki/`.

## Структура

| Файл | Что делает |
|------|-----------|
| `config.php` | настройки БД, срок cookie, срок ссылки восстановления |
| `database.sql` | создание БД и таблиц |
| `inc/bootstrap.php` | подключается на каждой странице: сессия, проверка cookie |
| `inc/db.php` | подключение к MySQL (PDO) |
| `inc/auth.php` | cookie `id` + `hash`, вход, выход, закрытый раздел |
| `inc/helpers.php` | защита от XSS и CSRF, проверка полей, аватар, правила пароля |
| `inc/header.php`, `inc/footer.php`, `inc/modal.php` | общие части страниц и всплывающее окно |
| `actions/` | `register.php`, `login.php`, `logout.php`, `forgot.php` (ответ JSON для окна) |
| `reset.php` | страница задания нового пароля по ссылке |
| `cabinet.php` | личный кабинет (закрытый раздел) |
| `auth.js` | окно входа на jQuery: вкладки, AJAX, шкала надёжности пароля |
| `uploads/avatars/` | сюда сохраняются аватары (скрипты там запрещены `.htaccess`) |

Важно: GitHub Pages PHP не выполняет. Для проверки лабы запускайте сайт локально
или на хостинге с PHP и MySQL.
