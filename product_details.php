<?php
include 'header.php';
 
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT p.*, u.name as seller_name FROM products p JOIN users u ON p.seller_id = u.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
 
if (!$product) {
    echo "<div class='container' style='text-align:center; padding: 10rem;'><h1>Product not found!</h1><a href='shop.php' class='btn btn-primary'>Back to Shop</a></div>";
    include 'footer.php';
    exit();
}
?>
 
<div class="container" style="margin-top: 5rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; background: white; padding: 3rem; border-radius: 20px; box-shadow: var(--shadow);">
        <div>
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" style="width: 100%; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);" onerror="this.src='https://via.placeholder.com/600x600?text=Product+Image'">
        </div>
        <div>
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem;"><?php echo htmlspecialchars($product['title']); ?></h1>
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 1.5rem;">
                <span class="product-price" style="font-size: 2rem;">$<?php echo number_format($product['price'], 2); ?></span>
                <span style="background: #e1f5fe; color: #0288d1; padding: 0.3rem 0.8rem; border-radius: 5px; font-weight: 600; font-size: 0.8rem;">In Stock</span>
            </div>
 
            <p style="opacity: 0.7; margin-bottom: 2rem; line-height: 1.8; font-size: 1.1rem;">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>
 
            <div style="margin-bottom: 2rem; padding: 1.5rem; background: #fdfdfd; border-radius: 10px; border: 1px solid #eee;">
                <h4 style="margin-bottom: 0.5rem; color: #666; font-size: 0.9rem;">Sold By</h4>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 35px; height: 35px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        <?php echo strtoupper(substr($product['seller_name'], 0, 1)); ?>
                    </div>
                    <span style="font-weight: 600;"><?php echo htmlspecialchars($product['seller_name']); ?></span>
                </div>
            </div>
 
            <form action="cart.php" method="POST" style="display: flex; gap: 1rem;">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="action" value="add">
                <div style="width: 120px;">
                    <input type="number" name="quantity" class="form-control" value="1" min="1" style="height: 50px; text-align: center;">
                </div>
                <button type="submit" class="btn btn-primary" style="flex-grow: 1; height: 50px; font-size: 1.1rem;">
                    <i class="fas fa-cart-shopping" style="margin-right: 10px;"></i> Add to Cart
                </button>
            </form>
 
            <div style="margin-top: 3rem; display: flex; gap: 2rem; opacity: 0.6; font-size: 0.9rem;">
                <div><i class="fas fa-shield-halved" style="margin-right: 5px;"></i> Authentic Brand</div>
                <div><i class="fas fa-truck-fast" style="margin-right: 5px;"></i> Express Delivery</div>
            </div>
        </div>
    </div>
</div>
 
<?php include 'footer.php'; ?>
 
