<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Database.php';
class TaskGroup
{
    public $id;
    public $group_name;
    public $group_description;

    public static function getAllGroups(): array
    {
        $db = Database::getInstance();

        return $db->select('TaskGroup', 'SELECT * FROM task_groups');

    }

    public static function addGroup($group_name, $group_desc): ?string
    {
        $db = Database::getInstance();

        $db->insert("TaskGroup", 'INSERT INTO task_groups (group_name, group_description) VALUES (:group_name, :group_desc)',
        [
            ":group_name" => $group_name,
            ":group_desc" => $group_desc
        ]);
        return $db->lastInsertId();
    }

    public function delete(): void
    {
        $db = Database::getInstance();

        $db->delete('DELETE FROM task_groups WHERE task_groups . id = :id',
        [
            ":id" => $this->id
        ]);
    }

    public static function getById($id) {
        $db = Database::getInstance();

        $groups = $db->select('TaskGroup', 'SELECT * FROM task_groups WHERE id = :id',
        [
            ":id" => $id
        ]);

        foreach ($groups as $group) {
            return $group;
        }
        return null;
    }

    public function editGroup($group_name, $group_desc)
    {
        $db = Database::getInstance();

        $db->update('TaskGroup', 'UPDATE task_groups SET group_name = :group_name, group_description = :group_desc WHERE task_groups . id = :id',
            [
                ":group_name" => $group_name,
                ":group_desc" => $group_desc,
                ":id" => $this->id
            ]
        );


    }



}