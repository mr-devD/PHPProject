<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?error=not-logged-in");
    die();
}
require_once __DIR__ . '/../classes/User.php';
$user = User::getUserById($_SESSION['user_id']);
$profile_id = $_POST['profile_id'];

if ($user->user_type_id !== 1) {
    header("Location: ../profile.php?error=no-access");
    die();
}

if (empty($profile_id)) {
    header("Location: ../profile.php?error=missing-data");
    die();
}

$profile = User::getUserById($profile_id);

if (!$profile) {
    header("Location: ../profile.php?error=user-not-exist");
    die();
}

$profile->deleteUser();
header("Location: ../users.php?success=user-deleted");
die();