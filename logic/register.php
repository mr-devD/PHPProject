<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../classes/User.php';




if (!isset($_POST['email'])) {
    header("Location: ../index.php?error=no-permission");
    die();
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$repeat_password = $_POST['repeat_password'];
$full_name = $_POST['full_name'];
$phone = $_POST['phone'];
$birthdate = $_POST['birthdate'];
$activation_code = User::generateActivationCode();


if (empty($username) || empty($email) || empty($password) || empty($repeat_password) || empty($full_name)) {
    header("Location: ../registration.php?error=missing-data");
    die();
}

if ($password !== $repeat_password) {
    header("Location: ../registration.php?error=password-mismatch");
    die();
}

if (!User::register($username, $email, $full_name, $password, $phone, $birthdate, $activation_code)) {
    header("Location: ../registration.php?error=user-exists");
    die();
}


User::send_link($email, $full_name, $activation_code);


header("Location: ../index.php?success=register-success");
die();