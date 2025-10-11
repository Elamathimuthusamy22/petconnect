
<?php
require 'vendor/autoload.php';

// MongoDB Atlas URI
$uri = "mongodb+srv://elamathimuthusamy22_db_user:iTy5jn4DTtCpMrW6@cluster0.3lcjrzs.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0";

try {
    $client = new MongoDB\Client($uri);
    $db = $client->petconnect_db; // Your database name
    // ❌ Remove or comment out this line
    // echo "✅ Connected to MongoDB successfully!";
} catch (Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
?>
