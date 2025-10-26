<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$collection = $db->petshop;
$cartCollection = $db->cart;

// Handle Add to Cart
if (isset($_POST['add_cart'])) {
    $itemName = $_POST['item_name'];

    $cartCollection->insertOne([
        'user_id' => $userId,
        'item_name' => $itemName,
        'added_at' => new MongoDB\BSON\UTCDateTime()
    ]);

    header("Location: cart.php");
    exit();
}

// Fetch all items
$items = $collection->find()->toArray();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pet Shop</title>
<style>
body { font-family: Arial; background: #f8f8f8; padding: 20px; }
.container { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
.item { background: white; padding: 20px; border-radius: 10px; width: 250px; text-align: center; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
.item img { width: 100%; height: 150px; object-fit: cover; border-radius: 10px; }
button { background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; margin-top: 10px; }
button:hover { background: #0056b3; }
.topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.cart-link { text-decoration: none; background: orange; color: white; padding: 10px 15px; border-radius: 8px; }
</style>
</head>
<body>

<div class="topbar">
    <h2>🐶 Pet Shop</h2>
    <a href="cart.php" class="cart-link">View Cart</a>
</div>

<div class="container">
<?php foreach($items as $item): ?>
    <div class="item">
        <?php
        if(isset($item['image']) && $item['image'] instanceof MongoDB\BSON\Binary){
            $imgType = $item['image_type'] ?? 'image/jpeg';
            $imgData = base64_encode($item['image']->getData());
            echo "<img src='data:$imgType;base64,$imgData'>";
        } else {
            echo "<div style='height:150px;background:#ddd;display:flex;align-items:center;justify-content:center;'>No Image</div>";
        }
        ?>
        <h3><?= htmlspecialchars($item['name']) ?></h3>
        <p>Type: <?= htmlspecialchars($item['type']) ?></p>
        <p>Price: ₹<?= $item['price'] ?></p>
        <form method="POST">
            <input type="hidden" name="item_name" value="<?= htmlspecialchars($item['name']) ?>">
            <button type="submit" name="add_cart">Add to Cart</button>
        </form>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>
