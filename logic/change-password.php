<?php
require_once __DIR__ . '/../classes/User.php';
if (isset($_POST['email'])){
    if (empty($_POST['email'])) {
        header("Location: ../change-password.php?error=no-access");
        die();
    }
    $email = $_POST['email'];


    $user = User::getUserByEmail($email);
    if (!$user){
        header("Location: ../change-password.php");
        die();
    }

    $user->send_pw_change_link();

    echo "<script>
          alert('Ako ste uneli ispravan email, stici ce vam link za promenu lozinke!');
          setTimeout(function() {
              window.location.href = '../change-password.php';
          }, 1000);
          </script>";

} elseif (isset($_POST['password']) && isset($_POST['repeat_password']) && isset($_POST['token'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];
  if (empty($password) || empty($repeat_password) || empty($token)) {
      header("Location: ../change-password.php?error=no-access");
      die();
  }
  if ($password !== $repeat_password) {
      header("Location: ../change-password.php?error=password-not-match&token=" . $token);
      die();
  }
    $token_parts = User::verify_token($token);

    if (!$token_parts){
        header("Location: ../index.php?error=invalid-token");
        die();
    }

    $email = $token_parts[0];

    User::change_password($email, $password);
    session_start();
    session_regenerate_id(true);
    header("Location: ../index.php?success=password-changed");
} else {
    header("Location: ../index.php?error=1");
    die();
}

