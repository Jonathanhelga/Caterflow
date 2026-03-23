<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php'; 

if(isset($_GET['category'])){
    $category = $_GET['category'];
    $user_id = $_SESSION['id'];
    $results = [];

    if($category == "customers"){
        $query = "SELECT type, cust_code, name, email, phone FROM customers WHERE user_id = :user_id";
    }
    if($category == "suppliers"){
        $query = "SELECT v.name AS vendor_name, p.name AS product_name, v.contact_person, v.phone
                  FROM vendors v JOIN products p ON v.product_id = p.product_id
                  WHERE v.user_id = :user_id";
    }
    if($category == "products"){
        $query = "SELECT product_code, name, price FROM products WHERE user_id = :user_id";
    }
    if($category == "orders"){
        $query = "SELECT o.invoice_number, c.name, o.delivery_date, o.total_amount, o.payment_status
                  FROM orders o JOIN customers c ON o.cust_id = c.cust_id
                  WHERE o.user_id = :user_id
                  ORDER BY o.delivery_date ASC";
    }
    if($category == "installments"){
        $query = "SELECT o.invoice_number, c.name AS customer_name, ot.due_date, ot.amount_due, ot.amount_paid, ot.status, o.payment_status
                  FROM order_tenures ot JOIN orders o ON ot.order_id = o.order_id
                  JOIN customers c ON o.cust_id = c.cust_id
                  WHERE ot.status = 'pending' AND o.user_id = :user_id
                  ORDER BY MIN(ot.due_date) OVER (PARTITION BY o.invoice_number) ASC, o.invoice_number, ot.due_date ASC";
    }

    try{
        $stmt = $pdo->prepare($query);
        $stmt->execute([':user_id' => $user_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            'success' => true,
            'results' => $results
        ]);
        exit;
    }catch(PDOException $e){
        die("Connection failed: " . $e->getMessage());
    }
}

if(isset($_GET['cust_code'])){
    $cust_code = $_GET['cust_code'];
    $user_id = $_SESSION['id'];

    try {
        $stmtInfo = $pdo->prepare(
            "SELECT c.cust_id, c.name, c.type, c.cust_code, c.phone, c.email, c.city, c.address_line,
                    COUNT(o.order_id) AS total_orders,
                    COALESCE(SUM(o.total_amount), 0) AS total_spent
             FROM customers c
             LEFT JOIN orders o ON c.cust_id = o.cust_id
             WHERE c.cust_code = :cust_code AND c.user_id = :user_id
             GROUP BY c.cust_id"
        );
        $stmtInfo->execute([':cust_code' => $cust_code, ':user_id' => $user_id]);
        $customerInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        $stmtTenures = $pdo->prepare(
            "SELECT o.invoice_number, ot.tenure_number, ot.amount_due, ot.amount_paid, ot.due_date, ot.status
             FROM order_tenures ot
             JOIN orders o ON ot.order_id = o.order_id
             WHERE o.cust_id = :cust_id AND o.user_id = :user_id AND ot.status = 'pending'
             ORDER BY ot.due_date ASC"
        );
        $stmtTenures->execute([':cust_id' => $customerInfo['cust_id'], ':user_id' => $user_id]);
        $unpaidTenures = $stmtTenures->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'info' => $customerInfo,
            'unpaid_tenures' => $unpaidTenures
        ]);
        exit;
    } catch(PDOException $e){
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}