<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}


require_once __DIR__ . '/../Database/db_connect.php';
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $cust_code = trim($_POST['cust_code'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $address_line = trim($_POST['address_line'] ?? '');

    if (empty($cust_code) || empty($type) || empty($name) || empty($phone)) {
        $message = "Error: Please fill in all required fields (Customer Code, Type, Name, Phone).";
        $messageType = "error";
    }
    else {
        $sql = "INSERT INTO customers (user_id, cust_code, type, name, email, phone, city, address_line) 
                VALUES (:user_id, :cust_code, :type, :name, :email, :phone, :city, :address_line)";

        try {
            $stmt = $pdo->prepare($sql);

            $params = [
                ':user_id' => $_SESSION['id'],
                ':cust_code' => $cust_code,
                ':type' => $type,
                ':name' => $name,
                ':email' => !empty($email) ? $email : null,
                ':phone' => $phone,
                ':city' => !empty($city) ? $city : null,
                ':address_line' => !empty($address_line) ? $address_line : null
            ];

            if ($stmt->execute($params)) {
                $_SESSION['flash_message'] = "Success! Customer data has been saved.";
                $_SESSION['flash_type'] = "success";
                header('Location: customer.php');
                exit;
            }
            else {
                $message = "Error: Could not save customer data.";
                $messageType = "error";
            }
        }
        catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Error: Customer Code or Phone Number already exists.";
            }
            else {
                $message = "Error: " . $e->getMessage();
            }
            $messageType = "error";
        }
    }
}
?>