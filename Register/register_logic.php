<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: actionOptions.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php';

$errors = ['username' => "", 'password' => "", 'confirm_password' => "", 'weird' => ""];
$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
    $input_user = trim($_POST["username"]);
    $input_pass = trim($_POST["password"]);
    $input_conf = trim($_POST["confirm_password"]);

    // 1. Username Validation
    if (empty($input_user)) {
        $errors['username'] = "Username field can't be empty";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $input_user)) {
        $errors['username'] = "Username can only contain letters, numbers, and underscores.";
    } else {
        $stmt = $pdo->prepare("SELECT user_id FROM User_List WHERE username = :username");
        $stmt->execute(['username' => $input_user]);
        if ($stmt->fetch()) {
            $errors['username'] = "Username already exists.";
        }
    }

    // 2. Password Validation
    if (empty($input_pass)) {
        $errors['password'] = "Please enter a password.";
    } elseif (strlen($input_pass) < 6) {
        $errors['password'] = "Password must have at least 6 characters.";
    }

    if ($input_pass !== $input_conf) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    // 3. Final Processing
    if (empty(array_filter($errors))) {
        try {
            // --- PDO MIGRATION: Insert User ---
            $sql = "INSERT INTO User_List (username, password_hash) VALUES (:username, :password)";
            $prepare = $pdo->prepare($sql);
            
            $hashed_password = password_hash($input_pass, PASSWORD_BCRYPT);
            
            // Execute with an associative array - much cleaner than bind_param!
            $success = $prepare->execute([
                'username' => $input_user,
                'password' => $hashed_password
            ]);

            if ($success) {
                $_SESSION['success_msg'] = "Registration successful! Please login.";
                header("location: login.php");
                exit; 
            }
        } catch (PDOException $e) {
            error_log("Registration Error: " . $e->getMessage());
            $errors['weird'] = "Something went wrong on the server. Try again later.";
        }
    }
}