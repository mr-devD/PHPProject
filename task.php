<?php
require_once __DIR__ . '/includes/session_check.php';
require_once __DIR__ . '/classes/User.php';
$user = User::getUserById($_SESSION['user_id']);

require_once __DIR__ . '/classes/Task.php';
$task = Task::getTaskById($_GET['task-id']);

require_once __DIR__ . '/classes/TaskGroup.php';

if (!$task) {
    header("Location: tasks.php?error=task-not-exist");
    die();
}


if (!$task->checkExecutant($user->id) && $user->user_type_id !== 1 && $user->user_type_id !== 3) {
    header("Location: tasks.php?error=no-access");
    die();
}

$taskExecutants = $task->getAllExecutants();
require_once __DIR__ . '/classes/Comment.php';
$comments = Comment::getCommentsByTask($task->id);
$attachments = Task::getAttachmentsByTask($task->id);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zadatak - <?= $task->subject ?></title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.4.js"
            integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <script>
        $(function () {
            $(document).on('submit', '#delete_comment', function (e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: {
                        comment_id: form.find('[name="comment_id"]').val(),
                        task_id: form.find('[name="task_id"]').val(),
                        ajax: true
                    },
                    dataType: 'json',
                    success: function (response) {
                        form.parent().parent().parent().remove();
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            });
        });

        $(function () {
            $('#commentForm').on('submit', function (event) {
                event.preventDefault();

                let form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);

                        const sentAt = new Date(response.sent_at);
                        const formattedDate = sentAt.toLocaleString('en-GB', {
                            day: '2-digit',
                            month: '2-digit',
                            year: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false,
                            hourCycle: 'h23'
                        }).replace(',', '');

                        // Create the HTML for the new comment
                        let newCommentHtml = `
    <div class="card mb-3">
        <div class="card-header">
            <strong class="comment-author"><?=$user->full_name ?> :</strong>
            <span class="comment-date">${formattedDate}</span>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <h6 class="card-title fw-bold">${response.title}</h6>
                <form action="logic/delete_comment.php" method="post" id="delete_comment">
                    <input type="hidden" name="comment_id" value="${response.id}">
                    <input type="hidden" name="task_id" value="${response.task_id}">
                    <input type="submit" value="Obrisi" class="btn-sm btn btn-danger">
                </form>
            </div>
            <p class="card-text">
                ${response.description}
            </p>
        </div>
    </div>
`;

                        // Append the new comment HTML to the comments container
                        $('.comment-list').prepend(newCommentHtml);

                        // Clear the form inputs if needed
                        form.trigger('reset');
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
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
                <div class="sidebar-brand-text mx-3"><span>Projekat-PHP</span></div>
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
                                        class="dropdown-item" href="profile.php"><i
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
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Zadatak - <?= $task->subject ?></h3>
                </div>
                <div class="row mb-5 d-flex align-items-center justify-content-center">
                    <div class="col-6">
                        <div class="card shadow">
                            <div class="card-header text-white <?php if ($task->completed) {echo "bg-success";} elseif ($task->canceled) {echo "bg-warning";} else {echo "bg-primary";} ?>">
                                <div>
                                    <h4 class="card-title">Detalji zadatka
                                        <?php if ($user->user_type_id === 1 || ($user->user_type_id === 3 && $task->manager === $user->id)) { ?>
                                            <form action="logic/delete_task.php" method="post" class="float-end">
                                                <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                <input type="submit" value="Obrisi" class="btn btn-sm btn-danger">
                                            </form>
                                            <form action="edit-task.php?task-id=<?=$task->id?>" method="post" class="float-end">
                                                <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                <input type="submit" value="Izmeni" class="btn btn-sm btn-info me-2">
                                            </form>
                                            <?php if (!$task->canceled && !$task->completed) { ?>
                                                <form action="logic/task_canceled.php" method="post" class="float-end me-2">
                                                    <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                    <input type="submit" value="Otkazan " class="btn btn-sm btn-warning">
                                                </form>
                                            <?php }
                                            if (!$task->completed && !$task->canceled) { ?>
                                                <form action="logic/task_completed.php" method="post" class="float-end me-2">
                                                    <input type="hidden" name="task_id" value="<?=$task->id?>">
                                                    <input type="submit" value="Zavrsen" class="btn btn-sm btn-success">
                                                </form>
                                            <?php }
                                        } ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <strong>Opis zadatka:</strong>
                                        <p class="card-text"><?= $task->description ?></p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <strong>Grupa:</strong>
                                        <?php $group = TaskGroup::getById($task->task_group_id) ?>
                                        <p class="card-text"><?= $group ?  $group->group_name : 'Nema grupu' ?> - <?= $group ? $group->group_description : 'Nema grupu'?></p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <strong>Rukovodioc:</strong>
                                        <p class="card-text"><?= User::getUserById($task->manager)->full_name ?></p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <strong>Prioritet:</strong>
                                        <span class="badge <?php if ($task->priority < 4) {echo "bg-success";} elseif ($task->priority < 7) {echo "bg-warning";} else {echo "bg-danger";} ?>"><?= $task->priority ?></span>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <strong>Rok izvrsenja:</strong>
                                        <p class="card-text"><?=date('j-m-Y', strtotime($task->deadline))?></p>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <strong>Prilozi:</strong>
                                        <ul class="list-group">
                                            <?php foreach ($attachments as $attachment) { ?>
                                                <li class="list-group-item">
                                                    <a href="<?= $attachment->file_path ?>"
                                                       download><?= $attachment->name ?></a>
                                                    <span class="mx-1"><?= round($attachment->file_size / (1024 * 1024), 2) ?>MB
                                                    <?php if ($user->user_type_id === 1 || ($task->manager === $user->id)) { ?>
                                                        <form action="logic/remove_attachment.php" class="float-end" method="post">
                                                            <input type="hidden" value="<?= $attachment->id ?>"
                                                                   name="attachment_id">
                                                            <input type="hidden" value="<?=$task->id?>" name="task_id">
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                        </span>
                                                    <?php } ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <strong>Izvršioci:</strong>
                                        <ul class="list-group">
                                            <?php foreach ($taskExecutants as $taskExecutant) {
                                                $completed = $task->checkExecutant($taskExecutant->executant_id)->completed === 1;
                                                ?>
                                                <li class="list-group-item <?php if ($completed) {
                                                    echo "list-group-item-success";
                                                } ?>"><?= User::getUserById($taskExecutant->executant_id)->full_name ?>
                                                    <?php if ($user->user_type_id === 1 || ($task->manager === $user->id)) { ?>
                                                        <div class="float-end mx-1">
                                                            <form action="logic/remove_executant.php" method="post">
                                                                <input type="hidden" value="<?=$taskExecutant->executant_id?>" name="executant_id">
                                                                <input type="hidden" value="<?=$task->id?>" name="task_id">
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    <?php }?>
                                                    <?php if ($taskExecutant->executant_id === $user->id && $task->checkExecutant($taskExecutant->executant_id)->completed !== 1) { ?>
                                                        <div class="float-end">
                                                            <form action="logic/executant_check_task_done.php"
                                                                  method="post">
                                                                <input type="hidden" name="task_id"
                                                                       value="<?= $task->id ?>">
                                                                <input type="hidden" name="executant_id"
                                                                       value="<?= $user->id ?>">
                                                                <input type="submit" class="btn btn-success btn-sm"
                                                                       value="Uradio sam">
                                                            </form>
                                                        </div>
                                                    <?php } ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5 d-flex align-items-center justify-content-center">
                    <div class="col-6">
                        <div class="card shadow">
                            <div class="card-header bg-secondary text-white">
                                <h4 class="mb-0">Komentari</h4>
                            </div>
                            <div class="card-body">
                                <form action="logic/submit_comment.php" method="post" id="commentForm">
                                    <div class="mb-3">
                                        <label for="commentTitle" class="form-label">Naslov komentara:</label>
                                        <input type="text" class="form-control" name="commentTitle" id="commentTitle"
                                               required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="commentDesc" class="form-label">Napiši komentar:</label>
                                        <textarea style="max-height: 100px" class="form-control" name="commentDesc"
                                                  id="commentDesc" rows="3" required></textarea>
                                    </div>
                                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                                    <input type="hidden" name="task_id" value="<?= $task->id ?>">
                                    <input type="hidden" name="sent_at"
                                           value="<?= strtotime(date("Y-m-d h:i:sa", time())); ?>">
                                    <button type="submit" class="btn btn-primary">Pošalji</button>
                                </form>
                                <div class="mt-4">
                                    <h5>Svi komentari:</h5>
                                    <div class="comment-list" id="commentsList">
                                        <?php foreach ($comments as $comment) {
                                            if ($comment->task_id === $task->id) { ?>
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <strong
                                                            class="comment-author"><?= User::getUserById($comment->user_id)->full_name ?>
                                                            :</strong>
                                                        <span
                                                            class="comment-date"><?= date('d/m/y H:i', strtotime($comment->sent_at)) ?></span>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <h6 class="card-title fw-bold"><?= $comment->title ?></h6>
                                                            <?php if ($user->user_type_id === 1 || ($task->manager === $user->id) || $comment->user_id === $user->id) { ?>
                                                                <form action="logic/delete_comment.php"
                                                                      method="post" id="delete_comment">
                                                                    <input type="hidden" name="comment_id"
                                                                           value="<?= $comment->id ?>">
                                                                    <input type="hidden" name="task_id"
                                                                           value="<?= $task->id ?>">
                                                                    <input type="submit" value="Obrisi"
                                                                           class="btn-sm btn btn-danger">
                                                                </form>
                                                            <?php } ?>
                                                        </div>
                                                        <p class="card-text">
                                                            <?= $comment->description ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
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
