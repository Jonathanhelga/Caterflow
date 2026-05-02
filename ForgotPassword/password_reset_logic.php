<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: actionOptions.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php';

$token = $_GET['token'] ?? $_POST['token'] ?? '';
$password_err = "";
$confirm_err = "";
$global_err = "";
$valid_token = false;
$reset_row = null;

if (!empty($token) && preg_match('/^[a-f0-9]{64}$/', $token)) {
    $tokenHash = hash('sha256', $token);
    $stmt = $pdo->prepare("SELECT reset_id, user_id, expires_at, used_at FROM password_resets WHERE token_hash = :h LIMIT 1");
    $stmt->execute(['h' => $tokenHash]);
    $reset_row = $stmt->fetch();

    if ($reset_row && $reset_row['used_at'] === null && strtotime($reset_row['expires_at']) > time()) {
        $valid_token = true;
    }
}

if (!$valid_token) {
    $global_err = "This password reset link is invalid or has expired. Please request a new one.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && $valid_token) {
    $pass = trim($_POST['password'] ?? '');
    $conf = trim($_POST['confirm_password'] ?? '');

    if (empty($pass)) {
        $password_err = "Please enter a new password.";
    } elseif (strlen($pass) < 6) {
        $password_err = "Password must have at least 6 characters.";
    }

    if ($pass !== $conf) {
        $confirm_err = "Passwords do not match.";
    }

    if (empty($password_err) && empty($confirm_err)) {
        try {
            $pdo->beginTransaction();

            $newHash = password_hash($pass, PASSWORD_BCRYPT);
            $pdo->prepare("UPDATE User_List SET password_hash = :h WHERE user_id = :uid")
                ->execute(['h' => $newHash, 'uid' => $reset_row['user_id']]);

            $pdo->prepare("UPDATE password_resets SET used_at = NOW() WHERE reset_id = :rid")
                ->execute(['rid' => $reset_row['reset_id']]);

            $pdo->prepare("UPDATE password_resets SET used_at = NOW() WHERE user_id = :uid AND used_at IS NULL")
                ->execute(['uid' => $reset_row['user_id']]);

            $pdo->commit();

            $_SESSION['success_msg'] = "Password reset successful. Please log in with your new password.";
            header("location: login.php");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Password reset error: " . $e->getMessage());
            $global_err = "Something went wrong. Please try again later.";
        }
    }
}
