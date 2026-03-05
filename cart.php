<?php
include 'header.php';
 
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}
 
$user_id = $_SESSION['user_id'];
 
// Handle Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $product_id = $_POST['product_id'];
 
    if ($action == 'add') {
        $quantity = $_POST['quantity'] ?? 1;
        // Check if item already in cart
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing = $stmt->fetch();
 
        if ($existing) {
            $new_qty = $existing['quantity'] + $quantity;
            $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?")->execute([$new_qty, $existing['id']]);
        } else {
            $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)")->execute([$user_id, $product_id, $quantity]);
        }
        header("Location: cart.php");
        exit();
    } elseif ($action == 'remove') {
        $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?")->execute([$user_id, $product_id]);
        header("Location: cart.php");
        exit();
    } elseif ($action == 'update') {
        $quantity = $_POST['quantity'];
        if ($quantity > 0) {
            $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?")->execute([$quantity, $user_id, $product_id]);
        } else {
            $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?")->execute([$user_id, $product_id]);
        }
        header("Location: cart.php");
        exit();
    }
}
 
// Fetch Cart items
$stmt = $pdo->prepare("SELECT c.*, p.title, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();
 
$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
 
<div class="container" style="margin-top: 3rem;">
    <h1 style="margin-bottom: 2rem;"><i class="fas fa-shopping-cart" style="color: var(--primary-color);"></i> Your Shopping Cart</h1>
 
    <?php if (empty($items)): ?>
        <div style="background: white; padding: 5rem; text-align: center; border-radius: 20px; box-shadow: var(--shadow);">
            <i class="fas fa-cart-arrow-down" style="font-size: 5rem; color: #eee; margin-bottom: 2rem;"></i>
            <h3>Your cart is empty</h3>
            <p style="opacity: 0.6; margin-bottom: 2rem;">Looks like you haven't added anything to your cart yet.</p>
            <a href="shop.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Cart Items -->
            <div style="background: white; border-radius: 20px; box-shadow: var(--shadow); overflow: hidden;">
                <?php foreach ($items as $item): ?>
                    <div style="display: flex; align-items: center; padding: 1.5rem; border-bottom: 1px solid #eee; gap: 1.5rem;">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;" onerror="this.src='https://via.placeholder.com/100x100'">
                        <div style="flex-grow: 1;">
                            <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p style="color: var(--primary-color); font-weight: 700;">$<?php echo number_format($item['price'], 2); ?></p>
                        </div>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <form action="cart.php" method="POST" style="display: flex; align-items: center; gap: 5px;">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <input type="hidden" name="action" value="update">
                                <input type="number" name="quantity" class="form-control" value="<?php echo $item['quantity']; ?>" min="1" onchange="this.form.submit()" style="width: 70px; padding: 0.4rem;">
                            </form>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <input type="hidden" name="action" value="remove">
                                <button type="submit" style="background: none; border: none; color: #f44336; cursor: pointer; font-size: 1.2rem;">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
 
            <!-- Summary -->
            <div>
                <div style="background: white; padding: 2rem; border-radius: 20px; box-shadow: var(--shadow); position: sticky; top: 120px;">
                    <h3 style="margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">Order Summary</h3>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span>Subtotal (<?php echo count($items); ?> items)</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span>Shipping Fee</span>
                        <span style="color: #2e7d32; font-weight: 600;">FREE</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 2rem; border-top: 2px solid #eee; padding-top: 1rem; font-weight: 800; font-size: 1.2rem;">
                        <span>Total</span>
                        <span style="color: var(--primary-color);">$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <a href="checkout.php" class="btn btn-primary" style="width: 100%; text-align: center; margin-top: 2rem; padding: 1rem;">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
 
<?php include 'footer.php'; ?>
 
