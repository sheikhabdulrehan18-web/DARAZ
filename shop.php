<?php
include 'header.php';
 
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM products WHERE title LIKE ? OR description LIKE ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(['%'.$search.'%', '%'.$search.'%']);
$products = $stmt->fetchAll();
?>
 
<div class="container" style="margin-top: 3rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2rem;">Explore Shop</h1>
        <form action="shop.php" method="GET" style="display: flex; gap: 10px; width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>
 
    <div class="product-grid">
        <?php if (empty($products)): ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 5rem; background: white; border-radius: 20px;">
                <dotlottie-player src="https://lottie.host/98c2534f-9e8a-4933-9f8e-4903337e7df4/YNoA0BfG8y.json" background="transparent" speed="1" style="width: 200px; height: 200px; margin: 0 auto;" loop autoplay></dotlottie-player>
                <h3 style="margin-top: 1rem;">No results found for "<?php echo htmlspecialchars($search); ?>"</h3>
                <p style="opacity: 0.6;">Try searching for something else or browse categories.</p>
                <a href="shop.php" class="btn btn-primary" style="margin-top: 2rem;">Clear Search</a>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="product-image" onerror="this.src='https://via.placeholder.com/300x300?text=Product'">
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                        <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-primary" style="width: 100%; text-align: center; margin-top: 1rem; font-size: 0.9rem;">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
 
<?php include 'footer.php'; ?>
 
