<?php
include 'config.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';
$edit_mode = false;
$current_product = null;

// Handle deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $success = 'Product deleted successfully';
}

// Handle addition/editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $product_name = trim($_POST['product_name']);
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);
    
    // Handle image upload
    $image = $_POST['existing_image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $target_file;
        } else {
            $error = 'An error occurred while uploading the image';
        }
    }

    if (empty($error)) {
        if ($id) {
            // Update product
            $stmt = $pdo->prepare("UPDATE products SET product_name=?, price=?, description=?, image=? WHERE id=?");
            $stmt->execute([$product_name, $price, $description, $image, $id]);
            $success = 'Product updated successfully';
        } else {
            // Add new product
            $stmt = $pdo->prepare("INSERT INTO products (product_name, price, description, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$product_name, $price, $description, $image]);
            $success = 'Product added successfully';
        }
    }
}

// Prepare data for edit mode
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_mode = true;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $current_product = $stmt->fetch();
}

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    
<div class="container py-5">
    <h2 class="mb-4 text-center"><i class="fas fa-box-open"></i> Product Control Panel</h2>

    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Management Form -->
    <div class="card mb-4 shadow">
        <div class="card-body">
            <h5 class="card-title"><?php echo $edit_mode ? 'Edit Product' : 'Add New Product'; ?></h5>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $current_product['id'] ?? ''; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $current_product['image'] ?? ''; ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="product_name" required 
                            value="<?php echo htmlspecialchars($current_product['product_name'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Price <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="price" required
                            value="<?php echo htmlspecialchars($current_product['price'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"><?php 
                            echo htmlspecialchars($current_product['description'] ?? ''); 
                        ?></textarea>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Product Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <?php if ($edit_mode && !empty($current_product['image'])): ?>
                            <div class="mt-2">
                                <img src="<?php echo $current_product['image']; ?>" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> <?php echo $edit_mode ? 'Save Changes' : 'Add Product'; ?>
                        </button>
                        <?php if($edit_mode): ?>
                        <a href="product_controll.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Product List -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <?php if (!empty($product['image'])): ?>
                                <img src="<?php echo $product['image']; ?>" class="img-thumbnail" style="max-width: 80px;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo number_format($product['price'], 2); ?> $</td>
                            <td>
                                <a href="product_controll.php?action=edit&id=<?php echo $product['id']; ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="product_controll.php?action=delete&id=<?php echo $product['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this product?')">
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