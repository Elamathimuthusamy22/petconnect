<?php
if(isset($_POST['add_pet'])){
    $collection = $db->pets;
    $collection->insertOne([
        'name' => $_POST['name'],
        'type' => $_POST['type'],
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    echo "<p style='color:green;'>Pet added successfully!</p>";
}
?>
<h2>Add Pet</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Pet Name" required><br><br>
    <input type="text" name="type" placeholder="Pet Type" required><br><br>
    <button type="submit" name="add_pet">Add Pet</button>
</form>
