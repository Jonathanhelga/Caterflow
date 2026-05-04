<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: actionOptions.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php';
require_once __DIR__ . '/../Mailer/mailer.php';

$email = "";
$email_err = "";
$global_err = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) { $email_err = "Please enter your email."; } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    }

    if (empty($email_err)) {
        try {
            $stmt = $pdo->prepare("SELECT user_id, username, email FROM User_List WHERE email = :email AND is_active = 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $token);
                $expires = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

                $pdo->prepare("UPDATE password_resets SET used_at = NOW() WHERE user_id = :uid AND used_at IS NULL")
                    ->execute(['uid' => $user['user_id']]); //this part will prevent unused link in case the user resubmit her/his email and doesn't use it the previously submitted 

                $pdo->prepare("INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (:uid, :h, :e)")
                    ->execute(['uid' => $user['user_id'], 'h' => $tokenHash, 'e' => $expires]);//we insert a new row for later use as a comparison of hash value to prevent missmatching account possibility

                $appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
                $resetLink = $appUrl . '/password_reset.php?token=' . $token;

                send_password_reset_email($user['email'], $user['username'], $resetLink);
            }

            $success = true;
        } catch (PDOException $e) {
            error_log("Forgot password error: " . $e->getMessage());
            $global_err = "Something went wrong. Please try again later.";
        }
    }
}


//   DELETE FROM password_resets WHERE used_at IS NOT NULL AND used_at < NOW() - INTERVAL 30 DAY;                                                                                                                                                                        
//   DELETE FROM password_resets WHERE expires_at < NOW() - INTERVAL 30 DAY;