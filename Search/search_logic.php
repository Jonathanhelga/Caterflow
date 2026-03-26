<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php';

function markOverdueTenures(PDO $pdo, int $user_id): void {
    // Mark individual tenures as overdue if due_date has passed and not yet paid
    $pdo->prepare(
        "UPDATE order_tenures ot
         JOIN orders o ON ot.order_id = o.order_id
         SET ot.status = 'overdue'
         WHERE o.user_id = :user_id
           AND ot.due_date < CURDATE()
           AND ot.status NOT IN ('paid', 'overdue')"
    )->execute([':user_id' => $user_id]);

    // Propagate overdue to the parent order if any tenure is overdue and order isn't fully paid
    $pdo->prepare(
        "UPDATE orders o
         SET o.payment_status = 'overdue'
         WHERE o.user_id = :user_id
           AND o.payment_status != 'paid'
           AND EXISTS (
               SELECT 1 FROM order_tenures ot
               WHERE ot.order_id = o.order_id AND ot.status = 'overdue'
           )"
    )->execute([':user_id' => $user_id]);
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_tenure_status'){
    $tenure_id      = (int)$_POST['tenure_id'];
    $amount_received = (float)$_POST['amount_paid'];
    $user_id        = $_SESSION['id'];

    try{
        $pdo->beginTransaction();

        // Fetch tenure — join orders to enforce user ownership
        $stmtTenure = $pdo->prepare(
            "SELECT ot.amount_due, ot.order_id
             FROM order_tenures ot
             JOIN orders o ON ot.order_id = o.order_id
             WHERE ot.tenure_id = :tenure_id AND o.user_id = :user_id"
        );
        $stmtTenure->execute([':tenure_id' => $tenure_id, ':user_id' => $user_id]);
        $tenureInfo = $stmtTenure->fetch(PDO::FETCH_ASSOC);

        if(!$tenureInfo){
            echo json_encode(['success' => false, 'error' => 'Tenure not found']);
            exit;
        }

        // Reject overpayment
        if($amount_received > $tenureInfo['amount_due']){
            echo json_encode(['success' => false, 'error' => 'Amount exceeds the due amount of ' . $tenureInfo['amount_due']]);
            exit;
        }

        // Determine tenure status
        if($amount_received == $tenureInfo['amount_due']){
            $status    = 'paid';
            $paid_date = date('Y-m-d');
        } elseif($amount_received > 0){
            $status    = 'partial';
            $paid_date = null;
        } else {
            $status    = 'pending';
            $paid_date = null;
        }

        // Update the tenure row
        $stmtUpdate = $pdo->prepare(
            "UPDATE order_tenures
             SET status = :status, amount_paid = :amount_paid, paid_date = :paid_date
             WHERE tenure_id = :tenure_id"
        );
        $stmtUpdate->execute([
            ':status'      => $status,
            ':amount_paid' => $amount_received,
            ':paid_date'   => $paid_date,
            ':tenure_id'   => $tenure_id
        ]);

        // Recalculate order payment_status from all tenures
        $order_id = $tenureInfo['order_id'];
        $stmtStatuses = $pdo->prepare(
            "SELECT status FROM order_tenures WHERE order_id = :order_id"
        );
        $stmtStatuses->execute([':order_id' => $order_id]);
        $allStatuses = $stmtStatuses->fetchAll(PDO::FETCH_COLUMN);

        $paidCount = count(array_filter($allStatuses, fn($s) => $s === 'paid'));
        if($paidCount === count($allStatuses)){ $orderPaymentStatus = 'paid'; } 
        elseif(in_array('overdue', $allStatuses)){ $orderPaymentStatus = 'overdue'; } 
        elseif(in_array('paid', $allStatuses) || in_array('partial', $allStatuses)){ $orderPaymentStatus = 'partial'; } 
        else { $orderPaymentStatus = 'pending'; }

        // Update orders.payment_status and orders.total_paid in one query
        $stmtOrder = $pdo->prepare(
            "UPDATE orders o
             JOIN (
                 SELECT order_id, SUM(amount_paid) AS total
                 FROM order_tenures
                 WHERE order_id = :order_id_sub
                 GROUP BY order_id
             ) t ON o.order_id = t.order_id
             SET o.payment_status = :payment_status, o.total_paid = t.total
             WHERE o.order_id = :order_id AND o.user_id = :user_id"
        );
        $stmtOrder->execute([
            ':order_id_sub'    => $order_id,
            ':payment_status'  => $orderPaymentStatus,
            ':order_id'        => $order_id,
            ':user_id'         => $user_id
        ]);

        $pdo->commit();
        echo json_encode([
            'success'          => true,
            'new_status'       => $status,
            'new_order_status' => $orderPaymentStatus
        ]);
        exit;
    }catch(PDOException $e){
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_order_status'){
    $order_id = $_POST['order_id'];
    $status   = $_POST['status'];
    $user_id  = $_SESSION['id'];

    $allowed = ['pending', 'processing', 'completed', 'cancelled'];
    if(!in_array($status, $allowed)){
        echo json_encode(['success' => false, 'error' => 'Invalid status']);
        exit;
    }

    try{
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id AND user_id = :user_id");
        $stmt->execute([':status' => $status, ':order_id' => $order_id, ':user_id' => $user_id]);
        echo json_encode(['success' => true]);
        exit;
    }catch(PDOException $e){
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

if(isset($_GET['category'])){
    $category = $_GET['category'];
    $user_id = $_SESSION['id'];
    $results = [];

    if($category == "customers"){
        $query = "SELECT cust_id, type, cust_code, name, email, phone FROM customers WHERE user_id = :user_id";
    }
    if($category == "suppliers"){
        $query = "SELECT v.vendor_id, v.name AS vendor_name, p.name AS product_name, v.contact_person, v.phone
                  FROM vendors v JOIN products p ON v.product_id = p.product_id
                  WHERE v.user_id = :user_id";
    }
    if($category == "products"){
        $query = "SELECT product_id, product_code, name, price, cost FROM products WHERE user_id = :user_id";
    }
    if($category == "orders"){
        markOverdueTenures($pdo, $user_id);
        $query = "SELECT o.order_id, o.invoice_number, c.name, o.delivery_date, o.total_amount, o.payment_status
                  FROM orders o JOIN customers c ON o.cust_id = c.cust_id
                  WHERE o.user_id = :user_id
                  ORDER BY o.delivery_date ASC";
    }
    if($category == "installments"){
        markOverdueTenures($pdo, $user_id);
        $query= "SELECT o.invoice_number, c.name AS customer_name, ot.due_date, ot.amount_due, ot.amount_paid, ot.status, o.order_id, ot.tenure_id
                  FROM order_tenures ot JOIN orders o ON ot.order_id = o.order_id
                  JOIN customers c ON o.cust_id = c.cust_id
                  WHERE (o.payment_status = 'pending' OR o.payment_status = 'partial') AND o.user_id = :user_id
                  ORDER BY MIN(CASE WHEN ot.status != 'paid' THEN ot.due_date END) OVER (PARTITION BY o.invoice_number) ASC, o.invoice_number, ot.due_date ASC";
    }
            // $query= "SELECT o.invoice_number, c.name AS customer_name, ot.due_date, ot.amount_due, ot.amount_paid, ot.status, o.order_id
            //       FROM order_tenures ot
            //       JOIN orders o ON ot.order_id = o.order_id
            //       JOIN customers c ON o.cust_id = c.cust_id
            //       JOIN (
            //           SELECT order_id, MIN(due_date) AS min_unpaid_date
            //           FROM order_tenures
            //           WHERE status != 'paid'
            //           GROUP BY order_id
            //       ) unpaid ON o.order_id = unpaid.order_id
            //       WHERE (o.payment_status = 'pending' OR o.payment_status = 'partial') AND o.user_id = :user_id
            //       ORDER BY unpaid.min_unpaid_date ASC, o.invoice_number, ot.due_date ASC";

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

if(isset($_GET['cust_id'])){
    $cust_id = $_GET['cust_id'];
    $user_id = $_SESSION['id'];
    markOverdueTenures($pdo, $user_id);

    try {
        $stmtInfo = $pdo->prepare(
            "SELECT c.cust_id, c.name, c.type, c.cust_code, c.phone, c.email, c.city, c.address_line,
                    COUNT(o.order_id) AS total_orders,
                    COALESCE(SUM(o.total_amount), 0) AS total_spent
             FROM customers c
             LEFT JOIN orders o ON c.cust_id = o.cust_id
             WHERE c.cust_id = :cust_id AND c.user_id = :user_id
             GROUP BY c.cust_id"
        );
        $stmtInfo->execute([':cust_id' => $cust_id, ':user_id' => $user_id]);
        $customerInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if (!$customerInfo) {
            echo json_encode(['success' => false, 'error' => 'Customer not found']);
            exit;
        }

        $stmtTenures = $pdo->prepare(
            "SELECT o.invoice_number, ot.tenure_number, ot.amount_due, ot.amount_paid, ot.due_date, ot.status
             FROM order_tenures ot
             JOIN orders o ON ot.order_id = o.order_id
             WHERE o.cust_id = :cust_id AND o.user_id = :user_id AND ot.status != 'paid'
             ORDER BY ot.due_date ASC"
        );
        $stmtTenures->execute([':cust_id' => $cust_id, ':user_id' => $user_id]);
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

if(isset($_GET['vendor_id'])){
    $vendor_id = $_GET['vendor_id'];
    $user_id = $_SESSION['id'];

    try {
        $stmtInfo = $pdo->prepare(
            "SELECT v.vendor_code, v.name, p.name AS product_name, p.cost, v.contact_person, v.phone
            FROM vendors v 
            JOIN products p ON v.product_id = p.product_id
            WHERE v.vendor_id = :vendor_id AND v.user_id = :user_id"
        );
        $stmtInfo->execute([':vendor_id' => $vendor_id, ':user_id' => $user_id]);
        $vendorInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if (!$vendorInfo) {
            echo json_encode(['success' => false, 'error' => 'Vendor not found']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'info' => $vendorInfo,
        ]);
        exit;
    } catch(PDOException $e){
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $user_id = $_SESSION['id'];
    try{
        $stmtInfo = $pdo->prepare(
           "SELECT p.product_id, p.product_code, p.name AS product_name,
                   c.name AS category_name, p.source, p.price, p.cost
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.category_id
            WHERE p.product_id = :product_id AND p.user_id = :user_id"
        );
        $stmtInfo->execute([':product_id' => $product_id, ':user_id' => $user_id]);
        $productInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if (!$productInfo) {
            echo json_encode(['success' => false, 'error' => 'Product not found']);
            exit;
        }

        $stmtProductSold = $pdo->prepare(
            "SELECT o.invoice_number, c.name AS customer_name, o.order_date,
                    oi.quantity, oi.unit_price, oi.subtotal
             FROM order_items oi
             JOIN orders o ON oi.order_id = o.order_id
             JOIN customers c ON o.cust_id = c.cust_id
             WHERE oi.product_id = :product_id AND o.user_id = :user_id
             ORDER BY o.order_date DESC"
        );
        $stmtProductSold->execute([':product_id' => $product_id, ':user_id' => $user_id]);
        $productSold = $stmtProductSold->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'info' => $productInfo,
            'product_sold' => $productSold
        ]);
        exit;
    }catch(PDOException $e){
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

if(isset($_GET['order_id'])){
    $order_id = $_GET['order_id'];
    $user_id = $_SESSION['id'];

    try{
        $stmtInfo = $pdo->prepare(
            "SELECT o.invoice_number, o.order_date, o.delivery_date, o.status, o.payment_status, o.total_amount,
                    c.name AS customer_name
             FROM orders o
             JOIN customers c ON o.cust_id = c.cust_id
             WHERE o.order_id = :order_id AND o.user_id = :user_id"
        );
        $stmtInfo->execute([':order_id' => $order_id, ':user_id' => $user_id]);
        $orderInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if (!$orderInfo) {
            echo json_encode(['success' => false, 'error' => 'Order not found']);
            exit;
        }

        $stmtProducts = $pdo->prepare(
                        "SELECT p.name AS product_name, oi.quantity, oi.unit_price, oi.subtotal
                        FROM order_items oi
                        JOIN products p ON oi.product_id = p.product_id
                        WHERE oi.order_id = :order_id AND p.user_id = :user_id"
        );
        $stmtProducts->execute([':order_id' => $order_id, ':user_id' => $user_id]);
        $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

        if (!$products) {
            echo json_encode(['success' => false, 'error' => 'products ordered not found']);
            exit;
        }

        $stmtTenures = $pdo->prepare(
            "SELECT
                COUNT(*) AS total_tenures,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS paid_tenures,
                SUM(CASE WHEN status != 'paid' THEN 1 ELSE 0 END) AS unpaid_tenures
             FROM order_tenures
             WHERE order_id = :order_id"
        );
        $stmtTenures->execute([':order_id' => $order_id]);
        $tenureSummary = $stmtTenures->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'info' => $orderInfo,
            'products' => $products,
            'tenure_summary' => $tenureSummary
        ]);
        exit;
    }catch(PDOException $e){
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

if(isset($_GET['tenure_order_id'])){
    $order_id = $_GET['tenure_order_id'];
    $user_id = $_SESSION['id'];
    markOverdueTenures($pdo, $user_id);

    try{
        $stmtInfo = $pdo->prepare(
            "SELECT o.invoice_number, c.name AS customer_name
            FROM orders o 
            JOIN customers c ON o.cust_id = c.cust_id
            WHERE o.order_id = :order_id AND o.user_id = :user_id"
        );
        $stmtInfo->execute([':order_id' => $order_id, ':user_id' => $user_id]);
        $orderInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

        if (!$orderInfo) {
            echo json_encode(['success' => false, 'error' => 'Order not found']);
            exit;
        }

        $stmtTenures= $pdo->prepare(
            "SELECT tenure_id, due_date, tenure_number, amount_due, amount_paid, status
            FROM order_tenures
            WHERE order_id = :order_id"
        );
        $stmtTenures->execute([':order_id' => $order_id]);
        $tenures = $stmtTenures->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'info' => $orderInfo,
            'tenures' => $tenures,
        ]);
        exit;
    }catch(PDOException $e){
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Rewrote update_tenure_status to accept amount_paid instead of a raw                                                                                                              
//     - status value; status (pending/partial/paid) is now derived server-side from the amount received vs. amount due                                                                                                                                          
//     - Order total_paid and payment_status are recalculated in a transaction whenever a tenure is updated                                                                                                                                         
//     - Implemented the installmentDetailRequest panel: fetches tenure rows per order and renders editable tenure cards with a Save button                                                                                                                                                                                                                            
//     - Added tenure_id to the installments query and GROUPED_COLS so row grouping and table-row sync work correctly                                                                                                                                                                                                                                                    
//     - Added CSS for tenure cards, badge variants, input fields, and save button                                                                                                        