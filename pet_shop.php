<?php
$collection = $db->shop_items;
if(isset($_POST['add_item'])){
    $collection->insertOne([
        'item_name' => $_POST['item_name'],
        'price' => $_POST['price'],
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ]);
    echo "<p style='color:green;'>Item added!</p>";
}
$items = $collection->find();
?>
<h2>Pet Shop Items</h2>
<form method="POST">
    <input type="text" name="item_name" placeholder="Item Name" required>
    <input type="number" name="price" placeholder="Price" required>
    <button type="submit" name="add_item">Add Item</button>
</form>
<h3>Available Items</h3>
<ul>
<?php foreach($items as $item){ echo "<li>".$item['item_name']." - $".$item['price']."</li>"; } ?>
</ul>
