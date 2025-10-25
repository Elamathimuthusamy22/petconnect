<?php
session_start();
include 'db_connect.php';

// Check login
if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])){
    header("Location: index.php?page=login");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$collection = $db->feedback;

$success_message = '';
$error_message = '';

// Submit feedback
if(isset($_POST['submit'])){
    $message = trim($_POST['message']);
    if(!empty($message)){
        try {
            $collection->insertOne([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'message' => $message,
                'admin_reply' => null,
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);
            $success_message = "Feedback submitted successfully!";
        } catch(Exception $e){
            $error_message = $e->getMessage();
        }
    } else {
        $error_message = "Please enter a feedback message.";
    }
}

// Fetch user feedbacks
$feedbacks = $collection->find(['user_id' => $user_id], ['sort' => ['_id' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Feedback</title>
<style>
body { font-family: Arial; background: #f5f5f5; padding: 20px; }
.container { background: #fff; padding: 25px; border-radius: 15px; width: 600px; margin: auto; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #2d3436; }
textarea { width: 100%; height: 100px; padding: 10px; border-radius: 8px; border: 1px solid #ccc; margin-top: 10px; resize: none; }
button { background: #ff69b4; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; margin-top: 10px; }
button:hover { background: #ff4d94; }
table { width: 100%; margin-top: 20px; border-collapse: collapse; }
th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
th { background: #f8f8f8; }
.success-message { background: #00cec9; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: 600; }
.error-message { background: #d63031; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-weight: 600; }
</style>
</head>
<body>
<div class="container">
<h2>Submit Feedback</h2>

<?php if($success_message): ?>
    <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
<?php endif; ?>

<?php if($error_message): ?>
    <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
<?php endif; ?>

<form method="post">
    <textarea name="message" placeholder="Enter your feedback..." required></textarea>
    <button type="submit" name="submit">Send Feedback</button>
</form>

<h3 style="margin-top:30px;">Your Feedbacks</h3>
<table>
<tr><th>Message</th><th>Admin Reply</th></tr>
<?php foreach($feedbacks as $f): ?>
<tr>
    <td><?= htmlspecialchars($f['message']) ?></td>
    <td><?= isset($f['admin_reply']) && $f['admin_reply'] ? htmlspecialchars($f['admin_reply']) : 'Pending' ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
