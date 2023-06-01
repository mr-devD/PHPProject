<?php
require_once __DIR__ . '/includes/session_check.php';
require_once __DIR__ . '/classes/User.php';
$user = User::getUserById($_SESSION['user_id']);
$users = User::getAllUsers();
if ($user->user_type_id !== 1) {
    header("Location: loggedin.php?error=no-access");
    die();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Korisnici</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body id="page-top">
<div id="wrapper">
    <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
        <div class="container-fluid d-flex flex-column p-0"><a
                class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
                <div class="sidebar-brand-text mx-3"><span>Projekat</span></div>
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
                    <li class="nav-item"><a class="nav-link active" href="users.php"><i class="fas fa-table"></i><span>Korisnici</span></a>
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
                <h3 class="text-dark mb-4">Korisnici</h3>
                <div class="card shadow">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 fw-bold">Podaci korisnika</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table mt-2">
                            <table class="table my-0" id="dataTable">
                                <thead>
                                <tr>
                                    <th>Ime i prezime</th>
                                    <th>E-mail</th>
                                    <th>Korisnicko ime</th>
                                    <th>Telefon</th>
                                    <th>Kreiran</th>
                                    <th>Tip korisnika</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($users as $user) { ?>
                                    <tr>
                                        <td><img class="rounded-circle me-2" width="30" height="30"
                                                 src="user_img/user-avatar.png"><a
                                                href="profile.php?id=<?= $user->id ?>"
                                                style="text-decoration: none"><?= $user->full_name ?></a></td>
                                        <td><?= $user->email ?></td>
                                        <td><?= $user->username ?></td>
                                        <td><?= $user->phone ?></td>
                                        <td><?= $user->created_at ?></td>
                                        <td><?= $user->getUserType() ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Ime i prezime</th>
                                    <th>E-mail</th>
                                    <th>Korisnicko ime</th>
                                    <th>Telefon</th>
                                    <th>Kreiran</th>
                                    <th>Tip korisnika</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable({
                    "language": {
                        "emptyTable":     "Nema podataka u tabeli",
                        "info":           "Prikazano _START_ do _END_ od _TOTAL_ redova",
                        "infoEmpty":      "Prikazano 0 do 0 od 0 redova",
                        "infoFiltered":   "(Filtrirano od ukupno _MAX_ podataka)",
                        "loadingRecords": "Ucitavanje...",
                        "search":         "Pretrazi:",
                        "zeroRecords":    "Nije pronadjen nijedan podatak",
                        "lengthMenu":     "Prikazi _MENU_ podataka",
                        "paginate": {
                            "first":      "Prvi",
                            "last":       "Poslednji",
                            "next":       "Sledeci",
                            "previous":   "Prethodni"
                        },
                    }
                });
            });
        </script>

        <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/chart.min.js"></script>
        <script src="assets/js/bs-init.js"></script>
        <script src="assets/js/theme.js"></script>
</body>
</html>