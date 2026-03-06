<?php
include 'header.php';
 
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
 
<div class="container" style="max-width: 800px; margin-top: 5rem;">
    <div style="background: white; padding: 3rem; border-radius: 20px; box-shadow: var(--shadow);">
        <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 3rem; border-bottom: 1px solid #eee; padding-bottom: 2rem;">
            <div style="width: 100px; height: 100px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; font-weight: 700;">
                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
            </div>
            <div>
                <h1 style="font-size: 2rem; margin-bottom: 0.2rem;"><?php echo htmlspecialchars($user['name']); ?></h1>
                <p style="opacity: 0.6;"><?php echo htmlspecialchars($user['email']); ?></p>
                <span style="display: inline-block; padding: 0.3rem 1rem; background: #fff3e0; color: #f57c00; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-top: 0.5rem; text-transform: uppercase;">
                    <?php echo $user['role']; ?>
                </span>
            </div>
        </div>
 
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 12px;">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem;"><i class="fas fa-shopping-bag" style="color: var(--primary-color); margin-right: 10px;"></i> Recent Orders</h3>
                <p style="opacity: 0.6; font-size: 0.9rem;">View and track your previous purchases.</p>
                <a href="<?php echo $user['role'] === 'seller' ? 'seller_orders.php' : 'orders.php'; ?>" class="btn btn-primary" style="margin-top: 1rem; font-size: 0.8rem;">View Orders</a>
            </div>
            <div style="background: #f9f9f9; padding: 1.5rem; border-radius: 12px;">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem;"><i class="fas fa-shield-alt" style="color: var(--primary-color); margin-right: 10px;"></i> Security</h3>
                <p style="opacity: 0.6; font-size: 0.9rem;">Manage your password and account security.</p>
                <button class="btn btn-primary" style="margin-top: 1rem; font-size: 0.8rem; opacity: 0.7; cursor: not-allowed;">Change Password</button>
            </div>
        </div>
 
        <?php if ($user['role'] === 'seller'): ?>
            <div style="margin-top: 2rem; background: var(--accent-color); color: white; padding: 2rem; border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="margin-bottom: 0.5rem;">Seller Dashboard</h3>
                    <p style="opacity: 0.7; font-size: 0.9rem;">Manage your products and grow your business.</p>
                </div>
                <a href="add_product.php" class="btn btn-primary">Add New Product</a>
            </div>
        <?php endif; ?>
    </div>
</div>
 
<?php include 'footer.php'; ?>
 
