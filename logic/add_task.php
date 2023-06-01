<?php



$taskName = $_POST['taskName'];
$taskDesc = $_POST['taskDescription'];
$taskDeadline = $_POST['taskDeadline'];
$taskPriority = $_POST['taskPriority'];
$taskGroup = $_POST['taskGroup'];
$taskAttachments = $_FILES['taskAttachments[]'];
$taskManager = $_POST['taskManager'];
$executants = $_POST['taskExecutants'];


if (empty($taskName) || empty($taskDesc) || empty($taskDeadline) || empty($taskPriority) || empty($taskGroup) || empty($taskManager)) {
    header("Location: ../tasks.php?error=missing-data");
    die();
}

require_once __DIR__ . '/../classes/Task.php';
require_once __DIR__ .'/../includes/Upload.php';



if (!is_dir('../task_attachments')) {
    mkdir('../task_attachments', 0777, true);
}


$id = Task::addTask($taskName, $taskDesc, $taskManager, $taskDeadline, $taskPriority, $taskGroup, $executants);
if (!empty($taskAttachments['name'])){
    $upload = new Upload('../task_attachments');
    foreach ($taskAttachments['name'] as $index => $name){
        $attachment = array(
            'name' => $name,
            'type' => $taskAttachments['type'][$index],
            'full_path' => $taskAttachments['full_path'][$index],
            'tmp_name' => $taskAttachments['tmp_name'][$index],
            'error' => $taskAttachments['error'][$index],
            'size' => $taskAttachments['size'][$index]
        );
        $upload->file($attachment);
        $upload->set_allowed_mime_types(['.pdf', 'image/jpeg', 'image/gif', 'image/png']);
        $upload->set_max_file_size(2);
        $attachment_name = $taskName . "_" . $attachment['name'];
        $upload->set_filename($attachment_name);
        $upload->save();
        Task::addTaskAttachment($id, 'task_attachments/' . $attachment_name, $attachment_name, $attachment['size']);
    }
}


header("Location: ../tasks.php?success=task-added");
die();