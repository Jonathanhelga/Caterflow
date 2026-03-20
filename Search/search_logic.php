<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php'; 

if(isset($_GET['category'])){
    $category = $_GET['category'];
    $results = [];
    if($category == "customers"){ $query = "SELECT type, cust_code, name, email, phone FROM customers"; }
    if($category == "suppliers"){ $query = "SELECT v.name AS vendor_name, p.name AS product_name, v.contact_person, v.phone 
                                            FROM vendors v JOIN products p ON v.product_id = p.product_id"; }
    if($category == "products"){ $query = "SELECT product_code, name, price FROM products"; }
    if($category == "orders"){ $query = "SELECT o.invoice_number, c.name, o.delivery_date, o.total_amount, o.payment_status 
                                            FROM orders o JOIN customers c ON o.cust_id = c.cust_id
                                            ORDER BY o.delivery_date ASC;"; }
    if($category == "installments"){ $query = "SELECT o.invoice_number, c.name AS customer_name, ot.due_date, ot.amount_due, ot.amount_paid, ot.status, o.payment_status 
                                                FROM order_tenures ot JOIN orders o ON ot.order_id = o.order_id
                                                JOIN customers c ON o.cust_id = c.cust_id
                                                WHERE ot.status = 'pending'
                                                ORDER BY MIN(ot.due_date) OVER (PARTITION BY o.invoice_number) ASC, o.invoice_number, ot.due_date ASC"; }
    try{
        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            'success' => true,
            'results' => $results
        ]);
        exit;
    }catch(PDOexception $e){
        die("Connection failed: " . $e->getMessage());
    }
}

?>