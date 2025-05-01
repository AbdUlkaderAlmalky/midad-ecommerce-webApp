<?php 
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almalky Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .product-img {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-store"></i> Almalky Store
        </a>
        
        <div class="navbar-nav">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="nav-link text-white">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                </span>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <a class="nav-link" href="product_controll.php">Manage Products</a>
                <?php endif; ?>
                <?php if($_SESSION['role'] === 'admin'): ?>
                    <a class="nav-link" href="users.php">Manage Users</a>
                <?php endif; ?>
                <a class="nav-link" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="nav-link" href="login.php">Login</a>
                <a class="nav-link" href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-4">Latest Games</h1>
    
    <div class="row g-4">
        <?php foreach ($products as $product): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow">
                <?php if(!empty($product['image'])): ?>
                    <img src="<?php echo $product['image']; ?>" class="card-img-top product-img" alt="<?php echo $product['product_name']; ?>">
                <?php else: ?>
                    <div class="product-img bg-secondary d-flex align-items-center justify-content-center">
                        <i class="fas fa-image fa-3x text-white"></i>
                    </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                    <p class="card-text text-muted"><?php echo substr($product['description'], 0, 50); ?>...</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 text-primary">$<?php echo number_format($product['price'], 2); ?></span>
                        <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($products)): ?>
        <div class="col-12 text-center py-5">
            <div class="alert alert-info">
                No products available
                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="product_controll.php" class="alert-link">Add New Product</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">© 2025 Almalky Store. All Rights Reserved</p>
        <div class="mt-2">
            <a href="https://www.facebook.com/abdo.malky.31" class="text-white mx-2"><i class="fab fa-facebook"></i></a>
            <a href="https://www.instagram.com/3bdulkader_almalky/" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>