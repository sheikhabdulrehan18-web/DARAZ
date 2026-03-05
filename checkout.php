<?php
include 'header.php';
 
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
 
// Fetch Cart items to check if empty
$stmt = $pdo->prepare("SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();
 
if (empty($items)) {
    header("Location: shop.php");
    exit();
}
 
$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process Order
    try {
        $pdo->beginTransaction();
 
        // 1. Create Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$user_id, $total]);
        $order_id = $pdo->lastInsertId();
 
        // 2. Add Order Items
        $item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            $item_stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
        }
 
        // 3. Clear Cart
        $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);
 
        $pdo->commit();
        $_SESSION['order_success'] = $order_id;
        header("Location: orders.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Failed to process order. Please try again.";
    }
}
?>
 
<div class="container" style="max-width: 900px; margin-top: 5rem;">
    <div style="background: white; padding: 3rem; border-radius: 20px; box-shadow: var(--shadow);">
        <h1 style="margin-bottom: 2rem;">Checkout</h1>
 
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
            <div>
                <h3 style="margin-bottom: 1.5rem;">Shipping Details</h3>
                <form id="checkout-form" method="POST">
                    <div class="form-group">
                        <label>Delivery Address</label>
                        <textarea class="form-control" rows="3" placeholder="Enter your full address..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" placeholder="+1 234 567 890" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <div style="border: 1px solid #eee; padding: 1rem; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                            <input type="radio" checked>
                            <span>Cash on Delivery</span>
                            <i class="fas fa-money-bill-wave" style="margin-left: auto; color: #2e7d32;"></i>
                        </div>
                    </div>
                </form>
            </div>
 
            <div style="background: #f9f9f9; padding: 2rem; border-radius: 12px; height: fit-content;">
                <h3 style="margin-bottom: 1.5rem;">Order Review</h3>
                <?php foreach ($items as $item): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem;">
                        <span style="opacity: 0.7;">Qty: <?php echo $item['quantity']; ?></span>
                        <span style="font-weight: 600;">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
                <div style="border-top: 1px solid #ddd; margin-top: 1.5rem; padding-top: 1rem; display: flex; justify-content: space-between; font-weight: 800; font-size: 1.2rem;">
                    <span>Total Amount</span>
                    <span style="color: var(--primary-color);">$<?php echo number_format($total, 2); ?></span>
                </div>
                <button type="submit" form="checkout-form" class="btn btn-primary" style="width: 100%; margin-top: 2rem; padding: 1rem;">Confirm Order</button>
            </div>
        </div>
    </div>
</div>
 
<?php include 'footer.php'; ?>
 
