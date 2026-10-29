<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = mysqli_real_escape_string($link, trim($_POST['login']));
    $password = md5(md5(trim($_POST['password'])));

    $query = "SELECT id, login, fio FROM users WHERE login='$login' AND password='$password' LIMIT 1";
    $result = mysqli_query($link, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        // Записываем в сессию, что пользователь вошел
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['user_fio'] = $user['fio'];
        
        // Перенаправляем в личный кабинет
        header("Location: profile.php");
        exit;
    } else {
        $_SESSION['auth_message'] = "Неверный логин или пароль!";
        $_SESSION['auth_type'] = 'error';
        header("Location: index.php");
        exit;
    }
}
?>