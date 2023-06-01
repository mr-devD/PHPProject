<?php
session_start();
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/classes/User.php';
    $user = User::getUserById($_SESSION['user_id']);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Promena Lozinke</title>
    <style>
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .change_pw_logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .change_pw_logo img {
            width: 100px;
            height: 100px;
        }

        .change_pw_logo h1 {
            margin: 0;
            font-size: 28px;
        }

        .change_password {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }

        .change_password_form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .change_pw_row {
            display: flex;
            align-items: center;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
            width: 100%;
        }

        .change_pw_row img {
            margin-right: 10px;
        }

        .change_pw_row input[type='email'],
        input[type='password'] {
            border: none;
            outline: none;
            width: 100%;
        }

        .change_pw_submit {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            width: 100%;
        }

        .change_pw_submit:hover {
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

        #request_change_password {
            display: none;
        }

        #change_password {
            display: none;
        }
    </style>

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');

            if (token) {
                document.getElementById('change_password').style.display = 'block';
            } else {
                document.getElementById('request_change_password').style.display = 'block';
            }
        });
    </script>
</head>
<body>
<main>
    <div id="request_change_password">
        <form action="logic/change-password.php" method="post">
            <div class="change_password">
                <div class="change_pw_logo">
                    <img src="slike/icons8-lock-144.png" alt="pw_logo">
                    <h1>Promena Lozinke</h1>
                </div>
                <div class="change_password_form">
                    <div class="change_pw_row">
                        <img src="slike/icons8-email-24.png" alt="email icon">
                        <input type="email" value="<?=$user->email?>" name="email" placeholder="email" required>
                    </div>
                    <input type="submit" value="Posalji" class="change_pw_submit">
                </div>
                <a href="index.php">Login</a>
            </div>
        </form>
    </div>
    <div id="change_password">
        <form action="logic/change-password.php" method="post">
            <div class="change_password">
                <div class="change_pw_logo">
                    <img src="slike/icons8-lock-144.png" alt="pw_logo">
                    <h1>Promena Lozinke</h1>
                </div>
                <div class="change_password_form">
                    <div class="change_pw_row">
                        <img src="slike/icons8-password-24.png" alt="pw icon">
                        <input type="password" name="password" placeholder="Nova lozinka" required>
                    </div>
                    <div class="change_pw_row">
                        <img src="slike/icons8-password-24.png" alt="pw icon">
                        <input type="password" name="repeat_password" placeholder="Ponovi novu lozinku" required>
                    </div>
                    <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
                    <input type="submit" value="Posalji" class="change_pw_submit">
                </div>
                <a href="index.php">Login</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>