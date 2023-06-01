<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?error=not-logged-in");
    die();
}

$task_id = $_POST['task_id'];
$executant_id = $_POST['executant_id'];

if (empty($task_id) || empty($executant_id)) {
    header("Location: ../task.php?task-id=$task_id&error=missing-data");
    die();
}

require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Task.php';

$user = User::getUserById($executant_id);
$task = Task::getTaskById($task_id);

if (!$task) {
    header("Location: ../task.php?task-id=$task_id&error=task-not-exist");
    die();
}

if (empty($task->checkExecutant($user->id))) {
    header("Location: ../task.php?task-id=$task_id&error=no-access");
    die();
}

$task->executantPartDone($executant_id);
header("Location: ../task.php?task-id=$task_id&success=successfully-completed");
die();