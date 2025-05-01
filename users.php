<?php
include 'config.php';
session_start();
// Check admin privileges
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit("You do not have access rights");
}

// Handle delete operations
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: users.php");
    exit();
}

// Handle add and edit operations
$error = '';
$editMode = false;
$currentUser = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate data
    if (empty($username) || empty($email) || (empty($id) && empty($password))) {
        $error = 'All fields marked with * are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address';
    } else {
        // Check for duplicate email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->rowCount() > 0) {
            $error = 'Email is already in use';
        }
    }

    if (empty($error)) {
        if ($id) {
            // Update user
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, password=?, role=? WHERE id=?");
                $stmt->execute([$username, $email, $hashedPassword, $role, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
                $stmt->execute([$username, $email, $role, $id]);
            }
        } else {
            // Add new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashedPassword, $role]);
        }
        header("Location: users.php");
        exit();
    }
}

// Prepare data for edit mode
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editMode = true;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $currentUser = $stmt->fetch();
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.03); }
        .password-toggle { cursor: pointer; }
    </style>
</head>
<body class="bg-light">
    
<div class="container py-5">
    <h2 class="mb-4 text-center"><i class="fas fa-users-cog"></i> User Management</h2>

    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Add/Edit Form -->
    <div class="card mb-4 shadow">
        <div class="card-body">
            <h5 class="card-title"><?php echo $editMode ? 'Edit User' : 'Add New User'; ?></h5>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $currentUser['id'] ?? ''; ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="username" required 
                            value="<?php echo htmlspecialchars($currentUser['username'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required
                            value="<?php echo htmlspecialchars($currentUser['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Password <?php if($editMode): ?><small>(Leave blank if you do not want to change)</small><?php else: ?><span class="text-danger">*</span><?php endif; ?></label>
                        <input type="password" class="form-control" name="password" <?php echo !$editMode ? 'required' : ''; ?>>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" name="role" required>
                            <option value="user" <?php echo ($currentUser['role'] ?? '') === 'user' ? 'selected' : ''; ?>>Regular User</option>
                            <option value="admin" <?php echo ($currentUser['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> <?php echo $editMode ? 'Save Changes' : 'Add User'; ?>
                        </button>
                        <?php if($editMode): ?>
                        <a href="users.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Registration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge <?php echo $user['role'] === 'admin' ? 'bg-success' : 'bg-primary'; ?>">
                                    <?php echo $user['role']; ?>
                                </span>
                            </td>
                            <td><?php echo date('Y/m/d', strtotime($user['created_at'] ?? '')); ?></td>
                            <td>
                                <a href="users.php?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="users.php?action=delete&id=<?php echo $user['id']; ?>" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>