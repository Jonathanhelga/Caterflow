<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php';

// ─── JSON endpoint: GET ?fetch_contacts=1&cust_id=X ───────────────────────────
if (isset($_GET['fetch_contacts']) && isset($_GET['cust_id'])) {
    $cust_id = (int)$_GET['cust_id'];
    $user_id = $_SESSION['id'];

    try {
        $stmtCheck = $pdo->prepare(
            "SELECT cust_id, name FROM customers
             WHERE cust_id = :cust_id AND user_id = :user_id AND type IN ('hotel', 'company')"
        );
        $stmtCheck->execute([':cust_id' => $cust_id, ':user_id' => $user_id]);
        $customer = $stmtCheck->fetch();

        if (!$customer) {
            echo json_encode(['success' => false, 'error' => 'Customer not found']);
            exit;
        }

        $stmt = $pdo->prepare(
            "SELECT contact_id, type, name, phone, notes
             FROM customer_contacts
             WHERE cust_id = :cust_id AND user_id = :user_id
             ORDER BY FIELD(type, 'Purchasing', 'Payment', 'Receiving'), name"
        );
        $stmt->execute([':cust_id' => $cust_id, ':user_id' => $user_id]);
        $contacts = $stmt->fetchAll();

        echo json_encode([
            'success'       => true,
            'contacts'      => $contacts,
            'customer_name' => $customer['name']
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// ─── POST: save new contact ────────────────────────────────────────────────────
$message     = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $cust_id = (int)($_POST['cust_id'] ?? 0);
    $type    = trim($_POST['type']         ?? '');
    $name    = trim($_POST['contact_name'] ?? '');
    $phone   = trim($_POST['phone']        ?? '');
    $notes   = trim($_POST['notes']        ?? '');
    $user_id = $_SESSION['id'];

    if (!$cust_id || empty($type) || empty($name) || empty($phone)) {
        $message     = "Error: Please select a customer and fill in all required fields.";
        $messageType = "error";
    } else {
        $stmtCheck = $pdo->prepare(
            "SELECT cust_id FROM customers
             WHERE cust_id = :cust_id AND user_id = :user_id AND type IN ('hotel', 'company')"
        );
        $stmtCheck->execute([':cust_id' => $cust_id, ':user_id' => $user_id]);

        if (!$stmtCheck->fetch()) {
            $message     = "Error: Invalid customer selected.";
            $messageType = "error";
        } else {
            try {
                $stmt = $pdo->prepare(
                    "INSERT INTO customer_contacts (cust_id, user_id, type, name, phone, notes)
                     VALUES (:cust_id, :user_id, :type, :name, :phone, :notes)"
                );
                $stmt->execute([
                    ':cust_id' => $cust_id,
                    ':user_id' => $user_id,
                    ':type'    => $type,
                    ':name'    => $name,
                    ':phone'   => $phone,
                    ':notes'   => !empty($notes) ? $notes : null,
                ]);

                $_SESSION['flash_message'] = "Contact saved successfully.";
                $_SESSION['flash_type']    = 'success';
                header("Location: customer_contact.php?selected_cust=" . $cust_id);
                exit;
            } catch (PDOException $e) {
                $message     = ($e->getCode() == 23000)
                    ? "Error: This phone number is already registered for this customer."
                    : "Error: " . $e->getMessage();
                $messageType = "error";
            }
        }
    }
}

// ─── Fetch hotel + company customers for the dropdown ─────────────────────────
$selectedCustId = (int)($_GET['selected_cust'] ?? $_POST['cust_id'] ?? 0);

try {
    $stmtCustomers = $pdo->prepare(
        "SELECT cust_id, cust_code, name, type
         FROM customers
         WHERE user_id = :user_id AND type IN ('hotel', 'company')
         ORDER BY name ASC"
    );
    $stmtCustomers->execute([':user_id' => $_SESSION['id']]);
    $customers = $stmtCustomers->fetchAll();
} catch (PDOException $e) {
    $customers = [];
}
