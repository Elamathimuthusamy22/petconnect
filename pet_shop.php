<?php
session_start();
include 'db_connect.php';

$loggedIn = isset($_SESSION['user_id']); // check if user is logged in
$userId = $loggedIn ? $_SESSION['user_id'] : null;

$collection = $db->petshop;
$cartCollection = $db->cart;

// Handle Add/Remove via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$loggedIn) {
        echo json_encode(['success' => false, 'message' => 'Login required']);
        exit;
    }

    $itemId = $_POST['item_id'] ?? null;
    if (!$itemId) exit;

    // Add to cart
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $cartCollection->updateOne(
            ['user_id' => $userId, 'items.item_id' => ['$ne' => $itemId]],
            ['$push' => ['items' => ['item_id' => $itemId, 'added_at' => new MongoDB\BSON\UTCDateTime()]]],
            ['upsert' => true]
        );
        echo json_encode(['success' => true, 'action' => 'added']);
        exit;
    }

    // Remove from cart
    if (isset($_POST['action']) && $_POST['action'] === 'remove') {
        $cartCollection->updateOne(
            ['user_id' => $userId],
            ['$pull' => ['items' => ['item_id' => $itemId]]]
        );
        echo json_encode(['success' => true, 'action' => 'removed']);
        exit;
    }
}

// Fetch all items
$items = $collection->find();
$itemsArray = iterator_to_array($items);

// Fetch current user's cart
$userCart = [];
if ($loggedIn) {
    $cartDoc = $cartCollection->findOne(['user_id' => $userId]);
    if ($cartDoc && isset($cartDoc['items'])) {
        $userCart = array_column($cartDoc['items'], 'item_id');
    }
}
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
<div class="cart-count" id="cartCount"><?php echo count($userCart); ?></div>

<div class="items-grid">
    <?php foreach($itemsArray as $item): 
        $itemId = (string)$item['_id'];
        $inCart = in_array($itemId, $userCart);
    ?>
        <div class="item-card" data-id="<?php echo $itemId; ?>">
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
                    <button class="add-cart-btn" <?php if($inCart) echo 'style="display:none;"'; ?>>Add to Cart</button>
                    <button class="remove-cart-btn" <?php if(!$inCart) echo 'style="display:none;"'; ?>>Remove from Cart</button>
                <?php else: ?>
                    <button class="add-cart-btn" disabled>Add to Cart (Login Required)</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
const loggedIn = <?php echo $loggedIn ? 'true' : 'false'; ?>;

if(loggedIn){
    const cartCount = document.getElementById('cartCount');

    document.querySelectorAll('.item-card').forEach(card => {
        const addBtn = card.querySelector('.add-cart-btn');
        const removeBtn = card.querySelector('.remove-cart-btn');
        const itemId = card.getAttribute('data-id');

        addBtn && addBtn.addEventListener('click', () => {
            fetch('petshop.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `item_id=${itemId}&action=add`
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    addBtn.style.display = 'none';
                    removeBtn.style.display = 'inline-block';
                    cartCount.textContent = parseInt(cartCount.textContent) + 1;
                }
            });
        });

        removeBtn && removeBtn.addEventListener('click', () => {
            fetch('petshop.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `item_id=${itemId}&action=remove`
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    removeBtn.style.display = 'none';
                    addBtn.style.display = 'inline-block';
                    cartCount.textContent = parseInt(cartCount.textContent) - 1;
                }
            });
        });
    });
}
</script>

</body>
</html>
