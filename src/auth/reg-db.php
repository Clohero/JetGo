<?php
require "../../config/connect-db.php";

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$full_name = trim($_POST['full_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');

$values = [$email, $password, $full_name, $phone];
foreach ($values as $value) {
    if ($value == '') {
        header("Location: ../../public/reg.php?error=Заполните все поля");
        exit;
    }
}



if (mb_strlen($password) < 5) {
    header("Location: ../../public/reg.php?error=Пароль слишком короткий");
    exit;
}

$check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");

if (mysqli_num_rows($check) > 0) {
    header("Location: ../../public/reg.php?error=Email уже зарегистрирован");
    exit;
}

$query = mysqli_query($conn, "INSERT INTO users (email, password, full_name, phone) VALUES ('$email', '$password', '$full_name', '$phone')");

if ($query) {
    session_start();
    $_SESSION['user'] = $email;
    $_SESSION['user_id'] = mysqli_insert_id($conn);
    $_SESSION['name'] = $full_name;
    $_SESSION['role'] = 'user';
    header("Location: ../../public/dashboard.php");
} else {
    header("Location: ../../public/reg.php?error=Ошибка при регистрации");
}
exit;
