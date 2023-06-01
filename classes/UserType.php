<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Database.php';
class UserType
{
    public $id;
    public $type_name;
    public $priority;


    public static function getUserType($user_type_id) {
        $db = Database::getInstance();

        $types = $db->select('UserType', 'SELECT * FROM user_types WHERE id = :user_type_id',
        [
           ":user_type_id" => $user_type_id
        ]);

        foreach ($types as $type) {
            return $type;
        }

        return null;
    }

    public static function getAllTypes(): ?array
    {
        $db = Database::getInstance();

        return $db->select('UserType', 'SELECT * FROM user_types ORDER BY priority DESC');
    }

    public static function getByName($name) {
        $db = Database::getInstance();

        $types = $db->select('UserType', 'SELECT * FROM user_types WHERE type_name LIKE :type_name',
        [
            ":type_name" => $name
        ]);

        foreach ($types as $type) {
            return $type;
        }

        return null;
    }

    public static function addType ($name, $priority): ?string
    {
        $db = Database::getInstance();

        $db->insert('UserType', 'INSERT INTO user_types (type_name, priority) VALUES (:name, :priority)',
        [
            ":name" => $name,
            ":priority" => $priority
        ]);

        return $db->lastInsertId();
    }

    public function deleteType(): void
    {
        $db = Database::getInstance();

        $db->delete('DELETE FROM user_types WHERE id LIKE :id',
        [
           ":id" => $this->id
        ]);
    }


}