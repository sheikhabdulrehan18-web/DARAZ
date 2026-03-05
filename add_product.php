<?php
include 'header.php';
 
if (!isLoggedIn() || getRole() !== 'seller') {
    header("Location: login.php");
    exit();
}
 
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $seller_id = $_SESSION['user_id'];
 
    // Simple image URL for now as per "minimal placeholders but working demo"
    // In real app, we'd handle file upload. Rules say "Image upload with validation".
    // Let's implement actual upload.
 
    $target_dir = ""; // Root directory as per "all files in root"
    $image_url = "";
 
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_file = time() . "_" . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
 
        // Check if image file is a actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $target_file;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    }
 
    if (!$error) {
        if (empty($title) || empty($price) || empty($image_url)) {
            $error = "Title, price and image are required.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (seller_id, title, description, price, image) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$seller_id, $title, $description, $price, $image_url])) {
                $_SESSION['success'] = "Product added successfully!";
                header("Location: shop.php");
                exit();
            } else {
                $error = "Failed to add product.";
            }
        }
    }
}
?>
 
<div class="container" style="max-width: 700px; margin-top: 5rem;">
    <div style="background: white; padding: 3rem; border-radius: 20px; box-shadow: var(--shadow);">
        <h1 style="margin-bottom: 2rem; border-bottom: 2px solid var(--primary-color); padding-bottom: 10px; display: inline-block;">Add New Product</h1>
 
        <?php if ($error): ?>
            <div style="background: #ffebee; color: #c62828; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
 
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Wireless Headphones" required>
            </div>
            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="99.99" required>
            </div>
            <div class="form-group">
                <label>Product Image</label>
                <input type="file" name="image" class="form-control" required style="padding: 10px;">
                <p style="font-size: 0.8rem; opacity: 0.6; margin-top: 5px;">Supported formats: JPG, PNG, WEBP</p>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Describe your product in detail..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem; margin-top: 1rem;">
                <i class="fas fa-plus" style="margin-right: 10px;"></i> Publish Product
            </button>
        </form>
    </div>
</div>
 
<?php include 'footer.php'; ?>
 
