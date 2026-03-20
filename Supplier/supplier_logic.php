<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php';

$sql = "SELECT p.* 
        FROM products p
        LEFT JOIN vendors v ON p.product_id = v.product_id
        WHERE p.source = 'Vendor' 
          AND p.user_id = ?
          AND v.vendor_id IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['id']]);
$products = $stmt->fetchAll();

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
    $name = trim($_POST['name'] ?? '');
    $vendor_code = trim($_POST['vendor_code'] ?? '');
    $product_id = trim($_POST['product'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($name) || empty($vendor_code) || empty($product_id) || empty($phone)) {
        $message = "Error: Please fill in all required fields (Name, Vendor Code, Product, Phone).";
        $messageType = "error";
    }
    else {
        $sql = "INSERT INTO vendors (user_id, vendor_code, name, product_id, contact_person, phone) 
                VALUES (:user_id, :vendor_code, :name, :product_id, :contact_person, :phone)";

        try {
            $stmt = $pdo->prepare($sql);

            $params = [
                ':user_id' => $_SESSION['id'],
                ':vendor_code' => $vendor_code,
                ':name' => $name,
                ':product_id' => $product_id,
                ':contact_person' => !empty($contact_person) ? $contact_person : null,
                ':phone' => $phone
            ];

            if ($stmt->execute($params)) {
                $_SESSION['flash_message'] = "Success! Supplier data has been saved.";
                $_SESSION['flash_type'] = "success";
                header('Location: supplier.php');
                exit;
            }
            else {
                $message = "Error: Could not save supplier data.";
                $messageType = "error";
            }
        }
        catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Error: Supplier Name or Phone Number already exists.";
            }
            else {
                $message = "Error: " . $e->getMessage();
            }
            $messageType = "error";
        }
    }
}