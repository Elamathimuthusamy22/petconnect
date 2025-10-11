<?php
$collection = $db->appointments;
if(isset($_POST['book'])){
    $collection->insertOne([
        'name' => $_POST['name'],
        'pet_name' => $_POST['pet_name'],
        'date' => $_POST['date'],
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    echo "<p style='color:green;'>Appointment booked!</p>";
}
?>
<h2>Book Appointment</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Your Name" required><br><br>
    <input type="text" name="pet_name" placeholder="Pet Name" required><br><br>
    <input type="date" name="date" required><br><br>
    <button type="submit" name="book">Book</button>
</form>
