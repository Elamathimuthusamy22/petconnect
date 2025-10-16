<?php
session_start(); // Start session at the top
include 'db_connect.php';

if(isset($_POST['signup'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $collection = $db->users;

    // Check if user already exists
    $existingUser = $collection->findOne(['email' => $email]);
    if($existingUser){
        $errorMessage = "Email already registered. Please login.";
    } else {
        $insertOneResult = $collection->insertOne([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);

        if($insertOneResult->getInsertedCount() == 1){
            // Set session variables
            $_SESSION['user_id'] = (string)$insertOneResult->getInsertedId();
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            $successMessage = "Signup successful! You are now logged in.";
        } else {
            $errorMessage = "Signup failed! Please try again.";
        }
    }
}
?>

<style>
.page-container {max-width: 600px;margin: 0 auto;padding: 20px;}
.page-content {background: white;padding: 40px;border-radius: 20px;box-shadow: 0 10px 30px rgba(0,0,0,0.1);margin-top: 20px;}
.page-content h2 {color: #2d3436;font-size: 36px;margin-bottom: 10px;text-align: center;}
.page-subtitle {color: #636e72;font-size: 16px;text-align: center;margin-bottom: 30px;}
.alert {padding: 15px 20px;border-radius: 10px;margin-bottom: 20px;font-weight: 500;}
.alert-success {background: #d4edda;color: #155724;border: 1px solid #c3e6cb;}
.alert-error {background: #f8d7da;color: #721c24;border: 1px solid #f5c6cb;}
.form-group {margin-bottom: 20px;}
.form-group label {display: block;color: #2d3436;font-weight: 500;margin-bottom: 8px;font-size: 14px;}
.form-group input {width: 100%;padding: 14px 16px;border: 2px solid #e8e8e8;border-radius: 10px;font-size: 15px;transition: all 0.3s;font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
.form-group input:focus {outline: none;border-color: #ff6348;box-shadow: 0 0 0 3px rgba(255, 99, 72, 0.1);}
.form-group input::placeholder {color: #b2bec3;}
.btn-submit {width: 100%;background: #ff6348;color: white;padding: 14px 30px;border-radius: 10px;border: none;font-weight: 600;font-size: 16px;cursor: pointer;transition: all 0.3s;margin-top: 10px;}
.btn-submit:hover {background: #ff4757;transform: translateY(-2px);box-shadow: 0 5px 15px rgba(255, 99, 72, 0.3);}
.btn-submit:active {transform: translateY(0);}
.form-footer {text-align: center;margin-top: 20px;color: #636e72;font-size: 14px;}
.form-footer a {color: #ff6348;text-decoration: none;font-weight: 600;}
.form-footer a:hover {text-decoration: underline;}
@media (max-width: 768px) {.page-container {padding: 10px;}.page-content {padding: 30px 20px;}.page-content h2 {font-size: 28px;}}
</style>

<div class="page-container">
    <div class="page-content">
        <h2>Create Account</h2>
        <p class="page-subtitle">Join PetConnect and start your journey!</p>

        <?php if(isset($successMessage)): ?>
            <div class="alert alert-success">
                ✓ <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($errorMessage)): ?>
            <div class="alert alert-error">
                ✗ <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a strong password" required>
            </div>

            <button type="submit" name="signup" class="btn-submit">Sign Up</button>
        </form>

        <div class="form-footer">
            Already have an account? 
            <a href="index.php?page=login">Login here</a>
        </div>
    </div>
</div>
