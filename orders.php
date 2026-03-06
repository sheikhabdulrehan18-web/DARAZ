<?php
include 'header.php';
 
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
 
// Show success message if redirected from checkout
$success_order = $_SESSION['order_success'] ?? null;
unset($_SESSION['order_success']);
 
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
 
<div class="container" style="margin-top: 3rem;">
    <h1 style="margin-bottom: 2rem;">My Orders</h1>
 
    <?php if ($success_order): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-circle-check" style="font-size: 2rem;"></i>
            <div>
                <h4 style="margin: 0;">Order Placed Successfully!</h4>
                <p style="margin: 0; opacity: 0.8;">Your Order ID is #<?php echo $success_order; ?>. We'll update you soon.</p>
            </div>
        </div>
    <?php endif; ?>
 
    <?php if (empty($orders)): ?>
        <div style="background: white; padding: 5rem; text-align: center; border-radius: 20px;">
            <p style="opacity: 0.5;">You haven't placed any orders yet.</p>
            <a href="shop.php" class="btn btn-primary" style="margin-top: 1rem;">Shop Products</a>
        </div>
    <?php else: ?>
        <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: #f9f9f9;">
                    <tr>
                        <th style="padding: 1.5rem;">Order ID</th>
                        <th style="padding: 1.5rem;">Date</th>
                        <th style="padding: 1.5rem;">Total</th>
                        <th style="padding: 1.5rem;">Status</th>
                        <th style="padding: 1.5rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr style="border-top: 1px solid #eee;">
                            <td style="padding: 1.5rem; font-weight: 600;">#<?php echo $order['id']; ?></td>
                            <td style="padding: 1.5rem; color: #666;"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td style="padding: 1.5rem; font-weight: 700;">$<?php echo number_format($order['total_price'], 2); ?></td>
                            <td style="padding: 1.5rem;">
                                <span style="padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; 
                                    <?php 
                                    if ($order['status'] == 'pending') echo 'background: #fff3e0; color: #ef6c00;';
                                    elseif ($order['status'] == 'shipped') echo 'background: #e3f2fd; color: #1565c0;';
                                    elseif ($order['status'] == 'delivered') echo 'background: #e8f5e9; color: #2e7d32;';
                                    else echo 'background: #eceff1; color: #455a64;';
                                    ?>">
                                    <?php echo $order['status']; ?>
                                </span>
                            </td>
                            <td style="padding: 1.5rem;">
                                <button class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.8rem; opacity: 0.7; cursor: not-allowed;">Track</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
 
<?php include 'footer.php'; ?>
 
