<?php
header('Content-Type: application/json');

require_once 'db.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM projects ORDER BY created_at DESC");
    $stmt->execute();
    $projects = $stmt->fetchAll();
    
    echo json_encode($projects);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch projects']);
}
?>