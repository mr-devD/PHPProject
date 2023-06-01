<?php

session_start();
if (!isset($_SESSION['user_id'])){
    header("Location: ../index.php?error=not-logged-in");
}



require_once __DIR__ . '/../classes/User.php';
$user = User::getUserById($_SESSION['user_id']);

if ($user->user_type_id != 1) {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $birthdate = $_POST['birthdate'];

    if (empty($username) || empty($birthdate) || empty($phone) || empty($full_name)) {
        header("Location: ../profile.php?id=$user->id&error=missing-data");
        die();
    }

    if ((User::getByPhone($phone) && User::getByPhone($phone)->id != $user->id) || (User::getByUsername($username) && User::getByUsername($username)->id != $user->id)) {
        header("Location: ../profile.php?id=$user->id&error=username-or-phone-exists");
        die();
    }


    User::editProfile($user->id, $full_name, $username, $phone, $birthdate);
    header("Location: ../profile.php?id=$user->id&success=profile-updated");
    die();
}

$username = $_POST['username'];
$full_name = $_POST['full_name'];
$phone = $_POST['phone'];
$birthdate = $_POST['birthdate'];
$email = $_POST['email'];
$user_type_id = $_POST['usertype'];
$edit_user_id = $_POST['edit_user_id'];


if (empty($username) || empty($birthdate) || empty($phone) || empty($full_name) || empty($email) || empty($user_type_id)) {
    header("Location: ../profile.php?id=$edit_user_id&error=missing-data");
    die();
}



if ((User::getByPhone($phone) && User::getByPhone($phone)->id != $edit_user_id) || (User::getByUsername($username) && User::getByUsername($username)->id != $edit_user_id)) {
    header("Location: ../profile.php?id=$edit_user_id&error=username-or-phone-exists");
    die();
}

User::editProfile($edit_user_id, $full_name, $username, $phone, $birthdate, $email, $user_type_id);
header("Location: ../profile.php?id=$edit_user_id&success=profile-updated");
die();
