<?php
require_once __DIR__ . '/../includes/session_check.php';

session_destroy();
header("Location: ../index.php?success=logout");
die();