<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Pets</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
           
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 60px;
        }

        .header h2 {
            font-size: 56px;
            color: #2d3436;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .header .highlight {
            color: #ff69b4;
        }

        .header p {
            color: #636e72;
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
        }

        .stats-bar {
            background: linear-gradient(135deg, #2d3436 0%, #1e272e 100%);
            border-radius: 20px;
            padding: 30px 50px;
            margin-bottom: 50px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .stat-item {
            text-align: center;
            padding: 0 30px;
            border-right: 2px solid #444;
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-number {
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 5px;
            background: linear-gradient(135deg, #ff6348 0%, #ff69b4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: #b2bec3;
            font-size: 16px;
        }

        .pets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .pet-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .pet-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .pet-image-container {
            width: 100%;
            height: 280px;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
            position: relative;
        }

        .pet-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .pet-card:hover .pet-image {
            transform: scale(1.1);
        }

        .no-image {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            color: #ddd;
        }

        .pet-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            color: #2d3436;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .pet-info {
            padding: 25px;
        }

        .pet-name {
            font-size: 28px;
            font-weight: bold;
            color: #2d3436;
            margin-bottom: 10px;
        }

        .pet-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 15px;
        }

        .detail-row {
            display: flex;
            align-items: center;
            color: #636e72;
            font-size: 15px;
        }

        .detail-icon {
            font-size: 18px;
            margin-right: 10px;
            min-width: 25px;
        }

        .detail-label {
            font-weight: 600;
            margin-right: 8px;
            color: #2d3436;
        }

        .pet-date {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
            color: #b2bec3;
            font-size: 13px;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .empty-icon {
            font-size: 100px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 32px;
            color: #2d3436;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #636e72;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .btn-add-pet {
            background: linear-gradient(135deg, #ff6348 0%, #ff4757 100%);
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

        .btn-add-pet:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 99, 72, 0.3);
        }

        .btn-add-pet::before {
            content: '➕';
        }

        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 40px;
            display: flex;
            gap: 20px;
            align-items: center;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
            flex-wrap: wrap;
        }

        .filter-label {
            font-weight: 600;
            color: #2d3436;
            font-size: 16px;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
        }

        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #e0e0e0;
            background: white;
            border-radius: 20px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            color: #636e72;
        }

        .filter-btn:hover,
        .filter-btn.active {
            border-color: #ff69b4;
            background: #fff5f9;
            color: #ff69b4;
        }

        @media (max-width: 768px) {
            .header h2 {
                font-size: 36px;
            }

            .stats-bar {
                flex-direction: column;
                gap: 20px;
                padding: 30px 20px;
            }

            .stat-item {
                border-right: none;
                border-bottom: 2px solid #444;
                padding: 15px 0;
                width: 100%;
            }

            .stat-item:last-child {
                border-bottom: none;
            }

            .pets-grid {
                grid-template-columns: 1fr;
            }

            .filter-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-buttons {
                width: 100%;
            }
        }

        .paw-decoration {
            position: fixed;
            color: #ff69b4;
            opacity: 0.05;
            font-size: 150px;
            z-index: 0;
            pointer-events: none;
        }

        .paw-1 {
            top: 10%;
            right: 5%;
            transform: rotate(45deg);
        }

        .paw-2 {
            bottom: 10%;
            left: 5%;
            transform: rotate(-45deg);
        }

        .container {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="paw-decoration paw-1">🐾</div>
    <div class="paw-decoration paw-2">🐾</div>

    <div class="container">
        <div class="header">
            <h2>Available <span class="highlight">Pets</span></h2>
            <p>Find your perfect companion from our adorable collection</p>
        </div>

        <?php
        // Include DB connection
        include 'db_connect.php';

        $collection = $db->pets;
        $pets = $collection->find();
        $petsArray = iterator_to_array($pets);
        
        // Calculate statistics
        $totalPets = count($petsArray);
        $petTypes = [];
        foreach($petsArray as $pet) {
            $type = $pet['type'] ?? 'Unknown';
            $petTypes[$type] = ($petTypes[$type] ?? 0) + 1;
        }
        $uniqueTypes = count($petTypes);
        
        // Get most popular type
        $mostPopular = 'N/A';
        if (!empty($petTypes)) {
            arsort($petTypes);
            $mostPopular = array_key_first($petTypes);
        }
        ?>

        <!-- Statistics Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number"><?php echo $totalPets; ?></div>
                <div class="stat-label">Total Pets</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $uniqueTypes; ?></div>
                <div class="stat-label">Pet Types</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $mostPopular; ?></div>
                <div class="stat-label">Most Popular</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <span class="filter-label">Filter by:</span>
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterPets('all')">All Pets</button>
                <?php
                foreach($petTypes as $type => $count) {
                    echo "<button class='filter-btn' onclick='filterPets(\"$type\")'>$type ($count)</button>";
                }
                ?>
            </div>
        </div>

        <?php if($totalPets > 0): ?>
            <!-- Pets Grid -->
            <div class="pets-grid" id="petsGrid">
                <?php
                foreach($petsArray as $pet) {
                    $name = htmlspecialchars($pet['name'] ?? 'Unknown');
                    $type = htmlspecialchars($pet['type'] ?? 'Unknown');
                    $breed = htmlspecialchars($pet['breed'] ?? 'N/A');
                    $age = htmlspecialchars($pet['age'] ?? 'N/A');
                    
                    // Format date
                    $date = 'N/A';
                    if (isset($pet['created_at'])) {
                        $timestamp = $pet['created_at']->toDateTime();
                        $date = $timestamp->format('M d, Y');
                    }
                    
                    // Type emoji mapping
                    $typeEmojis = [
                        'Dog' => '🐕',
                        'Cat' => '🐈',
                        'Bird' => '🦜',
                        'Rabbit' => '🐰',
                        'Hamster' => '🐹',
                        'Fish' => '🐠',
                        'Other' => '🐾'
                    ];
                    $emoji = $typeEmojis[$type] ?? '🐾';
                    
                    echo "<div class='pet-card' data-type='$type'>";
                    echo "<div class='pet-image-container'>";
                    
                    // Display image if available
                    if (isset($pet['image']) && $pet['image'] instanceof MongoDB\BSON\Binary) {
                        $imageType = $pet['image_type'] ?? 'image/jpeg';
                        $imageData = base64_encode($pet['image']->getData());
                        echo "<img src='data:$imageType;base64,$imageData' alt='$name' class='pet-image'>";
                    } else {
                        echo "<div class='no-image'>$emoji</div>";
                    }
                    
                    echo "<div class='pet-type-badge'>$type</div>";
                    echo "</div>";
                    
                    echo "<div class='pet-info'>";
                    echo "<div class='pet-name'>$name</div>";
                    
                    echo "<div class='pet-details'>";
                    echo "<div class='detail-row'>";
                    echo "<span class='detail-icon'>🏷️</span>";
                    echo "<span class='detail-label'>Breed:</span>";
                    echo "<span>$breed</span>";
                    echo "</div>";
                    
                    echo "<div class='detail-row'>";
                    echo "<span class='detail-icon'>🎂</span>";
                    echo "<span class='detail-label'>Age:</span>";
                    echo "<span>$age</span>";
                    echo "</div>";
                    echo "</div>";
                    
                    echo "<div class='pet-date'>Added on $date</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">🐾</div>
                <h3>No Pets Available Yet</h3>
                <p>Be the first to add a furry friend to our collection!</p>
                <a href="add_pet.php" class="btn-add-pet">Add Your First Pet</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function filterPets(type) {
            const cards = document.querySelectorAll('.pet-card');
            const buttons = document.querySelectorAll('.filter-btn');
            
            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter cards
            cards.forEach(card => {
                if (type === 'all' || card.dataset.type === type) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        }

        // Add smooth transitions
        document.querySelectorAll('.pet-card').forEach(card => {
            card.style.transition = 'all 0.3s ease';
        });
    </script>
</body>
</html>