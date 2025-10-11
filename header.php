<?php
// No whitespace before this tag!
// Start of header.php

$currentPage = isset($_GET['page']) ? $_GET['page'] : 'home';

$navItems = [
    'Home' => 'index.php?page=home',
    'Sign Up' => 'index.php?page=signup',
    'Login' => 'index.php?page=login',
    'Adopt Pet' => 'index.php?page=adopt_pet',
    'Add Pet' => 'index.php?page=add_pet',
    'Pet Tips' => 'index.php?page=pet_tips',
    'Appointments' => 'index.php?page=appointments',
    'Pet Shop' => 'index.php?page=pet_shop',
    'Lost & Found' => 'index.php?page=lost_found',
    'Feedback' => 'index.php?page=feedback',
    'Dashboard' => 'index.php?page=dashboard'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - <?php echo ucfirst($currentPage); ?></title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%); min-height:100vh; }
        .header-container { max-width: 1400px; margin:0 auto; padding:20px; }
        header { display:flex; justify-content:space-between; align-items:center; padding:20px 0; margin-bottom:20px; }
        .logo { font-size:36px; font-weight:bold; color:#2d3436; text-decoration:none; }
        .logo::after { content: '.'; color:#ff6348; }
        nav { display:flex; gap:15px; align-items:center; flex-wrap:wrap; }
        nav a { text-decoration:none; color:#2d3436; font-weight:500; padding:8px 18px; border-radius:25px; transition: all 0.3s; font-size:14px; white-space:nowrap; }
        nav a.active { background:#ff6348; color:white; }
        nav a:hover:not(.active) { color:#ff6348; background: rgba(255,99,72,0.1); }
        @media (max-width:968px) { header { flex-direction:column; gap:20px; } nav { justify-content:center; } }
    </style>
</head>
<body>
<div class="header-container">
    <header>
        <a href="index.php?page=home" class="logo">D&Cs</a>
        <nav>
            <?php foreach ($navItems as $item => $link): 
                $page = str_replace('index.php?page=', '', $link);
                $isActive = ($page === $currentPage) ? 'active' : '';
            ?>
            <a href="<?php echo htmlspecialchars($link); ?>" class="<?php echo $isActive; ?>">
                <?php echo htmlspecialchars($item); ?>
            </a>
            <?php endforeach; ?>
        </nav>
    </header>
</div>
