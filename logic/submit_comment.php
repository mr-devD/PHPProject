<?php

$title = $_POST['commentTitle'];
$description = $_POST['commentDesc'];
$user_id = $_POST['user_id'];
$task_id = $_POST['task_id'];


session_start();
if (!isset($_SESSION['user_id'])){
    header("Location: ../index.php?error=not-logged-in");
    die();
}

if (empty($title) || empty($description) || empty($user_id) || empty($task_id)) {
    header("Location: ../task.php?error=missing-data");
    die();
}

require_once __DIR__ . '/../classes/Comment.php';
require_once __DIR__ . '/../classes/User.php';

$id = Comment::addComment($title, $description, $user_id, $task_id);

$comment = Comment::getCommentById($id);


header('Content-Type: application/json');
echo json_encode($comment);
die();
