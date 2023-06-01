<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/Database.php';

class User
{
    public int $id;
    public string $username;
    public string $email;
    public string $full_name;
    public string $password;
    public ?string $phone;
    public string $birthdate;
    public string $created_at;
    public int $user_type_id;
    public string $activation_code;
    public int $activated;

    //User register function
    public static function register($username, $email, $full_name, $password, $phone, $birthdate, $activation_code): ?string
    {
        $db = Database::getInstance();
        $password = hash('sha512', $password);

        $user = $db->select('User',
            'SELECT * FROM users WHERE email LIKE :email OR username LIKE :username OR phone LIKE :phone',
            [
                ':email' => $email,
                ':username' => $username,
                ':phone' => $phone
            ]
        );

        if (!$phone) {
            $phone = null;
        }

        if (!$user) {
            $db->insert('User', 'INSERT INTO users (username, email, full_name, password, phone, birthdate, activation_code) VALUES (:username, :email, :full_name, :password, :phone, :birthdate, :activation_code)',
                [
                    ":username" => $username,
                    ":email" => $email,
                    ":full_name" => $full_name,
                    ":password" => $password,
                    ":phone" => $phone,
                    ":birthdate" => $birthdate,
                    ":activation_code" => $activation_code
                ]);
            return $db->lastInsertId();
        }
        return null;
    }

    public static function login($email, $password)
    {
        $db = Database::getInstance();
        $password = hash('sha512', $password);

        $users = $db->select('User', 'SELECT * FROM users WHERE email LIKE :email AND password LIKE :password',
        [
           ":email" => $email,
           ":password" => $password
        ]);

        foreach ($users as $user){
            return $user;
        }
        return null;
    }

    public static function change_password ($email, $new_password): void
    {
        $db = Database::getInstance();
        $new_password = hash('sha512', $new_password);

        $db->update('User', 'UPDATE users SET password = :new_password WHERE users . email = :email',
        [
            ":email" => $email,
            ":new_password" => $new_password
        ]);
    }

    public static function getAllUsers(): array
    {
        $db = Database::getInstance();

        return $db->select('User', 'SELECT * FROM users');
    }

    public function generateNewActivationCode($code): void
    {
        $db = Database::getInstance();
        $db->update('User', 'UPDATE users SET activation_code = :code WHERE users . email = :email',
        [
            ":code" => $code,
            ":email" => $this->email
        ]);

    }


    public static function generateActivationCode($expireTimeInMinutes = 30): string
    {
        $code = substr(md5(uniqid(mt_rand(), true)), 0, 10);
        $expireTime = time() + $expireTimeInMinutes * 60;
        return $code . '|' . $expireTime;
    }

    public static function isCodeValid($code): bool
    {
        $parts = explode('|', $code);
        $codeExpirationTime = $parts[1];
        return (time() <= $codeExpirationTime);
    }

    public static function getUserByEmail($email)
    {
        $db = Database::getInstance();

        $users = $db->select('User', 'SELECT * FROM users WHERE email LIKE :email',
            [
                ":email" => $email
            ]);

        foreach ($users as $user) {
            return $user;
        }
        return null;
    }

    public static function getUserById($id){
        $db = Database::getInstance();

        $users = $db->select('User', 'SELECT * FROM users WHERE id LIKE :id',
            [
                ":id" => $id
            ]);

        foreach ($users as $user) {
            return $user;
        }
        return null;
    }

    public static function getByUsername($username) {
        $db = Database::getInstance();

        $users =  $db->select('User', 'SELECT * FROM users WHERE username LIKE :username', [
           ":username" => $username
        ]);

        foreach ($users as $user) {
            return $user;
        }

        return null;
    }

    public static function getByPhone($phone) {
        $db = Database::getInstance();

        $users =  $db->select('User', 'SELECT * FROM users WHERE phone LIKE :phone', [
            ":phone" => $phone
        ]);

        foreach ($users as $user) {
            return $user;
        }

        return null;
    }

    public static function editProfile($id, $full_name, $username, $phone, $birthdate, $email = null, $user_type_id = null) {
        $db = Database::getInstance();
        if ($email == null || $user_type_id == null) {
            $db->update('User', 'UPDATE users SET username = :username, full_name = :full_name, phone = :phone, birthdate = :birthdate WHERE id LIKE :id',
            [
                ":id" => $id,
                ":username" => $username,
                ":full_name" => $full_name,
                ":phone" => $phone,
                ":birthdate" => $birthdate
            ]);
        } else {
            $db->update('User', 'UPDATE users SET email = :email, full_name = :full_name, username = :username, phone = :phone, user_type_id = :user_type_id, birthdate = :birthdate WHERE id LIKE :id',
                [
                    ":id" => $id,
                    ":email" => $email,
                    ":full_name" => $full_name,
                    ":username" => $username,
                    ":phone" => $phone,
                    ":user_type_id" => $user_type_id,
                    ":birthdate" => $birthdate
                ]);
        }
    }

    public static function getExecutants(): ?array
    {
        $db = Database::getInstance();

        return $db->select('User', 'SELECT * FROM users WHERE user_type_id LIKE 2');
    }


    public function getUserType()
    {
        $db = Database::getInstance();

        $user_types = $db ->select('User', 'SELECT * FROM user_types WHERE id = :user_type_id',
        [
            ":user_type_id" => $this->user_type_id
        ]);
        foreach ($user_types as $user_type) {
            return $user_type->type_name;
        }
        return null;
    }


    public function activateUser(): void
    {
        $db = Database::getInstance();
        $db->update('User', 'UPDATE users SET activated = 1 WHERE users . email = :email',
            [
                ":email" => $this->email
            ]);
    }

    public static function setMail(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.sendgrid.net';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';

        $mail->SMTPAuth = true;
        $mail->Username = 'apikey';
        $mail->Password = 'Your_password';
        $mail->setFrom('email@email.com', 'Name');

        return $mail;


    }

    public static function send_link($email, $full_name, $activation_code): void
    {
        $secretkey = "sigurnost";
        $token = base64_encode($email . ':' . $activation_code . ':' . hash_hmac('sha256', $email . ':' . $activation_code, $secretkey));
        $mail = self::setMail();

        $mail->addAddress($email, $full_name);
        $mail->isHTML();
        $mail->Subject = 'Verifikacioni kod';
        $mail->Body = "<div style='max - width: 600px; margin: 0 auto; font - family: Arial, sans - serif;'>
		<h1 style='font - size: 24px; font - weight: bold; color: #000000; text-align: center;'>Dobro dosli!</h1>
		<p style = 'font-size: 16px; color: #000000; line-height: 24px;' > Postovani $full_name,</p >
		<p style = 'font-size: 16px; color: #000000; line-height: 24px;' > Da bi ste verifikovali vas nalog, kliknite na link ispod:</p >
		<a href ='http://localhost/projekat1/logic/verify-account.php?token=$token' style = 'display: block; margin: 24px auto; max-width: 200px; height: 48px; line-height: 48px; text-align: center; font-size: 16px; font-weight: bold; color: #ffffff; text-decoration: none; background-color: #007bff; border-radius: 24px;' > Aktiviraj Nalog </a >
		<p style = 'font-size: 16px; color: #000000; line-height: 24px;' > Ovaj link vazi samo 30 minuta. Ako ne obavite verifikaciju u narednih 30 minuta, moracete da zatrazite novi link! .</p >
		<p style = 'font-size: 16px; color: #000000; line-height: 24px;' > Hvala,</p >
		<p style = 'font-size: 16px; color: #000000; line-height: 24px;' >Projekat1 Tim</p >
	</div > ";
        $mail->send();
    }

    public function send_pw_change_link (): void
    {
        $secretkey = "sigurnost";
        $token = base64_encode($this->email . ':' . $this->activation_code . ':' . hash_hmac('sha256', $this->email . ':' . $this->activation_code, $secretkey));
        $mail = self::setMail();

        $mail->addAddress($this->email, $this->full_name);
        $mail->isHTML();
        $mail->Subject = 'Promena Lozinke';
        $mail->Body = "<div style='max - width: 600px; margin: 0 auto; font - family: Arial, sans - serif;'>
        <h1 style='font - size: 24px; font - weight: bold; color: #000000; text-align: center;'>Zahtev za promenu lozinke!</h1>
        <p style = 'font-size: 16px; color: #000000; line-height: 24px;' > Postovani $this->full_name,</p>
        <p style = 'font-size: 16px; color: #000000; line-height: 24px;' > Za vas nalog je upucen zahtev za promenu lozinke, da bi ste obavili promenu, kliknite na link ispod: </p>
        <a href='http://localhost/projekat1/change-password.php?token=$token' style = 'display: block; margin: 24px auto; max-width: 200px; height: 48px; line-height: 48px; text-align: center; font-size: 16px; font-weight: bold; color: #ffffff; text-decoration: none; background-color: #007bff; border-radius: 24px;'>Promeni Lozinku</a>
        <p style = 'font-size: 16px; color: #000000; line-height: 24px;' >Ovaj link vazi samo 30 minuta.</p>
        <p style = 'font-size: 16px; color: #000000; line-height: 24px;' >Ukoliko niste vi zatrazili zahtev za promenu lozinke, molimo da ovaj mejl obrisete.</p>
        <p style = 'font-size: 16px; color: #000000; line-height: 24px;' > Hvala,</p>
        <p style = 'font-size: 16px; color: #000000; line-height: 24px;' >Projekat1 Tim</p>
        </div>";
        $mail->send();
    }

    public static function verify_token($token): ?array
    {
        $secret_key = 'sigurnost';
        $decoded_token = base64_decode($token);
        $token_parts = explode(':', $decoded_token);

        if (count($token_parts) != 3) {
            return null;
        }

        $email = $token_parts[0];
        $code = $token_parts[1];
        $hash = $token_parts[2];


        if ($hash !== hash_hmac('sha256', $email . ':' . $code, $secret_key)) {
            return null;
        }
        return $token_parts;
    }

    public function deleteUser() {
        $db = Database::getInstance();

        $db->delete('DELETE FROM users WHERE id LIKE :id',
        [
           ":id" => $this->id
        ]);
    }


}