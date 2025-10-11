<?php
if(!isset($_SESSION['user'])){
    echo "<p>Please login to access the dashboard.</p>";
    return;
}
?>
<h2>Dashboard</h2>
<p>Welcome, <?php echo $_SESSION['user']; ?>!</p>
<ul>
    <li><a href="index.php?page=adopt_pet">Adopt Pets</a></li>
    <li><a href="index.php?page=add_pet">Add Pet</a></li>
    <li><a href="index.php?page=pet_tips">Pet Tips</a></li>
</ul>
<a href="index.php?page=logout">Logout</a>
