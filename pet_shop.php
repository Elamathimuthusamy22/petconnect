<?php
session_start();
include 'db_connect.php';
$collection = $db->petshop;

// Check if user is logged in
$loggedIn = isset($_SESSION['user_id']);

// Initialize cart in session
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Add to cart
if($loggedIn && isset($_POST['add_cart'])){
    $itemId = $_POST['item_id'];
    if(!in_array($itemId, $_SESSION['cart'])){
        $_SESSION['cart'][] = $itemId;
    }
}

// Remove from cart
if($loggedIn && isset($_POST['remove_cart'])){
    $itemId = $_POST['item_id'];
    if(($key = array_search($itemId, $_SESSION['cart'])) !== false){
        unset($_SESSION['cart'][$key]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex array
    }
}

// Fetch all items
$items = $collection->find();
$itemsArray = iterator_to_array($items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pet Shop</title>
<style>
body{font-family:Arial;background:#fafafa;padding:40px;}
h2{text-align:center;color:#2d3436;}
.items-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:20px;}
.item-card{background:#fff;border-radius:15px;padding:15px;box-shadow:0 3px 15px rgba(0,0,0,0.1);position:relative;}
.item-card img{width:100%;height:150px;object-fit:cover;border-radius:10px;}
.item-info{margin-top:10px;}
.item-name{font-weight:bold;color:#2d3436;font-size:18px;}
.item-type, .item-price, .item-qty{font-size:14px;color:#636e72;margin-top:3px;}
.add-cart-btn, .remove-cart-btn{padding:8px 15px;border:none;border-radius:10px;background:#ff69b4;color:white;cursor:pointer;margin-top:10px;}
.add-cart-btn:disabled, .remove-cart-btn:disabled{background:#ccc;cursor:not-allowed;}
.cart-count{position:fixed;top:20px;right:20px;background:#ff4757;color:white;padding:10px 15px;border-radius:50%;font-weight:bold;}
</style>
</head>
<body>

<h2>Pet Shop</h2>

<div class="cart-count"><?php echo count($_SESSION['cart']); ?></div>

<div class="items-grid">
    <?php foreach($itemsArray as $item): 
        $itemId = (string)$item['_id'];
        $inCart = in_array($itemId, $_SESSION['cart']);
    ?>
        <div class="item-card">
            <?php 
            if(isset($item['image']) && $item['image'] instanceof MongoDB\BSON\Binary){
                $imgType = $item['image_type'] ?? 'image/jpeg';
                $imgData = base64_encode($item['image']->getData());
                echo "<img src='data:$imgType;base64,$imgData'>";
            } else {
                echo "<div style='height:150px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;'>No Image</div>";
            }
            ?>
            <div class="item-info">
                <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                <div class="item-type">Type: <?php echo htmlspecialchars($item['type']); ?></div>
                <div class="item-price">Price: $<?php echo $item['price']; ?></div>
                <div class="item-qty">Qty: <?php echo $item['quantity']; ?></div>

                <?php if($loggedIn): ?>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="item_id" value="<?php echo $itemId; ?>">
                        <?php if($inCart): ?>
                            <button type="submit" name="remove_cart" class="remove-cart-btn">Remove from Cart</button>
                        <?php else: ?>
                            <button type="submit" name="add_cart" class="add-cart-btn">Add to Cart</button>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <button class="add-cart-btn" disabled>Add to Cart (Login Required)</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
