<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$cartCollection = $db->cart;

// Handle remove from cart
if (isset($_POST['remove_cart'])) {
    $id = new MongoDB\BSON\ObjectId($_POST['cart_id']);
    $cartCollection->deleteOne(['_id' => $id]);
    header("Location: cart.php");
    exit();
}

// Fetch current user's cart
$cartItems = $cartCollection->find(['user_id' => $userId])->toArray();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Cart</title>
<style>
body { font-family: Arial; background: #f4f4f4; padding: 20px; }
.container { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
.cart-item { display: flex; justify-content: space-between; margin-bottom: 15px; align-items: center; padding: 10px; border-bottom: 1px solid #ddd; }
button { background: #ff4757; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; }
button:hover { background: #d63031; }
</style>
</head>
<body>

<div class="container">
<h2>My Cart</h2>

<?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <?php foreach($cartItems as $cart): ?>
        <div class="cart-item">
            <span><?= htmlspecialchars($cart['item_name']) ?></span>
            <form method="POST">
                <input type="hidden" name="cart_id" value="<?= $cart['_id'] ?>">
                <button type="submit" name="remove_cart">Remove</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</div>

</body>
</html>
