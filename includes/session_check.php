<?php
$current_path = $_SERVER['PHP_SELF'];
session_start();
if (!isset($_SESSION['user_id'])) {
    if (strpos($current_path, "/projekat1/logic/")) {
        header('Location: ../index.php?error=not-logged-in');
        die();
    }
	header('Location: ./index.php?error=not-logged-in');
	die();
}
