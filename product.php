<?php
include 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// معالجة عمليات الإضافة والحذف والتعديل
?>

<!-- جدول المنتجات مع خيارات التعديل -->