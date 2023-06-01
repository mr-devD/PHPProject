<?php
session_start();
if (!isset($_POST['group_id'])){
    header("Location: ../task-groups.php?error=no-data");
    die();
}

$id = $_POST['group_id'];

if (empty($id)){
    header("Location: ../task-groups.php?error=id-empty");
    die();
}
require_once __DIR__ . '/../classes/User.php';
$user = User::getUserById($_SESSION['user_id']);

if ($user->user_type_id === 2) {
    header("Location: ../task-groups.php?error=no-access");
    die();
}

require_once __DIR__ . '/../classes/TaskGroup.php';

$group = TaskGroup::getById($id);
$group->delete();
if (isset($_POST['ajax'])) {
    echo json_encode($group);
    die();
}
header("Location: ../task-groups.php?success=group-deleted");
die();