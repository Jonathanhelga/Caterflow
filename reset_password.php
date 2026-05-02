<?php
require_once __DIR__ . '/Database/db_connect.php';

$username = 'Shardjo888';
$new_password = 'TempPass123';

$hash = password_hash($new_password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("UPDATE User_List SET password_hash = :h WHERE username = :u");
$stmt->execute(['h' => $hash, 'u' => $username]);

echo $stmt->rowCount() === 1
    ? "Password reset OK for '$username'. New password: $new_password"
    : "User '$username' not found.";
