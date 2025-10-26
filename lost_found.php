<?php
session_start();
require_once 'db_connect.php';

$loggedIn = isset($_SESSION['user_id']); // check if user is logged in

$collection = $db->lost_found;

$success_message = '';
$error_message = '';

// Handle submission
if(isset($_POST['submit'])){
    if(!$loggedIn){
        $error_message = "You must be logged in to submit an item.";
    } else {
        try {
            $type = $_POST['type'] ?? '';
            $item_name = trim($_POST['item_name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if(empty($type) || empty($item_name) || empty($description)){
                throw new Exception("All fields are required.");
            }

            $collection->insertOne([
                'user_id' => $_SESSION['user_id'],
                'user_name' => $_SESSION['user_name'] ?? $_SESSION['user'] ?? 'User',
                'type' => $type,
                'item_name' => $item_name,
                'description' => $description,
                'status' => 'Pending Approval', // initial status
                'admin_note' => null,
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);

            $success_message = "Item submitted successfully!";
        } catch(Exception $e){
            $error_message = $e->getMessage();
        }
    }
}

// Fetch user submissions
$items = $collection->find(['user_id' => $_SESSION['user_id'] ?? ''], ['sort' => ['_id' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lost & Found</title>
<style>
body { font-family: Arial; background: #f5f5f5; padding: 20px; }
.container { max-width: 700px; margin: auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); position: relative; }
h2 { text-align: center; margin-bottom: 20px; color: #2d3436; }
form input, form select, form textarea { width: 100%; padding: 12px; margin-top: 10px; border-radius: 10px; border: 1px solid #ccc; }
button { background: #ff69b4; color: white; border: none; padding: 12px 20px; border-radius: 10px; cursor: pointer; margin-top: 15px; }
button:hover { background: #ff4d94; }
.success-message { background: #00cec9; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: 600; }
.error-message { background: #d63031; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: 600; }
table { width: 100%; border-collapse: collapse; margin-top: 30px; }
th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: middle; }
th { background: #f8f8f8; }
a { color: #ff69b4; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="container">
    <h2>Report Lost / Found Item</h2>

    <?php if($success_message): ?>
        <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    <?php if($error_message): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if(!$loggedIn): ?>
        <div class="error-message">Please <a href="index.php?page=login" style="color:white;text-decoration:underline;">login</a> to submit an item.</div>
    <?php endif; ?>

    <form method="POST">
        <select name="type" required <?php if(!$loggedIn) echo 'disabled'; ?>>
            <option value="">Select Type</option>
            <option value="Lost">Lost</option>
            <option value="Found">Found</option>
        </select>

        <input type="text" name="item_name" placeholder="Item Name" required <?php if(!$loggedIn) echo 'disabled'; ?>>

        <textarea name="description" placeholder="Description" required <?php if(!$loggedIn) echo 'disabled'; ?>></textarea>

        <button type="submit" name="submit" <?php if(!$loggedIn) echo 'disabled'; ?>>Submit</button>
    </form>

    <h3 style="margin-top:40px;">Your Submissions</h3>
    <table>
        <tr><th>Type</th><th>Item</th><th>Description</th><th>Status</th></tr>
        <?php foreach($items as $i): ?>
        <tr>
            <td><?= htmlspecialchars($i['type']) ?></td>
            <td><?= htmlspecialchars($i['item_name']) ?></td>
            <td><?= htmlspecialchars($i['description']) ?></td>
            <td>
                <?= htmlspecialchars($i['status']) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
