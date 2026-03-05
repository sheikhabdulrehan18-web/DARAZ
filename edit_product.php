<?php
include 'header.php';
 
if (!isLoggedIn() || getRole() !== 'seller') {
    header("Location: login.php");
    exit();
}
 
$seller_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? 0;
 
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$id, $seller_id]);
$product = $stmt->fetch();
 
if (!$product) {
    header("Location: shop.php");
    exit();
}
 
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $image_url = $product['image'];
 
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_file = time() . "_" . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Delete old image if exists
                if(file_exists($product['image'])) unlink($product['image']);
                $image_url = $target_file;
            }
        }
    }
 
    if (empty($title) || empty($price)) {
        $error = "Title and price are required.";
    } else {
        $stmt = $pdo->prepare("UPDATE products SET title = ?, description = ?, price = ?, image = ? WHERE id = ? AND seller_id = ?");
        if ($stmt->execute([$title, $description, $price, $image_url, $id, $seller_id])) {
            $_SESSION['success'] = "Product updated successfully!";
            header("Location: shop.php");
            exit();
        } else {
            $error = "Failed to update product.";
        }
    }
}
?>
 
<div class="container" style="max-width: 700px; margin-top: 5rem;">
    <div style="background: white; padding: 3rem; border-radius: 20px; box-shadow: var(--shadow);">
        <h1 style="margin-bottom: 2rem;">Edit Product</h1>
 
        <?php if ($error): ?>
            <div style="background: #ffebee; color: #c62828; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
 
        <form action="edit_product.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($product['title']); ?>" required>
            </div>
            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="form-group">
                <label>Product Image (Optional)</label>
                <input type="file" name="image" class="form-control" style="padding: 10px;">
                <p style="font-size: 0.8rem; opacity: 0.6; margin-top: 5px;">Currently: <?php echo $product['image']; ?></p>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="5"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex-grow: 1; padding: 1rem;">Update Product</button>
                <a href="delete_product.php?id=<?php echo $id; ?>" class="btn" style="background: #f44336; color: white; padding: 1rem;" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
            </div>
        </form>
    </div>
</div>
 
<?php include 'footer.php'; ?>
 
