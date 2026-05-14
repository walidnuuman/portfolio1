<?php
session_start();

// If already logged in, redirect to admin panel
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit;
}

$error = '';
require_once 'api/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Check credentials (admin / password123)
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password'])) {
        // Successful login
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        
        // Simple cookie to remember last login time
        setcookie("last_login", date("Y-m-d H:i:s"), time() + (86400 * 30), "/"); // 30 days
        
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-light);
        }
        .login-card {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .error-alert {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">Admin Login</h2>
            <?php if(!empty($error)): ?>
                <div class="error-alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                <div style="text-align: center; margin-top: 15px;">
                    <a href="index.php" style="color: var(--text-secondary);">&larr; Back to Site</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>