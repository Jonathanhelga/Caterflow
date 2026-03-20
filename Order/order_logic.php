<?php
// browser-sync start --proxy "http://localhost:8080/AWS" --files "**/*.php, **/*.css, **/*.js"
session_start();
date_default_timezone_set('Asia/Singapore');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php';
$message = "";
$messageType = "";

$customers = [];
try {
    $cusStmt = $pdo->query("SELECT cust_id, cust_code, name FROM customers ORDER BY name ASC");
    $customers = $cusStmt->fetchAll();
} catch (PDOException $e) {
    $customers = [];
}

$products = [];
try {
    $prodStmt = $pdo->query("SELECT product_id, name, price FROM products ORDER BY name ASC");
    $products = $prodStmt->fetchAll();
} catch (PDOException $e) {
    $products = [];
}

function generateInvoiceNumber($pdo, $cust_code, $order_date) {
    $date_string = (new DateTime($order_date))->format("Ymd");
    try {
        $stmt = $pdo->query("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders'");
        $next_id = $stmt->fetchColumn() ?: 1;
        $orderID = str_pad($next_id, 4, "0", STR_PAD_LEFT);
    } catch (PDOException $e) { $orderID = "0000"; }

    return $cust_code . '-' . $date_string . $orderID;
}

function calculateOrderTotals($submitted_products, $submitted_quantities, $all_products){
    $total_amount = 0;
    $items = [];

    for($i = 0; $i < count($submitted_products); $i++){
        $product_id = trim($submitted_products[$i] ?? '');
        $quantity = (int)($submitted_quantities[$i] ?? 0);

        if($product_id && $quantity > 0){
            $price = 0;
            foreach($all_products as $product){
                if($product['product_id'] == $product_id){
                    $price = (int)($product['price']);
                    break;
                }
            }
            $total_amount += $quantity * $price;
            $items[] = [
                ':product_id' => $product_id,
                ':quantity' => $quantity,
                ':unit_price' => $price
            ];
        }
    }
    return ['total' => $total_amount, 'items' => $items];
}

function calculateTenures($tenures, $tenure_interval, $period_unit, $total_amount, $starting_date){
    $tenure_information = [];
    $amount_each_tenure = floor($total_amount / $tenures);
    $remainder = $total_amount - ($amount_each_tenure * $tenures);
    $number_of_days = $tenure_interval * $period_unit;
    
    $current_date = new DateTime($starting_date);
    for($i = 1; $i <= $tenures; $i++){
        $current_date->modify('+' . $number_of_days . ' days');
        $due_date = $current_date->format('Y-m-d');
        
        $amount_due = $amount_each_tenure;
        if ($i == $tenures) {
            $amount_due += $remainder;
        }

        $tenure_information[] = [
            ':tenure_number' => $i,
            ':amount_due' => $amount_due,
            ':due_date' => $due_date,
        ];
    }
    return ['tenure_information' => $tenure_information];
}

function saveOrder($pdo, $order_data, $items, $tenure_information){
    try{
        $pdo->beginTransaction();
        $sqlOrder = "INSERT INTO orders (user_id, cust_id, invoice_number, order_date, delivery_date, total_amount) 
                     VALUES (:user_id, :cust_id, :invoice_number, :order_date, :delivery_date, :total_amount)";
        $stmtOrder = $pdo->prepare($sqlOrder);
        $stmtOrder->execute($order_data);
        $order_id = $pdo->lastInsertId();

        $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, unit_price)
                    VALUES (:order_id, :product_id, :quantity, :unit_price)";
        $stmtItem = $pdo->prepare($sqlItem);
        foreach ($items as $item) {
            $stmtItem->execute(array_merge([':order_id' => $order_id], $item));
        }

        $sqlTenure = "INSERT INTO order_tenures (order_id, tenure_number, amount_due, due_date)
                      VALUES (:order_id, :tenure_number, :amount_due, :due_date)";
        $stmtTenure = $pdo->prepare($sqlTenure);
        foreach ($tenure_information as $tenure) {
            $stmtTenure->execute(array_merge([':order_id' => $order_id], $tenure));
        }      
        $pdo->commit();
        return true;
    }catch(PDOException $e){
        $pdo->rollBack();
        throw $e;
    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
    //for debugging and it shows large block of text showing exactly what PHP received from the form.
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    // exit;
    $cust_id = trim($_POST['customers'] ?? '');
    $order_date = trim($_POST['order_date'] ?? '');
    $delivery_date = trim($_POST['delivery_date'] ?? '');
    $tenures = trim($_POST['tenures'] ?? '');
    $tenure_interval = trim($_POST['tenure_interval'] ?? '1');
    $tenure_period = trim($_POST['tenure_period'] ?? '');

    $submitted_products = $_POST['products'] ?? [];
    $submitted_quantities = $_POST['quantities'] ?? [];
    
    if (empty($cust_id) || empty($tenure_period) || empty($delivery_date) || empty($submitted_products) || empty($submitted_quantities)) {
        $message = "Error: Please fill in all required fields (Delivery Date, Products, Quantity).";
        $messageType = "error";
    }
    else{
        $period_unit = 0;
        if ($tenure_period == "years"){ $period_unit = 365; }
        elseif ($tenure_period == "weeks") { $period_unit = 7; }
        elseif ($tenure_period == "months") { $period_unit = 30; }
        else { $period_unit = 1; }

        $selected_cust_code = '';
        foreach ($customers as $customer) {
            if ($customer['cust_id'] == $cust_id) {
                $selected_cust_code = $customer['cust_code'];
                break;
            }
        }
        $order_calculations = calculateOrderTotals($submitted_products, $submitted_quantities, $products);
        $invoice = generateInvoiceNumber($pdo, $selected_cust_code, $order_date);
        $paramsOrders = [
            ':user_id' => $_SESSION['id'],
            ':cust_id' => $cust_id,
            ':invoice_number' => $invoice,
            ':order_date' => $order_date,
            ':delivery_date' => $delivery_date,
            ':total_amount' => $order_calculations['total']
        ];
        $tenureArray = calculateTenures($tenures, $tenure_interval, $period_unit, $order_calculations['total'], $delivery_date);
        try{
            saveOrder($pdo, $paramsOrders, $order_calculations['items'], $tenureArray['tenure_information']);
            $_SESSION['flash_message'] = "Success! Order data has been saved.";
            $_SESSION['flash_type'] = "success";
            header('Location: order.php');
            exit;
        }catch(PDOException $e){
            $message = "Error: " . $e->getMessage();
            $messageType = "error";
        }
    }
}



// $_POST['products'][0] = First selected item
// $_POST['quantities'][0] = First selected quantity
// $_POST['products'][1] = Second selected item
// $_POST['quantities'][1] = Second selected quantity