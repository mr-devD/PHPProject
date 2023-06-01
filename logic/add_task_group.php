<?php



$group_name = $_POST['taskGroupName'];
$group_desc = $_POST['taskGroupDescription'];

if (empty($group_name) || empty($group_desc)) {
    header("Location: ../task-groups.php?error=data-not-inserted");
    die();
}

require_once __DIR__ . "/../classes/TaskGroup.php";

$success = TaskGroup::addGroup($group_name, $group_desc);

if (!$success) {
    header("Location: ../task-groups.php?error=unable-to-insert");
    die();
}

header("Location: ../task-groups.php?success=group-inserted");
die();