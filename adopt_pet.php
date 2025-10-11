<h2>Available Pets</h2>
<ul>
<?php
$collection = $db->pets;
$pets = $collection->find();
foreach($pets as $pet){
    echo "<li>".$pet['name']." - ".$pet['type']."</li>";
}
?>
</ul>
