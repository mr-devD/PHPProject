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

$type_id = $_POST['type_id'];

require_once __DIR__ . '/../classes/UserType.php';

$type = UserType::getUserType($type_id);

if (!$type) {
    header("Location: ../user_types.php?error=type-not-exist");
    die();
}


$type->deleteType();

header("Location: ../user_types.php?success=type-deleted");
die();