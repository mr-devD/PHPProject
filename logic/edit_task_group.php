<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?error=not-logged-in");
    die();
}

require_once __DIR__ . '/../classes/User.php';
$user = User::getUserById($_SESSION['user_id']);

if ($user->user_type_id !== 1 && $user->user_type_id !== 3) {
    header("Location: ../task-groups.php?error=no-access");
    die();
}

if (!isset($_POST['group_id'])){
    header("Location: ../task-groups.php?error=no-data");
    die();
}

$id = $_POST['group_id'];
$group_name = $_POST['group_name'];
$group_desc = $_POST['group_desc'];

if (empty($id) || empty($group_name) || empty($group_desc)){
    header("Location: ../task-groups.php?error=missing-data");
    die();
}

require_once __DIR__ . '/../classes/TaskGroup.php';

$task_group = TaskGroup::getById($id);

if (!$task_group) {
    header("Location: ../task-groups.php?error=group-not-exist");
    die();
}

$task_group->editGroup($group_name, $group_desc);
$task_group = TaskGroup::getById($id);

json_encode($task_group);
header("Location: ../task-groups.php?success=group-edited");
die();