<?php
$collection = $db->feedback;
if(isset($_POST['submit'])){
    $collection->insertOne([
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'message' => $_POST['message'],
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    echo "<p style='color:green;'>Feedback submitted!</p>";
}
?>
<h2>Feedback</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Your Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <textarea name="message" placeholder="Your Feedback" required></textarea><br><br>
    <button type="submit" name="submit">Submit</button>
</form>
