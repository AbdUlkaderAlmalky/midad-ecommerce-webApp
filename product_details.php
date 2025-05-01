<?php
include 'config.php';
session_start();
// Check if the product ID exists in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Fetch product data from the database
$product_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

// If the product is not found
if (!$product) {
    header("HTTP/1.0 404 Not Found");
    exit('Product not found');
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Product Details</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .product-gallery {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .product-price {
            font-size: 2rem;
            color: #198754;
            font-weight: bold;
        }
        .product-description {
            line-height: 2;
            font-size: 1.1rem;
        }
    </style>
</head>
<body class="bg-light">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-store"></i> Online Store
        </a>
        <div class="navbar-nav">
            <a class="nav-link" href="index.php"><i class="fas fa-arrow-right"></i> Back to Products</a>
        </div>
    </div>
</nav>

<!-- Product Details Section -->
<div class="container my-5">
    <div class="row g-5">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="product-gallery">
                <?php if(!empty($product['image'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                         class="img-fluid rounded-3" 
                         alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                <?php else: ?>
                    <div class="bg-secondary text-center p-5">
                        <i class="fas fa-image fa-5x text-white"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <h1 class="mb-3"><?php echo htmlspecialchars($product['product_name']); ?></h1>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="product-price">
                    <?php echo number_format($product['price'], 2); ?> $
                </span>
                <span class="badge bg-primary">
                    <i class="fas fa-tag"></i> <?php echo $product['category'] ?? 'General'; ?>
                </span>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2"></i>Detailed Description</h5>
                    <p class="product-description card-text">
                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                    </p>
                </div>
            </div>

            <div class="d-grid gap-3">
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="product_controll.php?action=edit&id=<?php echo $product['id']; ?>" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-edit"></i> Edit Product
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<section class="container mb-5">
    <h4 class="mb-4">Related Products</h4>
    <div class="row g-4">
        <?php 
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id != ? ORDER BY RAND() LIMIT 4");
        $stmt->execute([$product_id]);
        $related_products = $stmt->fetchAll();
        
        foreach ($related_products as $related): ?>
        <div class="col-md-3">
            <div class="card h-100">
                <img src="<?php echo htmlspecialchars($related['image']); ?>" 
                     class="card-img-top" 
                     style="height: 200px; object-fit: cover;" 
                     alt="<?php echo htmlspecialchars($related['product_name']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($related['product_name']); ?></h5>
                    <p class="card-text text-primary">
                        <?php echo number_format($related['price'], 2); ?> $
                    </p>
                    <a href="product_details.php?id=<?php echo $related['id']; ?>" class="btn btn-outline-primary w-100">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p class="mb-0">All rights reserved © Our Online Store 2023</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>