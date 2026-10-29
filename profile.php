<?php
session_start();
require 'db.php';

// ЗАЩИТА: Если сессии нет (пользователь не входил), выкидываем на главную
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Достаем данные пользователя из базы
$user_id = $_SESSION['user_id'];
// (В следующем шаге мы добавим сюда защиту от SQL-инъекций, пока просто для теста)
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($link, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет — Dunaevskie Plastinki</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <a class="logo" href="index.php">Dunaevskie Plastinki</a>
        <nav><ul class="menu"><li><a href="index.php">На главную</a></li></ul></nav>
    </header>

    <main>
        <section style="max-width: 600px; margin: 50px auto; background: var(--surface); padding: 30px; border-radius: 15px; border: 1px solid var(--line);">
            <h1 class="grad">Личный кабинет</h1>
            
            <p><strong>ФИО:</strong> <?php echo htmlspecialchars($user['fio']); ?></p>
            <p><strong>Логин:</strong> <?php echo htmlspecialchars($user['login']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            
            <p style="margin-top: 30px;">
                <a href="logout.php" class="btn-big" style="background: #555; color: white;">Выйти</a>
            </p>
        </section>
    </main>
</body>
</html>