<?php

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    header("Location: ../index.php?error=no-data");
    die();
}

$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header("Location: ../index.php?error=credentials-empty");
    die();
}

require_once __DIR__ . '/../classes/User.php';
$user = User::login($email, $password);

if (!$user){
    header("Location: ../index.php?error=wrong-credentials");
    die();
}

if (!$user->activated) {
    header("Location: ../user-not-verified.php?email=" . $user->email . "&full_name=" . $user->full_name);
    die();
}

ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.gc_maxlifetime', 1800);

session_start();
$_SESSION['user_id'] = $user->id;
header("Location: ../loggedin.php?success=login-successful");
die();

