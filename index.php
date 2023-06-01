<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: loggedin.php?error=already-logged-in");
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
    <title>Login</title>
    <style>
        /* Center the login form on the page */
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #login {
            max-width: 250px;
        }

        /* Style the lock logo and header */
        .lock_logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .lock_logo img {
            width: 100px;
            height: 100px;
        }

        .lock_logo h1 {
            margin: 0;
            font-size: 28px;
        }

        /* Style the login form */
        .login {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }

        .login_form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login_row1,
        .login_row2 {
            display: flex;
            align-items: center;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
            width: 100%;
        }

        .login_row1 img,
        .login_row2 img {
            margin-right: 10px;
        }

        .login_row1 input[type="email"],
        .login_row2 input[type="password"] {
            border: none;
            outline: none;
            width: 100%;
        }

        .login_submit {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            width: 100%;
        }

        .login_submit:hover {
            background-color: #0062cc;
        }

        a {
            color: #007bff;
            text-decoration: none;
            margin-top: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        .success-message {
            color: #34dc15;
        }

        .error-message {
            color: #ff102a;
        }

    </style>
</head>
<body>
<main>
    <div id="login">
        <form action="logic/login.php" method="post" id="login_form">
            <div class="login">
                <div class = "lock_logo">
                    <img src="slike/icons8-lock-144.png" alt="LOCK ICON">
                    <h1>LOGIN</h1>
                </div>
                <div class="login_form">
                    <div class="login_row1">
                        <img src="slike/icons8-email-24.png" alt="email_icon"/>
                        <input type="email" name="email" placeholder="email" required>
                    </div>
                    <div class="login_row2">
                        <img src="slike/icons8-password-24.png" alt="password_icon">
                        <input type="password" name="password" placeholder="lozinka" required>
                    </div>
                    <input type="submit" value="Login" class="login_submit"><br>
                    <?php if (isset($_GET['success'])) {
                        $success = $_GET['success']; ?>
                        <div class="success-message">
                            <?php if ($success === 'register-success') { ?>
                                <p>Poslat vam je verifikacioni mejl! Verifikujte nalog!</p>
                            <?php } elseif ($success === 'password-changed') { ?>
                                <p>Uspesno ste promenili lozinku, mozete se ulogovati!</p>
                            <?php } elseif ($success === 'user-verified') { ?>
                            <p>Uspesno ste verifikovali nalog, mozete se ulogovati!</p>
                            <?php } ?>
                        </div>
                    <?php } elseif (isset($_GET['error'])) {
                        $error = $_GET['error']; ?>
                        <div class="error-message">
                            <?php if ($error === 'credentials-empty') { ?>
                                <p>Unesite neophodne podatke!</p>
                            <?php } elseif ($error === 'wrong-credentials') { ?>
                                <p>Neispravan email ili lozinka!</p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <a href="registration.php">Napravi nalog</a>
                    <a href="change-password.php">Promeni Lozinku</a>
                </div>
            </div>
        </form>
    </div>
</main>
</body>
</html>