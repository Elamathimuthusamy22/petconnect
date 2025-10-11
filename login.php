<?php
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $collection = $db->users;
    $user = $collection->findOne(['email' => $email]);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user'] = $user['name'];
        header("Location: index.php?page=dashboard");
        exit;
    } else {
        echo "<p style='color:red;'>Invalid credentials!</p>";
    }
}
?>
<h2>Login</h2>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>
