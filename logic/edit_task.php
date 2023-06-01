<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?error=not-logged-in");
    die();
}

require_once __DIR__ . '/../classes/User.php';
$user = User::getUserById($_SESSION['user_id']);

if ($user->user_type_id !== 1 && $user->user_type_id !== 3) {
    header("Location: ../tasks.php?error=no-access");
    die();
}

$task_id = $_POST['task_id'];
$task_subject = $_POST['task_subject'];
$task_desc = $_POST['task_desc'];
$task_priority = $_POST['task_priority'];
$task_group_id = $_POST['task_group_id'];
$task_deadline = $_POST['task_deadline'];

require_once __DIR__ . '/../classes/Task.php';
$task = Task::getTaskById($task_id);
if (!$task) {
    header("Location: ../tasks.php?error=task-not-exist");
    die();
}

require_once __DIR__ . '/../classes/TaskGroup.php';
$group = TaskGroup::getById($task_group_id);

if (!$group) {
    header("Location: ../task.php?task-id=$task_id&error=group-not-exist");
    die();
}

if ($user->user_type_id === 3 && $task->manager !== $user->id) {
    header("Location: ../task.php?task-id=$task_id&error=no-access");
    die();
}

Task::editTask($task_id, $task_subject, $task_desc, $task_priority, $task_group_id, $task_deadline);
header("Location: ../task.php?task-id=$task_id&success=task-edited");
die();