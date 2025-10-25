<?php
// Include header (navigation)
include 'header.php';

// Determine which page to load
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Allowed pages
$allowedPages = [
    'home', 'signup', 'login', 'adopt_pet',  
     'appointments', 'pet_shop', 'lost_found', 'logout',
    'feedback', 'dashboard'
];

// If user is on 'home', show the hero and stats section
if ($page === 'home') {
?>
<style>
    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    .hero {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
        min-height: 600px;
        position: relative;
    }

    .hero-content h1 {
        font-size: 72px;
        color: #2d3436;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .hero-content h1 .highlight {
        color: #ff69b4;
    }

    .hero-content p {
        color: #636e72;
        font-size: 16px;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .cta-buttons {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .btn-primary {
        background: #ff6348;
        color: white;
        padding: 15px 35px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-primary:hover {
        background: #ff4757;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 99, 72, 0.3);
    }

    .btn-primary::after {
        content: '▶';
        font-size: 12px;
    }

    .link-secondary {
        color: #2d3436;
        text-decoration: underline;
        font-weight: 500;
    }

    .hero-image {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pink-blob {
        position: absolute;
        width: 500px;
        height: 500px;
        background: linear-gradient(135deg, #ccc9c9ff 0%, #f5f5f5 100%);
        border-radius: 45% 55% 60% 40% / 50% 45% 55% 50%;
        z-index: 1;
    }

    .main-image {
        position: relative;
        z-index: 2;
        width: 450px;
        height: 450px;
        border-radius: 50%;
        object-fit: cover;
    }

    .dog-circle {
        position: absolute;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid white;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        z-index: 3;
    }

    .dog-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .paw-print {
        position: absolute;
        color: #ddd;
        font-size: 40px;
        opacity: 0.3;
    }

    .paw-1 { top: 10%; right: 20%; }
    .paw-2 { bottom: 20%; left: 15%; }
    .paw-3 { top: 30%; right: 5%; }

    .stats-section {
        background: linear-gradient(135deg, #2d3436 0%, #1e272e 100%);
        border-radius: 20px;
        padding: 50px 50px 50px 400px;
        margin-top: 60px;
        margin-left: 100px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        text-align: center;
        color: white;
        position: relative;
    }

    .stat-item {
        border-right: 2px solid #444;
    }

    .stat-item:last-child {
        border-right: none;
    }

    .stat-number {
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .stat-label {
        color: #b2bec3;
        font-size: 18px;
    }

    .secondary-image {
        position: absolute;
        left: -150px;
        top: 50%;
        transform: translateY(-50%);
        width: 350px;
        height: 350px;
        border-radius: 50%;
        overflow: hidden;
        border: 15px solid white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        z-index: 10;
    }

    .secondary-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pink-circle-bg {
        position: absolute;
        left: -170px;
        top: 50%;
        transform: translateY(-50%);
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,105,180,0.3) 0%, rgba(255,105,180,0.1) 40%, transparent 70%);
        border-radius: 50%;
        z-index: 1;
    }

    @media (max-width: 968px) {
        .hero {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .hero-content h1 {
            font-size: 48px;
        }

        .cta-buttons {
            justify-content: center;
        }

        .stats-section {
            grid-template-columns: 1fr;
            gap: 30px;
            padding: 50px 30px;
            margin-left: 0;
        }

        .stat-item {
            border-right: none;
            border-bottom: 2px solid #444;
            padding-bottom: 20px;
        }

        .stat-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .secondary-image {
            display: none;
        }
    }
</style>

<?php
// Include the header


// Stats data
$stats = [
    ['number' => '5k+', 'label' => 'Total Dogs'],
    ['number' => '45k+', 'label' => 'Total Clients'],
    ['number' => '2k+', 'label' => 'Pets Doctors']
];
?>

<style>
    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    .hero {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
        min-height: 600px;
        position: relative;
    }

    /* ... rest of your CSS ... */
</style>

<div class="container">
    <section class="hero">
        <div class="hero-content">
            <h1>
                We Provide you <span class="highlight">Pets</span> 🐕
            </h1>
            <p>
                Adopt your favourite Pets from us now!!
            </p>
            <div class="cta-buttons">
                <a href="index.php?page=adopt_pet" class="btn-primary">Get started</a>
                <a href="#" class="link-secondary">Show process</a>
            </div>
        </div>

        <div class="hero-image">
            <div class="paw-print paw-1">🐾</div>
            <div class="paw-print paw-2">🐾</div>
            <div class="paw-print paw-3">🐾</div>

            <div class="pink-blob"></div>

            <!-- Main image -->
            <img class="main-image" 
                 src="https://img.freepik.com/free-photo/pretty-woman-hoodie-holds-dog-pink-background-charming-dark-haired-lady-grey-outfit-plays-with-corgi-isolated_197531-18537.jpg"
                 alt="Main Dog">

            <!-- Dog circles -->
            <div class="dog-circle" style="top: 20%; right: 5%;">
                <img src="https://img.freepik.com/free-photo/pug-dog-isolated-white-background_2829-11416.jpg" alt="Dog 1">
            </div>
            <div class="dog-circle" style="top: 10%; left: 15%;">
                <img src="https://hips.hearstapps.com/hmg-prod/images/dog-puppy-on-garden-royalty-free-image-1586966191.jpg" alt="Dog 2">
            </div>
            <div class="dog-circle" style="bottom: 5%; left: 10%;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTK3uu45UWvHLN6-4kjP9lfUIv0MzXiEEgNeQ&s" alt="Dog 3">
            </div>
            <div class="dog-circle" style="bottom: 15%; right: 10%;">
                <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1" alt="Dog 4">
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="pink-circle-bg"></div>
        <div class="secondary-image">
            <img src="https://st4.depositphotos.com/23223598/29832/i/450/depositphotos_298322524-stock-photo-happy-young-woman-holding-cute.jpg" alt="Secondary Dog">
        </div>

        <?php foreach ($stats as $stat): ?>
            <div class="stat-item">
                <div class="stat-number"><?php echo htmlspecialchars($stat['number']); ?></div>
                <div class="stat-label"><?php echo htmlspecialchars($stat['label']); ?></div>
            </div>
        <?php endforeach; ?>
    </section>
</div>
<?php
} else if (in_array($page, $allowedPages)) {
    // For all other pages, include their respective PHP files
    include $page . '.php';
} else {
    // If page is not allowed
    echo "<h2>Page not found!</h2>";
}
?>