<?php
$collection = $db->pet_tips;
if(isset($_POST['add_tip'])){
    $collection->insertOne([
        'tip' => $_POST['tip'],
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    echo "<p style='color:green;'>Tip added!</p>";
}
$tips = $collection->find();
?>
<h2>Pet Tips</h2>
<form method="POST">
    <textarea name="tip" placeholder="Add a tip..." required></textarea><br><br>
    <button type="submit" name="add_tip">Add Tip</button>
</form>
<h3>All Tips</h3>
<ul>
<?php foreach($tips as $tip){ echo "<li>".$tip['tip']."</li>"; } ?>
</ul>
