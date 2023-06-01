<?php

session_start();
if (!isset($_SESSION['user_id'])){
    header("Location: ../index.php?error=not-logged-in");
    die();
}

$attachment_id = $_POST['attachment_id'];
$task_id = $_POST['task_id'];

if (empty($attachment_id) || empty($task_id)) {
    header("Location: ../task.php?task-id=$task_id&error=missing-data");
    die();
}
require_once __DIR__ . '/../classes/Task.php';

$task = Task::getTaskById($task_id);
$attachment = Task::getAttachmentByID($attachment_id);
if (!$task || !$attachment) {
    header("Location: ../task.php?task-id=$task_id&error=data-mismatch");
    die();
}

if ($attachment->task_id != $task_id) {
    header("Location: ../task.php?task-id=$task_id&error=data-mismatch");
    die();
}

$task->removeAttachment($attachment_id);
header("Location: ../task.php?task-id=$task_id&success=attachment-removed");
die();