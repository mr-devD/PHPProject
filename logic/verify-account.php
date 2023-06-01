<?php
require_once __DIR__ . '/../classes/User.php';


if (!isset($_GET['token'])){
    header("Location: ../index.php");
    die();
}

$token = $_GET['token'];
$token_parts = User::verify_token($token);

if (!$token_parts){
    header("Location: ../index.php?error=invalid-token");
    die();
}

$email = $token_parts[0];
$code = $token_parts[1];
$hash = $token_parts[2];

$user = User::getUserByEmail($email);

if (!$user) {
    header("Location: ../index.php?error=user-not-exist");
    die();
}

if (!User::isCodeValid($code)){
    header("Location: ../index.php?error=code-expired");
    die();
}

if ($user->activated){
    header("Location: ../index.php?error=user-already-activated");
    die();
}

if (!$user->activation_code == $code){
    header("Location: ../index.php?error=invalid-code");
    die();
}

$user->activateUser();
header("Location: ../index.php?success=user-verified");
die();

