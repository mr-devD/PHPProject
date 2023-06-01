<?php

require_once __DIR__ . '/../classes/User.php';



if (empty($_POST['email']) || empty($_POST['full_name'])){
    header("Location ../index.php?error=no-access");
    die();
}

$email = $_POST['email'];
$full_name =$_POST['full_name'];
$user = User::getUserByEmail($email);
if (!$user){
    header("Location: ../index.php?error=user-not-exist");
    die();
}

$activation_code = User::generateActivationCode();
$user->generateNewActivationCode($activation_code);

User::send_link($email, $full_name, $activation_code);

header("Location: ../index.php?success=new-link-sent");
die();

