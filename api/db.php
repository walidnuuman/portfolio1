<?php
// Configuration for Database Connection

$host = 'localhost';
$dbname = 'portfolio_db';
$user = 'root'; // default for XAMPP
$pass = '';     // default for XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // In production, log this error securely rather than outputting to screen
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}
?>