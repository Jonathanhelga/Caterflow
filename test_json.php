<?php
// 1. Grab your database connection
require_once __DIR__ . '/Database/db_connect.php';

try {
    // 2. Write the query you want to test!
    $stmt = $pdo->query("SELECT cust_code, name, type, phone FROM customers LIMIT 3");
    
    // 3. Fetch all rows. FETCH_ASSOC makes sure it's a clean array without duplicate column numbers
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 4. Print it as JSON! (JSON_PRETTY_PRINT makes it easy to read in the terminal)
    echo json_encode($results, JSON_PRETTY_PRINT) . "\n";
    
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage() . "\n";
}


//  curl http://localhost:8080/AWS/test_json.php
    // curl yells an HTTP request at your XAMPP server.
    // The server wakes up PHP.
    // PHP knows the secret password to the database. PHP connects to MySQL using the database language (PDO).
    // MySQL runs the query and hands the raw data to PHP.
    // PHP turns that data into standard text (JSON).
    // PHP hands the JSON back to the server, which hands it back to curl!
// same as
// php test_json.php
