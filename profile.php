<?php
require_once __DIR__ . '/includes/session_check.php';
require_once __DIR__ . '/classes/User.php';
$user = User::getUserById($_SESSION['user_id']);

if (!isset($_GET['id'])){
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        header("Location: profile.php?id=$user->id&error=$error");
        die();
    }
    header("Location: profile.php?id=$user->id");
    die();
}

if (!User::getUserById($_GET['id'])){
    header("Location: profile.php?id=$user->id");
    die();
}

if ($_GET['id'] != $user->id && $user->user_type_id != 1) {
    header("Location: profile.php?id=$user->id");
    die();
}

$profile = User::getUserById($_GET['id']);

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
    <title><?=$profile->full_name?></title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
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
                <li class="nav-item"><a class="nav-link active" href="profile.php"><i
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
                    <h3 class="text-dark mb-5">Profil - <?= $profile->full_name ?></h3>
                    <div class="row mb-2 d-flex align-items-center justify-content-center">
                        <div class="col-8">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h4 class="card-title">Izmena informacija
                                        <?php if ($user->user_type_id === 1) { ?>
                                            <form action="logic/delete_user.php" method="post" class="float-end">
                                                <input type="hidden" name="profile_id" value="<?= $profile->id ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php } ?>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <form action="logic/edit-profile.php" method="post">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-2">
                                                    <label for="full_name" class="form-label">Ime i prezime:</label>
                                                    <input type="text" name="full_name" id="full_name" class="form-control" value="<?= $profile->full_name ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-2">
                                                    <label for="username" class="form-label">Korisnicko ime:</label>
                                                    <input type="text" name="username" id="username"
                                                           class="form-control" value="<?= $profile->username ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-2">
                                                    <label for="email" class="form-label">E-mail:</label>
                                                    <input type="email" name="email" id="email"
                                                           class="form-control <?php if ($user->user_type_id !== 1) {
                                                               echo "shadow-none border-0";
                                                           } ?>"
                                                           value="<?= $profile->email ?>" <?php if ($user->user_type_id !== 1) {
                                                        echo "disabled";
                                                    } ?> required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-2">
                                                    <label for="phone" class="form-label">Telefon:</label>
                                                    <input type="tel" name="phone" id="phone" class="form-control" value="<?=$profile->phone?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-2">
                                                    <label for="birthdate" class="form-label">Datum rodjenja:</label>
                                                    <input type="date" name="birthdate" id="birthdate" class="form-control" value="<?=$profile->birthdate?>" required>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-2">
                                                    <label for="usertype" class="form-label">Tip korisnika:</label>
                                                    <select name="usertype" id="usertype" class="form-control <?php if ($user->user_type_id !== 1) {
                                                        echo "shadow-none border-0";} ?>" <?php if ($user->user_type_id != 1) {echo "disabled";} ?>>
                                                        <?php foreach ($types as $type) { ?>
                                                            <option value="<?=$type->id?>" <?php if ($type->id === $profile->user_type_id){ echo "selected";}?>><?=$type->type_name?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12">
                                                <div class="mb-2 text-center">
                                                    <?php if ($user->user_type_id == 1) { ?>
                                                        <input type="hidden" name="edit_user_id"
                                                               value="<?= $_GET['id']; ?>">
                                                    <?php } ?>
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