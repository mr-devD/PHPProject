<?php
$email = $_GET['email'];
$full_name = $_GET['full_name'];
if (empty($email) || empty($full_name)){
    header("Location: index.php?error=no-access");
    die();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikacija</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <p class="text-center">Ovaj nalog nije verifikovan! Verifikujte ga uz pomoć linka koji ste dobili na email!</p>
            <p class="text-center">Ili zatražite novi link uz pomoć dugmeta ispod:</p>
            <form action="logic/send_link.php" method="post">
                <input type="hidden" name="email" value="<?= $email ?>">
                <input type="hidden" name="full_name" value="<?= $full_name ?>">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Zatraži novi link</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
