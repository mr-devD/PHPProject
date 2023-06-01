<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Database.php';
class Task
{

    public $id;
    public $subject;
    public $description;
    public $manager;
    public $deadline;
    public $priority;
    public $task_group_id;
    public $completed;
    public $canceled;


    public static function addTask($subject, $description, $manager, $deadline, $priority, $task_group_id, $executants): ?string
    {
        $db = Database::getInstance();
        $db->insert('Task', 'INSERT INTO tasks (subject, description, manager, deadline, priority, task_group_id) VALUES (:subject, :description, :manager, :deadline, :priority, :task_group_id)',
            [
                ":subject" => $subject,
                ":description" => $description,
                ":manager" => $manager,
                ":deadline" => $deadline,
                ":priority" => $priority,
                ":task_group_id" => $task_group_id
            ]);
        $task_id = $db->lastInsertId();
        foreach ($executants as $executant) {
            $db->insert('Task', 'INSERT INTO task_executants (task_id, executant_id) VALUES (:task_id, :executant_id)',
            [
               ":task_id" => $task_id,
                ":executant_id" => $executant
            ]);
        }
        return $task_id;
    }

    public static function addTaskAttachment($task_id, $file_path, $name, $file_size){
        $db = Database::getInstance();
        $db->insert('Task', 'INSERT INTO task_attachments (task_id, file_path, name, file_size) VALUES (:task_id, :full_path, :name, :file_size)',
        [
           ":task_id" =>$task_id,
            ":full_path" => $file_path,
            ":name" => $name,
            ":file_size" => $file_size
        ]);

    }

    public static function getAllTasks(): ?array
    {
        $db = Database::getInstance();
        return $db->select('Task', 'SELECT * FROM tasks');
    }

    public static function getTaskById($task_id)
    {
        $db = Database::getInstance();
        $tasks = $db->select('Task', 'SELECT * FROM tasks WHERE id LIKE :id',
        [
           ":id" => $task_id
        ]);
        foreach ($tasks as $task){
            return $task;
        }
        return null;
    }

    public static function getAttachmentsByTask($id)
    {
        $db = Database::getInstance();
        return $db->select('Task', 'SELECT * FROM task_attachments WHERE task_id LIKE :id',
        [
           ":id" => $id
        ]);
    }

    public static function getAttachmentByID($id) {
        $db = Database::getInstance();

        $attachments = $db->select('Task', 'SELECT * FROM task_attachments WHERE id LIKE :id',
        [
           ":id" => $id
        ]);
        foreach ($attachments as $attachment) {
            return $attachment;
        }
        return null;
    }

    public function removeAttachment($attachment_id) {
        $db = Database::getInstance();

        $db->delete('DELETE FROM task_attachments WHERE task_id LIKE :id AND id LIKE :attachment_id',
        [
           ":id" => $this->id,
            ":attachment_id" => $attachment_id
        ]);
    }

    public function removeExecutant($executant_id) {
        $db = Database::getInstance();
        $db->delete('DELETE FROM task_executants WHERE task_id LIKE :task_id AND executant_id LIKE :executant_id',
        [
           ":task_id" => $this->id,
            ":executant_id" => $executant_id
        ]);
    }

    public function checkExecutant($executant_id)
    {
        $db = Database::getInstance();

        $executants =  $db->select('Task', 'SELECT * FROM task_executants WHERE task_id LIKE :task_id AND executant_id LIKE :executant_id',
        [
           ":task_id" => $this->id,
           ":executant_id" => $executant_id
        ]);

        foreach ($executants as $executant) {
            return $executant;
        }
        return null;
    }

    public function getAllExecutants(): ?array
    {
        $db = Database::getInstance();

        return $db->select('Task', 'SELECT * FROM task_executants WHERE task_id LIKE :task_id',
        [
           ":task_id" => $this->id
        ]);
    }

    public function executantPartDone($executant_id): void
    {
        $db = Database::getInstance();
        $db->update('Task', 'UPDATE task_executants SET completed = 1 WHERE executant_id LIKE :executant_id AND task_executants.task_id LIKE :task_id',
        [
           ":executant_id" => $executant_id,
            ":task_id" => $this->id
        ]);
    }

    public function checkIfExecutantPartCompleted($executant_id): ?array
    {
        $db = Database::getInstance();
        return $db->select('Task', 'SELECT * FROM task_executants WHERE task_id LIKE :task_id AND executant_id LIKE :executant_id AND completed = 1',
        [
            "task_id" => $this->id,
            ":executant_id" => $executant_id
        ]);
    }

    public static function editTask($task_id, $task_subject, $task_desc, $task_priority, $task_group_id, $task_deadline): void
    {
        $db = Database::getInstance();

        $db->update('Task', 'UPDATE tasks SET subject = :task_subject, description = :task_desc, priority = :task_priority, task_group_id = :task_group_id, deadline = :task_deadline WHERE id LIKE :id',
        [
            ":id" => $task_id,
            ":task_subject" => $task_subject,
            ":task_desc" => $task_desc,
            ":task_priority" => $task_priority,
            ":task_group_id" => $task_group_id,
            ":task_deadline" => $task_deadline
        ]);
    }

    public function deleteTask(): void
    {
        $db = Database::getInstance();
        $db->delete('DELETE FROM tasks WHERE id LIKE :task_id',
        [
            ":task_id" => $this->id
        ]);
    }

    public function cancelTask(): void
    {
        $db = Database::getInstance();
        $db->update('Task', 'UPDATE tasks SET canceled = 1 WHERE id LIKE :task_id',
        [
           ":task_id" => $this->id
        ]);
    }

    public function completeTask(): void
    {
        $db = Database::getInstance();
        $db->update('Task', 'UPDATE tasks SET completed = 1 WHERE id LIKE :task_id',
        [
           ":task_id" => $this->id
        ]);
    }
}