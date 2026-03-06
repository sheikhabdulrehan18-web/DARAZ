<?php
session_start();
require_once 'db.php';
 
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
 
function getRole() {
    return $_SESSION['role'] ?? null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daraz Clone | Modern E-commerce</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">DARAZ</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="shop.php">Shop</a>
            <?php if (isLoggedIn()): ?>
                <?php if (getRole() === 'seller'): ?>
                    <a href="add_product.php">Add Product</a>
                    <a href="seller_orders.php">Seller Orders</a>
                <?php else: ?>
                    <a href="cart.php">Cart</a>
                    <a href="orders.php">My Orders</a>
                <?php endif; ?>
                <a href="profile.php">Profile</a>
                <a href="logout.php" class="btn btn-primary" style="padding: 0.4rem 1rem;">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>
    <div id="content">
 
