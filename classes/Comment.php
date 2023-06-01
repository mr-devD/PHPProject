<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Database.php';

class Comment
{
    public int $id;
    public string $title;
    public $description;
    public $sent_at;
    public $user_id;
    public $task_id;

    public static function addComment($title, $description, $user_id, $task_id): ?string
    {
        $db = Database::getInstance();
        $id = $db->insert('Comment', 'INSERT INTO comments (title, description, user_id, task_id) VALUES (:title, :description, :user_id, :task_id)',
            [
                ":title" => $title,
                ":description" => $description,
                ":user_id" => $user_id,
                ":task_id" => $task_id
            ]);

        if (!$id) {
            return null;
        }
        return $db->lastInsertId();
    }

    public static function getCommentsByTask($task_id): ?array
    {
        $db = Database::getInstance();
        return $db->select('Comment', 'SELECT * FROM comments WHERE task_id LIKE :task_id ORDER BY comments.sent_at DESC',
        [
            ":task_id" => $task_id
        ]);
    }

    public static function getCommentById($id) {
        $db = Database::getInstance();

        $comments = $db->select('Comment', 'SELECT * FROM comments WHERE id LIKE :id',
        [
           ":id" => $id
        ]);

        foreach ($comments as $comment) {
            return $comment;
        }
        return null;
    }

    public function deleteComment(): void
    {
        $db = Database::getInstance();
        $db->delete('DELETE FROM comments WHERE id LIKE :id',
        [
           ":id" => $this->id
        ]);
    }
}