<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=not-logged-in");
    die();
}

require_once __DIR__ . '/classes/User.php';
$user = User::getUserById($_SESSION['user_id']);

if ($user->user_type_id === 2) {
    header("Location: loggedin.php?error=no-access");
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
    <title>Grupe Zadataka</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.js"
            integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <script>
        $(function () {
            $('.delete_group').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: {
                        group_id: form.find('[name=group_id]').val(),
                        ajax: true
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (form.attr('class') === 'delete_group') {
                            form.parent().parent().parent().remove();
                        }
                        console.log(response);
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            });
        });

        $(function () {
            $('.edit-group').on('submit', function (event) {
                event.preventDefault();
                let form = $(this);

                form.parent().parent().add('contentEditable="true"')
                $.ajax({
                   url: form.attr('action'),
                   method: form.attr('method'),
                   data: {

                   },
                });
            });
        });
    </script>
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
                    <li class="nav-item"><a class="nav-link active" href="task-groups.php"><i class="fas fa-tasks"></i><span>Grupe Zadataka</span></a>
                    </li>
                <?php } ?>
                <li class="nav-item"><a class="nav-link" href="tasks.php"><i
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
                <h3 class="text-dark mb-4">Grupe Zadataka</h3>
                <div class="row mb-5 d-flex align-items-center justify-content-center">
                    <div class="col-6">
                        <div class="card shadow">
                            <div class="card-header">
                                Dodavanje grupe zadatka
                            </div>
                            <div class="card-body">
                                <form action="logic/add_task_group.php" method="post">
                                    <div class="mb-3">
                                        <label for="taskGroupName" class="form-label">Ime grupe koju zelite da
                                            dodate</label>
                                        <input type="text" name="taskGroupName" class="form-control" id="taskGroupName"
                                               placeholder="Ime grupe">
                                    </div>
                                    <div class="mb-3">
                                        <label for="taskGroupDescription" class="form-label">Opis grupe i cemu ona
                                            sluzi</label>
                                        <input type="text" name="taskGroupDescription" class="form-control"
                                               id="taskGroupDescription" placeholder="Opis">
                                    </div>
                                    <input type="hidden" value="<?= $user->user_type_id ?>">
                                    <button type="submit" class="btn btn-primary">Dodaj</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5 d-flex align-items-center justify-content-center">
                    <div class="col-6">
                        <div class="card shadow">
                            <div class="card-header">
                                Lista Grupa Zadataka
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Ime grupe</th>
                                        <th>Opis grupe</th>
                                        <th>Akcije</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($groups as $group) { ?>
                                        <tr>
                                            <?php if (isset($_GET['edit']) && $group->id == $_GET['task-id']) { ?>
                                                <form action="logic/edit_task_group.php" method="post">
                                                    <td><input type="text" name="group_name" id="group_name" value="<?=$group->group_name?>" class="form-control"></td>
                                                    <td><input type="text" name="group_desc" id="group_desc" value="<?=$group->group_description?>" class="form-control"></td>
                                                    <input type="hidden" name="group_id" id="group_id" value="<?=$group->id?>">
                                                    <td>
                                                        <div class="d-inline-block">
                                                            <input type="submit" class="btn btn-warning" value="Sacuvaj">
                                                        </div>
                                                    </td>
                                                </form>

                                            <?php } else { ?>
                                                <td><?=$group->group_name?></td>
                                                <td><?=$group->group_description?></td>
                                            <?php } ?>
                                            <td>
                                                <?php if (!isset($_GET['edit'])) { ?>
                                                    <div class="d-inline-block">
                                                        <a class="btn btn-warning" href="task-groups.php?edit=true&task-id=<?=$group->id?>">Izmeni</a>
                                                    </div>
                                                <?php } ?>
                                                <div class="d-inline-block">
                                                    <form action="logic/delete_task_group.php" method="post" class="delete-group-form">
                                                        <input type="hidden" name="group_id" value="<?= $group->id ?>">
                                                        <input type="submit" value="Obrisi" class="btn btn-danger">
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
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
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/chart.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="logic/edit-groups.js"></script>
</body>
</html>
