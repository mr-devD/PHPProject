<?php
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Task.php';

session_start();
$user = User::getUserById($_SESSION['user_id']);
$task_id = $_POST['task_id'];
$task = Task::getTaskById($task_id);

if (empty($task_id) || empty($user) || empty($task)) {
    header("Location: ../tasks.php?error=missing-data");
    die();
}


if ($user->user_type_id !== 1 && $user->user_type_id !== 3) {
    header("Location: ../task.php?task-id=$task_id&error=no-access");
    die();
}

if ($task->manager !== $user->id && $user->user_type_id !== 1) {
    header("Location: ../task.php?task-id=$task_id&error=no-access");
    die();
}

$task->cancelTask();

header("Location: ../task.php?task-id=$task_id&success=task-cancaled");
die();