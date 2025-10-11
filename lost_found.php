<?php
$collection = $db->lost_found;
if(isset($_POST['report'])){
    $collection->insertOne([
        'pet_name' => $_POST['pet_name'],
        'status' => $_POST['status'],
        'description' => $_POST['description'],
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    echo "<p style='color:green;'>Report submitted!</p>";
}
$reports = $collection->find();
?>
<h2>Lost & Found Reports</h2>
<form method="POST">
    <input type="text" name="pet_name" placeholder="Pet Name" required><br><br>
    <select name="status" required>
        <option value="">Select Status</option>
        <option value="Lost">Lost</option>
        <option value="Found">Found</option>
    </select><br><br>
    <textarea name="description" placeholder="Description" required></textarea><br><br>
    <button type="submit" name="report">Submit</button>
</form>
<h3>All Reports</h3>
<ul>
<?php foreach($reports as $r){ echo "<li>".$r['pet_name']." - ".$r['status']." - ".$r['description']."</li>"; } ?>
</ul>
