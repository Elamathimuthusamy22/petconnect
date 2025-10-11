<?php
if(isset($_POST['signup'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $collection = $db->users;
    $insertOneResult = $collection->insertOne([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    if($insertOneResult->getInsertedCount() == 1){
        echo "<p style='color:green;'>Signup successful! You can now login.</p>";
    } else {
        echo "<p style='color:red;'>Signup failed!</p>";
    }
}
?>
<h2>Signup</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="signup">Signup</button>
</form>
