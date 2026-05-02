<?php
session_start();

// Redirect to ActionOptions if already logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: actionOptions.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php';

$username = "";
$password = "";
$username_err = "";
$password_err = "";
$login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err)){
        try {
            $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM User_List WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username, 'email' => $username]);

            if($user = $stmt->fetch()){
                if(password_verify($password, $user['password_hash'])){
                    session_regenerate_id();

                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $user['user_id'];
                    $_SESSION["username"] = $user['username'];

                    header("location: actionOptions.php");
                    exit;
                } else {
                    $login_err = "Invalid username or password.";
                }
            } else { 
                $login_err = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            $login_err = "Oops! Something went wrong. Please try again later.";
        }
    }
}