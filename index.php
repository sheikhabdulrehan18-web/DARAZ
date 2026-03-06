<?php
include 'header.php';
 
// Fetch trending products (latest 8)
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
$trending_products = $stmt->fetchAll();
?>
 
<!-- Hero Slider Section -->
<section class="hero">
    <div class="hero-content">
        <h1 style="overflow: hidden; border-right: .15em solid orange; white-space: nowrap; margin: 0 auto; letter-spacing: .15em; animation: typing 3.5s steps(40, end), blink-caret .75s step-end infinite;">Big Sale Starts <span style="color: #fffb00;">Now!</span></h1>
        <p style="animation: fadeInUp 1.2s ease forwards; opacity: 0;">Up to 70% Off on Electronics, Fashion & Home Appliances. Exclusive deals for you.</p>
        <a href="shop.php" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem; animation: fadeInUp 1.5s ease forwards; opacity: 0; margin-top: 2rem;">Shop Collection</a>
    </div>
</section>
 
<style>
@keyframes typing { from { width: 0 } to { width: 100% } }
@keyframes blink-caret { from, to { border-color: transparent } 50% { border-color: orange; } }
.hero p, .hero .btn { opacity: 0; animation-fill-mode: forwards; }
</style>
 
<!-- Features Bar -->
<div style="background: white; padding: 2rem 5%; display: flex; justify-content: space-around; flex-wrap: wrap; gap: 2rem; box-shadow: var(--shadow); border-bottom: 2px solid var(--primary-color);">
    <div style="text-align: center;">
        <i class="fas fa-truck" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;"></i>
        <h4 style="font-size: 0.9rem;">Free Delivery</h4>
    </div>
    <div style="text-align: center;">
        <i class="fas fa-rotate-left" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;"></i>
        <h4 style="font-size: 0.9rem;">7 Day Returns</h4>
    </div>
    <div style="text-align: center;">
        <i class="fas fa-shield-check" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;"></i>
        <h4 style="font-size: 0.9rem;">Secure Payment</h4>
    </div>
    <div style="text-align: center;">
        <i class="fas fa-headset" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;"></i>
        <h4 style="font-size: 0.9rem;">24/7 Support</h4>
    </div>
</div>
 
<div class="container" style="margin-top: 4rem;">
    <!-- Featured Categories -->
    <div class="section-title">Shop by Category</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 4rem;">
        <?php
        $categories = ['Electronics', 'Men Fashion', 'Women Fashion', 'Home Decor', 'Beauty', 'Sports'];
        foreach ($categories as $cat):
        ?>
        <div style="background: white; padding: 1.5rem; border-radius: 12px; text-align: center; border: 1px solid #eee; transition: var(--transition); cursor: pointer;" onmouseover="this.style.borderColor='var(--primary-color)'" onmouseout="this.style.borderColor='#eee'">
            <h5 style="margin: 0; font-weight: 500;"><?php echo $cat; ?></h5>
        </div>
        <?php endforeach; ?>
    </div>
 
    <!-- Trending Products -->
    <div class="section-title">Trending Deals</div>
    <div class="product-grid">
        <?php if (empty($trending_products)): ?>
            <p style="grid-column: 1/-1; text-align: center; padding: 3rem; opacity: 0.5;">No products available yet. Check back soon!</p>
        <?php else: ?>
            <?php foreach ($trending_products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="product-image" onerror="this.src='https://via.placeholder.com/300x300?text=Product'">
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                        <div style="display: flex; gap: 5px; color: #ff9800; font-size: 0.8rem; margin: 0.5rem 0;">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            <span style="color: #888; margin-left: 5px;">(12)</span>
                        </div>
                        <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-primary" style="width: 100%; text-align: center; margin-top: 1rem; font-size: 0.9rem;">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
 
    <!-- CTA Section -->
    <div style="margin-top: 5rem; background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?auto=format&fit=crop&w=1200') center/cover; padding: 6rem 2rem; border-radius: 20px; text-align: center; color: white;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Become a Seller Today!</h2>
        <p style="max-width: 600px; margin: 0 auto 2rem; opacity: 0.9;">Join thousands of businesses and start selling to millions of customers on Daraz.</p>
        <a href="signup.php" class="btn btn-primary" style="padding: 1rem 3rem;">Start Selling</a>
    </div>
</div>
 
<?php include 'footer.php'; ?>
 
