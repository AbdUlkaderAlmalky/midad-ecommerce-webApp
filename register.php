<?php 
include 'config.php';
// Error: session_start() is duplicated (exists in config.php)
session_start(); // Remove this line

function validateInput($username, $email, $password) {
    // ...
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $errors = validateInput($username, $email, $password);

    if (empty($errors)) {
        // Error: No check for duplicate email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Email already exists";
        } else {
            // Error: No storage in the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashedPassword]);
            
            // Error: No redirection after registration
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Create Account - Online Store</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .register-card {
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .password-strength {
            height: 3px;
            transition: all 0.3s;
        }
        .bg-auth {
            background: linear-gradient(45deg, #f8f9fa 0%, #e9ecef 100%);
        }
    </style>
</head>
<body class="bg-auth">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-store"></i> Online Store
        </a>
        <div class="navbar-nav">
            <a class="nav-link" href="login.php">Already have an account? Log in</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-7 col-lg-6">
            <div class="card register-card">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">
                        <i class="fas fa-user-plus"></i> Create a New Account
                    </h2>

                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php foreach($errors as $error): ?>
                                <div><?php echo $error; ?></div>
                            <?php endforeach; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <!-- Username Input -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="username" 
                                    name="username" 
                                    required
                                    placeholder="Enter your full name"
                                    value="<?php echo $_POST['username'] ?? '' ?>"
                                >
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    id="email" 
                                    name="email" 
                                    required
                                    placeholder="example@domain.com"
                                    value="<?php echo $_POST['email'] ?? '' ?>"
                                >
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    required
                                    placeholder="More than 6 characters"
                                    minlength="6"
                                >
                            </div>
                            <div class="password-strength mt-2">
                                <div class="progress" style="height: 3px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-user-plus me-2"></i> Register Account
                        </button>

                        <!-- Login Link -->
                        <div class="text-center mt-4">
                            Already have an account? 
                            <a href="login.php" class="text-decoration-none">Log in now</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-auto">
    <div class="container text-center">
        <p class="mb-0">All rights reserved © Our Online Store 2023</p>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>