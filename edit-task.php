<?php
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/includes/session_check.php';

$user = User::getUserById($_SESSION['user_id']);

if (empty($_GET['task-id'])) {
    header("Location: tasks.php?error=missing-data");
    die();
}

if ($user->user_type_id !== 1 && $user->user_type_id !== 3) {
    header("Location: tasks.php?error=no-access");
    die();
}

require_once __DIR__ . '/classes/Task.php';

$task = Task::getTaskById($_GET['task-id']);

if (!$task) {
    header("Location: tasks.php?error=task-not-exist");
    die();
}

if ($user->user_type_id === 3 && $task->manager !== $user->id) {
    header("Location: tasks.php?error=no-access");
    die();
}


require_once __DIR__ . '/classes/TaskGroup.php';
$groups = TaskGroup::getAllGroups();



?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Projekat1</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
</head>
<body id="page-top">
<div id="wrapper">
    <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
        <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
                <div class="sidebar-brand-text mx-3"><span>Brand</span></div>
            </a>
            <hr class="sidebar-divider my-0">
            <ul class="navbar-nav text-light" id="accordionSidebar">
                <li class="nav-item"><a class="nav-link" href="profile.php?id=<?=$user->id?>"><i
                            class="fas fa-user"></i><span>Profil</span></a></li>
                <?php if (($user->user_type_id) === 3 || ($user->user_type_id === 1)) { ?>
                    <li class="nav-item"><a class="nav-link" href="task-groups.php"><i class="fas fa-tasks"></i><span>Grupe Zadataka</span></a>
                    </li>
                <?php } ?>
                <li class="nav-item"><a class="nav-link active" href="tasks.php"><i
                            class="fas fa-tasks"></i><span>Zadaci</span></a></li>
                <?php if ($user->user_type_id === 1) { ?>
                    <li class="nav-item"><a class="nav-link" href="users.php"><i class="fas fa-table"></i><span>Korisnici</span></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="user_types.php"><i class="fas fa-table"></i><span>Tipovi Korisnika</span></a>
                    </li>
                <?php } ?>

            </ul>
            <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
        </div>
    </nav>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                    <ul class="navbar-nav flex-nowrap ms-auto">
                        <div class="d-none d-sm-block topbar-divider"></div>
                        <li class="nav-item dropdown no-arrow">
                            <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small"><?=$user->full_name?></span></a>
                                <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a
                                        class="dropdown-item" href="profile.php?id=$<?= $user->id ?>"><i
                                            class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profil</a><a
                                        class="dropdown-item" href="change-password.php"><i
                                            class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Promena lozinke</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logic/logout.php"><i
                                            class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Odjava</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container-fluid">
                <h3 class="text-dark mb-5">Izmena zadatka - <?=$task->subject?></h3>
                <div class="row mb-2 d-flex align-items-center justify-content-center">
                    <div class="col-8">
                        <div class="card shadow">
                            <div class="card-header">
                                <div class="card-title">
                                    <h4>Izmena zadatka</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="logic/edit_task.php" method="post">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <label for="task_subject" class="form-label">Naslov Zadatka:</label>
                                                <input type="text" class="form-control" name="task_subject"
                                                       id="task_subject" value="<?= $task->subject ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <label for="task_desc" class="form-label">Opis Zadatka:</label>
                                                <input type="text" class="form-control" name="task_desc" id="task_desc" value="<?=$task->description?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <label for="task_priority" class="form-label">Prioritet zadatka:</label>
                                                <select name="task_priority" id="task_priority" class="form-control">
                                                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                                                        <option value="<?=$i?>" <?php if ($task->priority === $i) {echo "selected";} ?>><?= $i?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <label for="task_group_id" class="form-label">Grupa zadatka kojoj pripada:</label>
                                                <select name="task_group_id" id="task_group_id" class="form-control">
                                                    <?php foreach ($groups as $group)  { ?>
                                                        <option value="<?=$group->id?>" <?php if ($task->task_group_id === $group->id) {echo "selected";} ?>><?=$group->group_name?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <label for="task_deadline" class="form-label">Rok izvrsenja zadatka:</label>
                                                <input type="date" class="form-control" name="task_deadline" id="task_deadline" min="<?= date('Y-m-d') ?>" value="<?=$task->deadline?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <label for="task_manager" class="form-label">Rukovodioc zadatka:</label>
                                                <input type="text" class="form-control shadow-none border-0"
                                                       name="task_manager" id="task_manager"
                                                       value="<?= User::getUserById($task->manager)->full_name ?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="mb-2 text-center">
                                                <input type="hidden" name="task_id"
                                                       value="<?= $task->id; ?>">
                                                <input type="submit" class="btn btn-primary w-100" value="Sacuvaj">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/chart.min.js"></script>
<script src="assets/js/bs-init.js"></script>
<script src="assets/js/theme.js"></script>
</body>
</html>
