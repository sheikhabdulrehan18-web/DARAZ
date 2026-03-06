<?php
include 'header.php';
 
$error = '';
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
 
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
 
<div class="auth-container" style="margin-top: 8rem;">
    <h2 style="text-align: center; margin-bottom: 2rem;">Welcome Back</h2>
 
    <?php if (isset($success)): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem;">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>
 
    <?php if ($error): ?>
        <div style="background: #ffebee; color: #c62828; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
 
    <form action="login.php" method="POST">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; margin-top: 1rem;">Login</button>
    </form>
    <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; opacity: 0.7;">
        Don't have an account? <a href="signup.php" style="color: var(--primary-color); font-weight: 600;">Sign Up</a>
    </p>
</div>
 
<?php include 'footer.php'; ?>
 
