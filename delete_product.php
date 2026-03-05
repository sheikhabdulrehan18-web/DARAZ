<?php
session_start();
require_once 'db.php';
 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}
 
$seller_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? 0;
 
// Fetch image name to delete file
$stmt = $pdo->prepare("SELECT image FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$id, $seller_id]);
$product = $stmt->fetch();
 
if ($product) {
    if (file_exists($product['image'])) {
        unlink($product['image']);
    }
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->execute([$id, $seller_id]);
    $_SESSION['success'] = "Product deleted successfully.";
}
 
header("Location: shop.php");
exit();
?>
 
