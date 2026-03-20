<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {//impossible to occur
    http_response_code(401);
    echo json_encode(
        [
        'success' => false, 
        'error' => 'Unauthorized'
        ]
    );
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { //impossible to occur
    http_response_code(405);
    echo json_encode(
        [
        'success' => false, 
        'error' => 'Method not allowed'
        ]
    );
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php';

$name = trim($_POST['name'] ?? '');

if (empty($name)) { //impossible to occur
    echo json_encode(
        [
            'success' => false, 
            'error' => 'Category name cannot be empty.'
        ]
    );
    exit;
}

if (strlen($name) > 50) {
    echo json_encode(['success' => false, 'error' => 'Category name must be 50 characters or less.']);
    exit;
}

try {
    $check = $pdo->prepare("SELECT category_id, name FROM categories WHERE LOWER(name) = LOWER(:name)");
    $check->execute([':name' => $name]);
    $existing = $check->fetch();

    if ($existing) {

        echo json_encode([
            'success'  => true,
            'already_exists' => true,
            'category' => ['id' => $existing['category_id'], 'name' => $existing['name']]
        ]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->execute([':name' => $name]);
    $newId = $pdo->lastInsertId();

    echo json_encode([
        'success'  => true,
        'already_exists' => false,
        'category' => ['id' => $newId, 'name' => $name]
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
