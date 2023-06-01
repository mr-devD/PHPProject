<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=not-logged-in");
    die();
}

require_once __DIR__ . '/classes/User.php';
$user = User::getUserById($_SESSION['user_id']);

if ($user->user_type_id !== 1) {
    header("Location: loggedin.php?error=no-access");
    die();
}

require_once __DIR__ . '/classes/UserType.php';
$types = UserType::getAllTypes();
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
                <li class="nav-item"><a class="nav-link" href="tasks.php"><i
                            class="fas fa-tasks"></i><span>Zadaci</span></a></li>
                <?php if ($user->user_type_id === 1) { ?>
                    <li class="nav-item"><a class="nav-link" href="users.php"><i class="fas fa-table"></i><span>Korisnici</span></a>
                    </li>
                    <li class="nav-item"><a class="nav-link active" href="user_types.php"><i class="fas fa-table"></i><span>Tipovi Korisnika</span></a>
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
                <h3 class="text-dark mb-4">Tipovi korisnika</h3>
                <div class="row mb-5 d-flex align-items-center justify-content-center">
                    <div class="col-6">
                        <div class="card shadow">
                            <div class="card-header">
                                Dodavanje tipa korisnika
                            </div>
                            <div class="card-body">
                                <form action="logic/add_user_type.php" method="post">
                                    <div class="mb-3">
                                        <label for="userTypeName" class="form-label">Ime tipa koji zelite da
                                            dodate?</label>
                                        <input type="text" name="userTypeName" class="form-control" id="userTypeName"
                                               placeholder="Ime tipa">
                                    </div>
                                    <div class="mb-3">
                                        <label for="userTypePriority" class="form-label">Prioritet tipa
                                            korisnika</label>
                                        <input type="text" name="userTypePriority" class="form-control"
                                               id="userTypePriority" placeholder="Prioritet">
                                    </div>
                                    <input type="hidden" name="user_type_id" value="<?= $user->user_type_id ?>">
                                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
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
                                Lista Tipova
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Ime tipa</th>
                                        <th>Prioritet tipa</th>
                                        <th>Akcije</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($types as $type) { ?>
                                        <tr>
                                            <td><?= $type->type_name ?></td>
                                            <td><?= $type->priority ?></td>
                                            <td>
                                                <div class="d-inline-block">
                                                    <button class="btn btn-warning"
                                                            data-group-id="<?= $type->id ?>">Izmeni
                                                    </button>
                                                </div>
                                                <div class="d-inline-block">
                                                    <form action="logic/delete_user_type.php" method="post"
                                                          class="delete-usertype-form">
                                                        <input type="hidden" name="type_id" value="<?= $type->id ?>">
                                                        <input type="hidden" name="user_id" value="<?= $user->id ?>">
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
