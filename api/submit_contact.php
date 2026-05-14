<?php
header('Content-Type: application/json');

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and Validate inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':subject' => $subject,
            ':message' => $message
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully.']);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save message.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>