<!doctype html>
<html lang="rs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registracija</title>
    <style>
        /* Styles for the registration form */
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }

        .register_logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .register_logo img {
            height: 200px;
            margin-right: 10px;
        }

        .register_form .column {
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
        }

        .register_row .column::after {
            content: "";
            border-left: 1px solid #ccc;
            height: 100%;
            position: absolute;
            right: 0;
            top: 0;
        }

        .register_form .column img {
            margin-right: 10px;
        }

        .register_form .registration_row {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .register_form .registration_row img {
            margin-right: 10px;
        }



        .register_form input[type="text"],
        .register_form input[type="email"],
        .register_form input[type="password"],
        .register_form input[type="tel"],
        .register_form input[type="date"] {
            display: inline-block;
            font-size: 16px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .registration_submit {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            width: 100%;
        }

        .registration_submit:hover {
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

        .error-message {
            color: #ff102a;
        }


    </style>
</head>
<body>
<main>
<div id="register">
    <form action="logic/register.php" id="register_form" method="post">
        <div class="register">
            <div class="register_logo">
                <img src="slike/user_256px.png" alt="USER_LOGO">
                <h1>Napravi Nalog</h1>
            </div>
            <div class="register_form">
                <div class="registration_row">
                    <img src="slike/icons8-username-24.png" alt="username_icon">
                    <input type="text" name="username" id="username" placeholder="Korisnicko ime" required>
                </div>
                <div class="registration_row">
                    <img src="slike/icons8-email-24.png" alt="email_icon">
                    <input type="email" name="email" id="email" placeholder="Email" required>
                </div>
                <div class="registration_row">
                    <div class="column">
                        <img src="slike/icons8-password-24.png" alt="password_icon">
                        <input type="password" name="password" id="password" placeholder="Lozinka" required>
                    </div>
                    <div class="column">
                        <img src="slike/icons8-password-24.png" alt="password_icon">
                        <input type="password" name="repeat_password" id="repeat_password" placeholder="Ponovite lozinku" required>
                    </div>
                </div>
                <div class="registration_row">
                    <img src="slike/icons8-id-28.png" alt="full_name_icon">
                    <input type="text" name="full_name" id="full_name" placeholder="Ime i Prezime" required>
                </div>
                <div class="registration_row">
                    <div class="column">
                        <img src="slike/icons8-telephone-28.png" alt="phone_icon">
                        <input type="tel" name="phone" id="phone" placeholder="Telefon">
                    </div>
                    <div class="column">
                        <img src="slike/icons8-date-28.png" alt="date_icon">
                        <input type="date" max="<?= date('Y-m-d', strtotime('-18 years')); ?>" name="birthdate" id="birthdate">
                    </div>
                </div>
                <input type="submit" value="Registruj se" class="registration_submit"><br>
                <?php
                if (isset($_GET['error'])) {
                    $error = $_GET['error']; ?>
                    <div class="error-message">
                        <?php if ($error === "password-mismatch") { ?>
                            <p>Lozinke se ne podudaraju!</p>
                        <?php } elseif ($error === "user-exists") { ?>
                            <p>Korisnik vec postoji!</p>
                        <?php } ?>
                    </div>
                <?php } ?>
                <a href="index.php">Login</a><br>
                <a href="change-password.php">Promeni Lozinku</a>
            </div>
        </div>
    </form>
</div>
</main>
</body>
</html>