<?php
include 'header.php';
 
if (!isLoggedIn() || getRole() !== 'seller') {
    header("Location: login.php");
    exit();
}
 
$seller_id = $_SESSION['user_id'];
 
// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    $_SESSION['status_success'] = "Order #$order_id status updated to $new_status.";
    header("Location: seller_orders.php");
    exit();
}
 
// Fetch orders that contain this seller's products
$query = "SELECT DISTINCT o.*, u.name as buyer_name 
          FROM orders o 
          JOIN users u ON o.user_id = u.id 
          JOIN order_items oi ON o.id = oi.order_id 
          JOIN products p ON oi.product_id = p.id 
          WHERE p.seller_id = ? 
          ORDER BY o.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([$seller_id]);
$orders = $stmt->fetchAll();
?>
 
<div class="container" style="margin-top: 3rem;">
    <h1 style="margin-bottom: 2rem;">Seller Orders Dashboard</h1>
 
    <?php if (isset($_SESSION['status_success'])): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            <?php echo $_SESSION['status_success']; unset($_SESSION['status_success']); ?>
        </div>
    <?php endif; ?>
 
    <?php if (empty($orders)): ?>
        <div style="background: white; padding: 5rem; text-align: center; border-radius: 20px;">
            <p style="opacity: 0.5;">No orders received yet. Keep promoting your products!</p>
        </div>
    <?php else: ?>
        <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: #f9f9f9;">
                    <tr>
                        <th style="padding: 1.5rem;">Order ID</th>
                        <th style="padding: 1.5rem;">Customer</th>
                        <th style="padding: 1.5rem;">Total</th>
                        <th style="padding: 1.5rem;">Date</th>
                        <th style="padding: 1.5rem;">Status</th>
                        <th style="padding: 1.5rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr style="border-top: 1px solid #eee;">
                            <td style="padding: 1.5rem; font-weight: 600;">#<?php echo $order['id']; ?></td>
                            <td style="padding: 1.5rem;"><?php echo htmlspecialchars($order['buyer_name']); ?></td>
                            <td style="padding: 1.5rem; font-weight: 700;">$<?php echo number_format($order['total_price'], 2); ?></td>
                            <td style="padding: 1.5rem; color: #666;"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td style="padding: 1.5rem;">
                                <form action="seller_orders.php" method="POST" style="display: flex; gap: 10px;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <input type="hidden" name="update_status" value="1">
                                    <select name="status" class="form-control" style="width: 130px; font-size: 0.8rem;" onchange="this.form.submit()">
                                        <option value="pending" <?php if($order['status']=='pending') echo 'selected'; ?>>Pending</option>
                                        <option value="processing" <?php if($order['status']=='processing') echo 'selected'; ?>>Processing</option>
                                        <option value="shipped" <?php if($order['status']=='shipped') echo 'selected'; ?>>Shipped</option>
                                        <option value="delivered" <?php if($order['status']=='delivered') echo 'selected'; ?>>Delivered</option>
                                        <option value="cancelled" <?php if($order['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td style="padding: 1.5rem;">
                                <a href="#" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.8rem; background: var(--accent-color);">Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
 
<?php include 'footer.php'; ?>
 
