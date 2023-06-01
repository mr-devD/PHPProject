<?php
require_once __DIR__ . '/includes/session_check.php';
require_once __DIR__ . '/classes/User.php';
$user = User::getUserById($_SESSION['user_id']);
$executants = User::getExecutants();
require_once __DIR__ . '/classes/TaskGroup.php';
$groups = TaskGroup::getAllGroups();
require_once __DIR__ . '/classes/Task.php';
$tasks = Task::getAllTasks();

if (isset($_GET['error']) && $_GET['error'] === 'no-access') {
    echo '<script> alert("Nemate pristup!");</script>';
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zadaci</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body id="page-top">
<div id="wrapper">
    <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
        <div class="container-fluid d-flex flex-column p-0"><a
                class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
                <div class="sidebar-brand-text mx-3"><span>Brand</span></div>
            </a>
            <hr class="sidebar-divider my-0">
            <ul class="navbar-nav text-light" id="accordionSidebar">
                <li class="nav-item"><a class="nav-link" href="profile.php"><i
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
            <div class="text-center d-none d-md-inline">
                <button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button>
            </div>
        </div>
    </nav>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                <div class="container-fluid">
                    <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i
                            class="fas fa-bars"></i></button>
                    <ul class="navbar-nav flex-nowrap ms-auto">
                        <div class="d-none d-sm-block topbar-divider"></div>
                        <li class="nav-item dropdown no-arrow">
                            <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link"
                                                                       aria-expanded="false" data-bs-toggle="dropdown"
                                                                       href="#"><span
                                        class="d-none d-lg-inline me-2 text-gray-600 small"><?= $user->full_name ?></span></a>
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
                <h3 class="text-dark mb-0">Zadaci</h3>
                <?php if ($user->user_type_id !== 2) { ?>
                    <div class="row mb-5 d-flex align-items-center justify-content-center">
                        <div class="col-8">
                            <div class="card shadow">
                                <div class="card-header">
                                    Dodavanje zadatka
                                </div>
                                <div class="card-body">
                                    <form action="logic/add_task.php" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="taskName" class="form-label">Naslov Zadatka</label>
                                            <input type="text" name="taskName" class="form-control" id="taskName"
                                                   placeholder="Naslov zadatka" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="taskDescription" class="form-label">Opis zadatka</label>
                                            <input type="text" name="taskDescription" class="form-control"
                                                   id="taskDescription" placeholder="Opis" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="taskDeadline" class="form-label">Rok izvrsenja zadatka</label>
                                            <input type="date" name="taskDeadline" class="form-control"
                                                   id="taskDeadline" min="<?= date('Y-m-d') ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="taskPriority" class="form-label">Prioritet zadatka</label>
                                            <select name="taskPriority" class="form-control" id="taskPriority" required>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="taskGroup" class="form-label">Grupa kojoj zadatak
                                                pripada</label>
                                            <select name="taskGroup" class="form-control" id="taskGroup" required>
                                                <?php foreach ($groups as $group) { ?>
                                                    <option value="<?= $group->id ?>"><?= $group->group_name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="taskExecutants" class="form-label">Izaberi izvrsioce</label>
                                            <select name="taskExecutants[]" class="form-control" id="taskExecutants"
                                                    required multiple>
                                                <?php foreach ($executants as $executant) { ?>
                                                    <option
                                                        value="<?= $executant->id ?>"><?= $executant->full_name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="taskAttachments" class="form-label">Prilozi za zadatak</label>
                                            <input type="file" name="taskAttachments[]" class="form-control"
                                                   id="taskAttachments" placeholder="Prilozi" accept=".pdf, image/jpeg, image/png, image/gif" multiple>
                                        </div>

                                        <input type="hidden" name="taskManager" value="<?= $user->id ?>" required>
                                        <input type="hidden" value="<?= $user->user_type_id ?>" required>
                                        <button type="submit" class="btn btn-primary">Dodaj</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="row mb-5 d-flex align-items-center justify-content-center">
                    <div class="col-8">
                        <div class="card shadow">
                            <div class="card-header">
                                Lista Zadataka
                            </div>
                            <div class="card-body">
                                <table class="table" id="task-table">
                                    <thead>
                                    <tr>
                                        <th>Ime zadatka</th>
                                        <th>Opis zadatka</th>
                                        <th>Rukovodioc</th>
                                        <th>Prioritet</th>
                                        <th>Grupa</th>
                                        <th>Rok Izvrsenja</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($tasks as $task) { ?>
                                        <?php if ($task->checkExecutant($user->id) || $user->user_type_id === 1 || $user->user_type_id === 3) { ?>
                                            <tr class="<?php if ($task->completed) {echo "bg-success text-white";} elseif ($task->canceled) {echo "bg-warning text-white";} ?>">
                                                <td>
                                                    <a href="task.php?task-id=<?= $task->id ?>"
                                                       style="text-decoration: none"><?= $task->subject ?></a>
                                                </td>
                                                <td><?= strlen($task->description) > 80 ? substr($task->description, 0, 80) . '...' : $task->description ?></td>
                                                <td><?= User::getUserById($task->manager)->full_name ?></td>
                                                <td><?= $task->priority ?></td>
                                                <?php $group = TaskGroup::getById($task->task_group_id);?>
                                                <td><?= $group ? $group->group_name : "Nema grupu" ?></td>
                                                <td><?= date('j-m-Y', strtotime($task->deadline)) ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#task-table').DataTable({
            "language": {
                "emptyTable": "Nema podataka u tabeli",
                "info": "Prikazano _START_ do _END_ od _TOTAL_ redova",
                "infoEmpty": "Prikazano 0 do 0 od 0 redova",
                "infoFiltered": "(Filtrirano od ukupno _MAX_ podataka)",
                "loadingRecords": "Ucitavanje...",
                "search": "Pretrazi:",
                "zeroRecords": "Nije pronadjen nijedan podatak",
                "lengthMenu": "Prikazi _MENU_ podataka",
                "paginate": {
                    "first": "Prvi",
                    "last": "Poslednji",
                    "next": "Sledeci",
                    "previous": "Prethodni"
                },
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#taskExecutants').select2();
    });
</script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/chart.min.js"></script>
<script src="assets/js/bs-init.js"></script>
<script src="assets/js/theme.js"></script>
</body>
</html>