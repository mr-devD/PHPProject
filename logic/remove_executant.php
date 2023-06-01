<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?error=not-logged-in");
    die();
}

$executant_id = $_POST['executant_id'];
$task_id = $_POST['task_id'];

if (empty($executant_id) || empty($task_id)) {
    header("Location: ../task.php?task-id=$task_id&error=missing-data");
    die();
}

require_once __DIR__ . '/../classes/Task.php';
require_once __DIR__ . '/../classes/User.php';
$task = Task::getTaskById($task_id);
$user = User::getUserById($executant_id);


if (!$user || !$task) {
    header("Location: ../task.php?task-id=$task_id&error=invalid-data");
    die();
}

if (!$task->checkExecutant($executant_id)) {
    header("Location: ../task.php?task-id=$task_id&error=data-mismatch");
    die();
}

$task->removeExecutant($executant_id);
header("Location: ../task.php?task-id=$task_id&success=executant-removed");
die();