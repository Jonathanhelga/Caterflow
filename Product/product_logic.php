<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}


require_once __DIR__ . '/../Database/db_connect.php';
$message = "";
$messageType = "";


$categories = [];
try {
    $catStmt = $pdo->query("SELECT category_id, name FROM categories ORDER BY name ASC");
    $categories = $catStmt->fetchAll();
} catch (PDOException $e) {
    $categories = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
    $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $product_price = trim($_POST['product_price'] ?? '');
    $product_cost = trim($_POST['product_cost'] ?? '');
    $product_code = trim($_POST['product_code'] ?? '');
    $category = trim($_POST['category'] ?? '');

    if (empty($name) || empty($type) || empty($product_price) || empty($product_code)) {
        $message = "Error: Please fill in all required fields (Name, Type, Product Price).";
        $messageType = "error";
    }
    else {
        $check = $pdo->prepare("SELECT category_id FROM categories WHERE LOWER(name) = LOWER(:name)");
        $check->execute([':name' => $category]);
        $categoryRow = $check->fetch();

        $sql = "INSERT INTO products (user_id, product_code, category_id, name, source, price, cost)
                VALUES (:user_id, :product_code, :category_id, :name, :source, :price, :cost)";

        try {
            $stmt = $pdo->prepare($sql);

            $params = [
                ':user_id' => $_SESSION['id'],
                ':product_code' => $product_code,
                ':category_id' => $categoryRow['category_id'],
                ':name' => $name,
                ':source' => $type,
                ':price' => $product_price,
                ':cost' => !empty($product_cost) ? $product_cost : 0
            ];

            if ($stmt->execute($params)) {
                $_SESSION['flash_message'] = "Success! Product data has been saved.";
                $_SESSION['flash_type'] = "success";
                header('Location: product.php');
                exit;
            }
            else {
                $message = "Error: Could not save product data.";
                $messageType = "error";
            }
        }
        catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Error: Product Name or Product Price already exists.";
            }
            else { $message = "Error: " . $e->getMessage(); }
            $messageType = "error";
        }
    }
}