<?php
require_once __DIR__ .'/../classes/User.php';

session_start();
$user = User::getUserById($_SESSION['user_id']);
$user_id = $_POST['user_id'];

if ($user->user_type_id !== 1) {
    header("Location: ../tasks.php?error=no-access");
    die();
}


if ($user->id != $user_id) {
    header("Location: ../user_types.php?error=data-mismatch");
    die();
}

$userTypeName = $_POST['userTypeName'];
$userTypePriority = $_POST['userTypePriority'];

require_once __DIR__ . '/../classes/UserType.php';

$type_exists = UserType::getByName($userTypeName);

if ($type_exists) {
    header("Location: ../user_types.php?error=type-exists");
    die();
}

if (!UserType::addType($userTypeName, $userTypePriority)) {
    header("Location: ../user_types.php?error=type-not-added");
    die();
}

header("Location: ../user_types.php?success=type-added");
die();