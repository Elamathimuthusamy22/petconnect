<?php
require 'db_connect.php';
session_start();

// Get page from URL
$page = $_GET['page'] ?? 'home';

// Include header
include 'header.php';

// Include the page content dynamically
switch($page){
    case 'signup':
        include 'signup.php';
        break;
    case 'login':
        include 'login.php';
        break;
    case 'adopt_pet':
        include 'adopt_pet.php';
        break;
    case 'add_pet':
        include 'add_pet.php';
        break;
    case 'pet_tips':
        include 'pet_tips.php';
        break;
    case 'appointments':
        include 'appointments.php';
        break;
    case 'pet_shop':
        include 'pet_shop.php';
        break;
    case 'lost_found':
        include 'lost_found.php';
        break;
    case 'feedback':
        include 'feedback.php';
        break;
    case 'dashboard':
        include 'dashboard.php';
        break;
    default:
        echo "<h2>Welcome to PetConnect!</h2>";
        echo "<p>Use the navigation above to explore the site.</p>";
}

include 'footer.php';
?>
