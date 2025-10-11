<?php
// Start session at the very top
session_start();

// Include header if you want consistent navigation
// include 'header.php';

// Check if user is logged in
if(!isset($_SESSION['user'])){
    echo "<p>Please login to access the dashboard.</p>";
    exit(); // stop the rest of the page
}
?>

<h2>Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</p>
<ul>
    <li><a href="index.php?page=adopt_pet">Adopt Pets</a></li>
    <li><a href="index.php?page=add_pet">Add Pet</a></li>
    <li><a href="index.php?page=pet_tips">Pet Tips</a></li>
</ul>
<a href="index.php?page=logout">Logout</a>
