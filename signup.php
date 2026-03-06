<?php
include 'header.php';
 
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
 
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashed_password, $role])) {
                $_SESSION['success'] = "Registration successful! Please login.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
 
<div class="auth-container">
    <h2 style="text-align: center; margin-bottom: 2rem;">Create Account</h2>
    <?php if ($error): ?>
        <div style="background: #ffebee; color: #c62828; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    <form action="signup.php" method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <div class="form-group">
            <label>Account Type</label>
            <select name="role" class="form-control">
                <option value="buyer">Buyer</option>
                <option value="seller">Seller</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; margin-top: 1rem;">Sign Up</button>
    </form>
    <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; opacity: 0.7;">
        Already have an account? <a href="login.php" style="color: var(--primary-color); font-weight: 600;">Login</a>
    </p>
</div>
 
<?php include 'footer.php'; ?>
 
