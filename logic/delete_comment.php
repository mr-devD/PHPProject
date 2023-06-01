<?php

require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Comment.php';


session_start();
$user = User::getUserById($_SESSION['user_id']);
$task_id = $_POST['task_id'];
$comment_id = $_POST['comment_id'];
$comment = Comment::getCommentById($comment_id);

if ($comment->user_id !== $user->id && $user->user_type_id === 2) {
    header("Location: ../task.php?task-id=$task_id&error=no-access");
    die();
}

if (empty($task_id) || empty($comment_id)) {
    header("Location: ../tasks.php?error=missing-data");
    die();
}

$comment->deleteComment();

if (isset($_POST['ajax'])) {
    echo json_encode($comment);
    die();
}
header("Location: ../task.php?task-id=$task_id&success=comment-deleted");
die();
