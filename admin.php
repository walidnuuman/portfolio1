<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once 'api/db.php';

// Handle adding a new project
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_project') {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $project_url = filter_var($_POST['project_url'], FILTER_SANITIZE_URL);
    $image_url = '';

    // Handle File Upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/img/uploads/';
        $file_name = time() . '_' . basename($_FILES['image_file']['name']);
        $target_path = $upload_dir . $file_name;
        
        // Ensure folder exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_path)) {
            $image_url = $target_path;
        } else {
            $message = '<div class="alert alert-error">Failed to upload image.</div>';
        }
    }
    
    if(!empty($title) && !empty($description) && empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO projects (title, description, image_url, project_url) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $image_url, $project_url]);
            $message = '<div class="alert alert-success">Project added successfully!</div>';
        } catch(PDOException $e) {
            $message = '<div class="alert alert-error">Error adding project.</div>';
        }
    } else {
        $message = '<div class="alert alert-error">Title and description are required.</div>';
    }
}

// Handle deleting a project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_project') {
    $project_id = filter_var($_POST['project_id'], FILTER_VALIDATE_INT);
    if ($project_id) {
        try {
            // Optional: fetch image url and delete file
            $stmt_img = $pdo->prepare("SELECT image_url FROM projects WHERE id = ?");
            $stmt_img->execute([$project_id]);
            $proj = $stmt_img->fetch();
            if ($proj && !empty($proj['image_url']) && file_exists($proj['image_url'])) {
                unlink($proj['image_url']);
            }

            $stmt_del = $pdo->prepare("DELETE FROM projects WHERE id = ?");
            $stmt_del->execute([$project_id]);
            $message = '<div class="alert alert-success">Project deleted successfully!</div>';
        } catch(PDOException $e) {
            $message = '<div class="alert alert-error">Error deleting project.</div>';
        }
    }
}

// Handle updating a project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_project') {
    $project_id = filter_var($_POST['project_id'], FILTER_VALIDATE_INT);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $project_url = filter_var($_POST['project_url'], FILTER_SANITIZE_URL);

    if ($project_id && !empty($title) && !empty($description)) {
        try {
            // Include image update if a new one is uploaded
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'assets/img/uploads/';
                $file_name = time() . '_' . basename($_FILES['image_file']['name']);
                $target_path = $upload_dir . $file_name;
                
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_path)) {
                    // Delete old image
                    $stmt_old = $pdo->prepare("SELECT image_url FROM projects WHERE id = ?");
                    $stmt_old->execute([$project_id]);
                    $old_proj = $stmt_old->fetch();
                    if ($old_proj && !empty($old_proj['image_url']) && file_exists($old_proj['image_url'])) {
                        unlink($old_proj['image_url']);
                    }
                    
                    $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, image_url = ?, project_url = ? WHERE id = ?");
                    $stmt->execute([$title, $description, $target_path, $project_url, $project_id]);
                } else {
                    $message = '<div class="alert alert-error">Failed to upload new image.</div>';
                }
            } else {
                // Update without image changes
                $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, project_url = ? WHERE id = ?");
                $stmt->execute([$title, $description, $project_url, $project_id]);
            }
            if(empty($message)) $message = '<div class="alert alert-success">Project updated successfully!</div>';
        } catch(PDOException $e) {
            $message = '<div class="alert alert-error">Error updating project.</div>';
        }
    } else {
        $message = '<div class="alert alert-error">Title and description are required.</div>';
    }
}

// Check for edit request
$edit_project = null;
if (isset($_GET['edit_project_id'])) {
    $edit_id = filter_var($_GET['edit_project_id'], FILTER_VALIDATE_INT);
    if ($edit_id) {
        $stmt_edit = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt_edit->execute([$edit_id]);
        $edit_project = $stmt_edit->fetch();
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Fetch all messages
$stmt_msg = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$contact_messages = $stmt_msg->fetchAll();

// Fetch all projects
$stmt_proj = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt_proj->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--card-bg);
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        .admin-content {
            padding: 40px 20px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .admin-card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
        }
        th {
            background-color: var(--bg-light);
        }
    </style>
</head>
<body style="padding-top: 0; background-color: var(--bg-light);">

    <div class="admin-header">
        <h2>Dashboard</h2>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            <a href="?logout=1" class="btn btn-secondary" style="margin-left: 15px;">Logout</a>
            <a href="index.php" class="btn btn-primary" target="_blank" style="margin-left: 5px;">View Site</a>
        </div>
    </div>
    
    <div class="admin-content">
        <?php echo $message; ?>
        
        <?php if(isset($_COOKIE['last_login'])): ?>
            <p style="margin-bottom: 20px; color: var(--text-secondary);">Last login recorded: <?php echo htmlspecialchars($_COOKIE['last_login']); ?></p>
        <?php endif; ?>

        <?php if ($edit_project): ?>
        <!-- Edit Project Form -->
        <div class="admin-card">
            <h3>Edit Project</h3>
            <form method="POST" action="admin.php" style="margin-top: 15px;" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_project">
                <input type="hidden" name="project_id" value="<?php echo $edit_project['id']; ?>">
                <div class="form-group">
                    <label>Project Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($edit_project['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required><?php echo htmlspecialchars($edit_project['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Upload New Project Image (Optional)</label>
                    <input type="file" name="image_file" accept="image/*">
                    <?php if(!empty($edit_project['image_url'])): ?>
                        <small>Current Image: <a href="<?php echo htmlspecialchars($edit_project['image_url']); ?>" target="_blank">View image</a></small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Project Live URL</label>
                    <input type="text" name="project_url" value="<?php echo htmlspecialchars($edit_project['project_url'] ?? ''); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Project</button>
                <a href="admin.php" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
            </form>
        </div>
        <?php else: ?>
        <!-- Add Project Form -->
        <div class="admin-card">
            <h3>Add New Project</h3>
            <form method="POST" action="admin.php" style="margin-top: 15px;" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_project">
                <div class="form-group">
                    <label>Project Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Upload Project Image</label>
                    <input type="file" name="image_file" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Project Live URL</label>
                    <input type="text" name="project_url">
                </div>
                <button type="submit" class="btn btn-primary">Add Project</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Manage Projects Table -->
        <div class="admin-card">
            <h3>Manage Projects</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($projects as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['title']); ?></td>
                            <td><?php echo htmlspecialchars($p['created_at']); ?></td>
                            <td>
                                <a href="admin.php?edit_project_id=<?php echo $p['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.9em; margin-right: 5px;">Edit</a>
                                <form method="POST" action="admin.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                    <input type="hidden" name="action" value="delete_project">
                                    <input type="hidden" name="project_id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" class="btn btn-secondary" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(count($projects) == 0): ?>
                        <tr><td colspan="3">No projects found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Messages Table -->
        <div class="admin-card">
            <h3>Contact Messages</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($contact_messages as $msg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($msg['name']); ?></td>
                            <td><?php echo htmlspecialchars($msg['email']); ?></td>
                            <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                            <td><?php echo htmlspecialchars($msg['message']); ?></td>
                            <td><?php echo htmlspecialchars($msg['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(count($contact_messages) == 0): ?>
                        <tr><td colspan="5">No messages yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>