<?php
session_start();
require 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные и сразу защищаем от SQL-инъекций
    $login = mysqli_real_escape_string($link, trim($_POST['login']));
    $fio = mysqli_real_escape_string($link, trim($_POST['fio']));
    $email = mysqli_real_escape_string($link, trim($_POST['email']));
    $password = md5(md5(trim($_POST['password']))); // Двойной хэш, как в методичке

    // Проверка на пустые поля и длину логина
    if (empty($login) || empty($password) || empty($fio) || empty($email)) {
        $error = "Заполните все поля!";
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $login)) {
        $error = "Логин может содержать только латинские буквы и цифры.";
    } else {
        // Проверяем, нет ли уже такого логина
        $check = mysqli_query($link, "SELECT id FROM users WHERE login = '$login'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Пользователь с таким логином уже существует.";
        } else {
            // Добавляем в базу
            $query = "INSERT INTO users (login, password, fio, email) 
                    VALUES ('$login', '$password', '$fio', '$email')";
            
            if (mysqli_query($link, $query)) {
                $success = "Вы успешно зарегистрированы! Теперь можете войти.";
            } else {
                $error = "Ошибка при регистрации: " . mysqli_error($link);
            }
        }
    }
}

// Возвращаемся на главную с сообщением
$_SESSION['auth_message'] = $success ? $success : $error;
$_SESSION['auth_type'] = $success ? 'success' : 'error';
header("Location: index.php");
exit;
?>