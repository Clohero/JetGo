<?php
session_start();
require "../../config/connect-db.php";

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

$values = [$email, $password];
foreach ($values as $value) {
    if ($value == '') {
        header("Location: ../../public/login.php?error=Заполните все поля");
        exit;
    }
}


$result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

if (mysqli_num_rows($result) == 0) {
    header("Location: ../../public/login.php?error=Пользователь не найден");
    exit;
}

$user = mysqli_fetch_assoc($result);

if ($password != $user['password']) {
    header("Location: ../../public/login.php?error=Неверный пароль");
    exit;
}

$_SESSION['user'] = $user['email'];
$_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['full_name'];
$_SESSION['role'] = $user['role'];

header("Location: ../../public/dashboard.php");
exit;
