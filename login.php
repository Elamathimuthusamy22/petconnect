<?php
// Start output buffering and session at the very top
ob_start();
session_start();

// Include database connection
include 'db_connect.php';

// Include header AFTER starting session
// include 'header.php';

// Initialize error message
$errorMessage = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $collection = $db->users;
    $user = $collection->findOne(['email' => $email]);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['name'];
        header("Location: index.php?page=dashboard");
        exit();
    } else {
        $errorMessage = "Invalid credentials! Please check your email and password.";
    }
}
?>

<!-- CSS and HTML UI (unchanged) -->
<style>
    .page-container { max-width:600px; margin:0 auto; padding:20px; }
    .page-content { background:white; padding:40px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,0.1); margin-top:20px; }
    .page-content h2 { color:#636e72; font-size:36px; margin-bottom:10px; text-align:center; }
    .page-subtitle { color:#636e72; font-size:16px; text-align:center; margin-bottom:30px; }
    .alert { padding:15px 20px; border-radius:10px; margin-bottom:20px; font-weight:500; }
    .alert-error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
    .form-group { margin-bottom:20px; }
    .form-group label { display:block; color:#2d3436; font-weight:500; margin-bottom:8px; font-size:14px; }
    .form-group input { width:100%; padding:14px 16px; border:2px solid #e8e8e8; border-radius:10px; font-size:15px; transition: all 0.3s; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .form-group input:focus { outline:none; border-color:#ff6348; box-shadow:0 0 0 3px rgba(255,99,72,0.1); }
    .form-group input::placeholder { color:#b2bec3; }
    .forgot-password { text-align:right; margin-top:-10px; margin-bottom:20px; }
    .forgot-password a { color:#ff6348; text-decoration:none; font-size:14px; font-weight:500; }
    .forgot-password a:hover { text-decoration:underline; }
    .btn-submit { width:100%; background:#ff6348; color:white; padding:14px 30px; border-radius:10px; border:none; font-weight:600; font-size:16px; cursor:pointer; transition: all 0.3s; margin-top:10px; }
    .btn-submit:hover { background:#ff4757; transform:translateY(-2px); box-shadow:0 5px 15px rgba(255,99,72,0.3); }
    .btn-submit:active { transform:translateY(0); }
    .form-footer { text-align:center; margin-top:20px; color:#636e72; font-size:14px; }
    .form-footer a { color:#ff6348; text-decoration:none; font-weight:600; }
    .form-footer a:hover { text-decoration:underline; }
    .welcome-icon { text-align:center; font-size:48px; margin-bottom:20px; }
    @media (max-width:768px) { .page-container { padding:10px; } .page-content { padding:30px 20px; } .page-content h2 { font-size:28px; } }
</style>

<div class="page-container">
    <div class="page-content">
        <div class="welcome-icon">🐾</div>
        <h2>Welcome Back!</h2>
        <p class="page-subtitle">Login to access your PetConnect account</p>

        <?php if(!empty($errorMessage)): ?>
            <div class="alert alert-error">
                ✗ <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="forgot-password">
                <a href="#" onclick="alert('Password reset feature coming soon!'); return false;">Forgot password?</a>
            </div>

            <button type="submit" name="login" class="btn-submit">Login</button>
        </form>

        <div class="form-footer">
            Don't have an account? <a href="index.php?page=signup">Sign up here</a>
        </div>
    </div>
</div>

<?php
// Flush output buffer
ob_end_flush();
?>
