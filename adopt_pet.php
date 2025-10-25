<?php
session_start();
include 'db_connect.php';

$collection = $db->pets;
$loggedIn = isset($_SESSION['user_id']); // check login

// Handle adoption request via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adopt_id'])) {
    if (!$loggedIn) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to adopt']);
        exit;
    }

    $petId = new MongoDB\BSON\ObjectId($_POST['adopt_id']);
    $userId = $_SESSION['user_id'];

    // Update pet status and store adopter
    $result = $collection->updateOne(
        ['_id' => $petId, 'status' => 'available'], // only if available
        ['$set' => [
            'status' => 'pending',
            'adopter_id' => $userId,
            'adopted_at' => new MongoDB\BSON\UTCDateTime()
        ]]
    );

    if ($result->getModifiedCount() > 0) {
        // Optional: add to adoptions collection
        $db->adoptions->insertOne([
            'user_id' => $userId,
            'pet_id' => $petId,
            'status' => 'pending',
            'requested_at' => new MongoDB\BSON\UTCDateTime()
        ]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Pet already adopted or pending']);
    }
    exit;
}

// Fetch all pets
$pets = $collection->find([], ['sort' => ['_id' => -1]]);
$petsArray = iterator_to_array($pets);

// Stats
$totalPets = count($petsArray);
$petTypes = [];
foreach ($petsArray as $pet) {
    $type = $pet['type'] ?? 'Unknown';
    $petTypes[$type] = ($petTypes[$type] ?? 0) + 1;
}
$uniqueTypes = count($petTypes);
$mostPopular = !empty($petTypes) ? array_key_first($petTypes) : 'N/A';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Available Pets</title>
<style>
body{font-family:Arial;margin:0;padding:40px;background:#fafafa}
.container{max-width:1200px;margin:auto}
.header{text-align:center;margin-bottom:40px}
.header h2{font-size:48px;color:#2d3436}
.header .highlight{color:#ff69b4}
.stats-bar{display:flex;justify-content:space-around;align-items:center;
background:linear-gradient(135deg,#2d3436,#1e272e);color:#fff;border-radius:20px;padding:20px;margin-bottom:30px}
.stat-item{text-align:center}
.stat-number{font-size:36px;background:linear-gradient(135deg,#ff6348,#ff69b4);
-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.stat-label{color:#b2bec3}
.pets-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(350px,1fr));gap:25px}
.pet-card{background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1);
transition:0.3s;position:relative}
.pet-card:hover{transform:translateY(-8px)}
.pet-image-container{height:250px;overflow:hidden;position:relative}
.pet-image{width:100%;height:100%;object-fit:cover;transition:0.3s}
.pet-card:hover .pet-image{transform:scale(1.05)}
.no-image{display:flex;align-items:center;justify-content:center;font-size:70px;height:100%;background:#f0f0f0}
.pet-type-badge{position:absolute;top:10px;right:10px;background:#fff;padding:6px 12px;border-radius:15px;font-weight:bold}
.pet-info{padding:20px}
.pet-name{font-size:24px;font-weight:bold;color:#2d3436}
.pet-details{margin:10px 0}
.detail-row{margin:5px 0;color:#636e72}
.pet-date{font-size:13px;color:#aaa;margin-top:10px}
.adopt-btn{margin-top:15px;padding:10px 20px;border:none;border-radius:25px;background:linear-gradient(135deg,#ff6348,#ff69b4);color:white;font-weight:bold;cursor:pointer;transition:0.3s}
.adopt-btn:hover{opacity:0.9}
.adopt-btn:disabled{background:#ccc;cursor:not-allowed}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Available <span class="highlight">Pets</span></h2>
        <p>Find your perfect companion!</p>
    </div>

    <div class="stats-bar">
        <div class="stat-item"><div class="stat-number"><?php echo $totalPets; ?></div><div class="stat-label">Total Pets</div></div>
        <div class="stat-item"><div class="stat-number"><?php echo $uniqueTypes; ?></div><div class="stat-label">Pet Types</div></div>
        <div class="stat-item"><div class="stat-number"><?php echo $mostPopular; ?></div><div class="stat-label">Most Popular</div></div>
    </div>

    <div class="pets-grid">
        <?php
        if ($totalPets > 0) {
            foreach ($petsArray as $pet) {
                $id = (string)$pet['_id'];
                $name = htmlspecialchars($pet['name'] ?? 'Unknown');
                $type = htmlspecialchars($pet['type'] ?? 'Unknown');
                $breed = htmlspecialchars($pet['breed'] ?? 'N/A');
                $age = htmlspecialchars($pet['age'] ?? 'N/A');
                $status = $pet['status'] ?? 'available';

                $date = 'N/A';
                if (isset($pet['created_at'])) {
                    $timestamp = $pet['created_at']->toDateTime();
                    $date = $timestamp->format('M d, Y');
                }

                $emoji = ['Dog'=>'🐕','Cat'=>'🐈','Bird'=>'🦜','Rabbit'=>'🐰','Hamster'=>'🐹','Fish'=>'🐠'][$type] ?? '🐾';

                echo "<div class='pet-card'>";
                echo "<div class='pet-image-container'>";
                if (isset($pet['image']) && $pet['image'] instanceof MongoDB\BSON\Binary) {
                    $imgType = $pet['image_type'] ?? 'image/jpeg';
                    $imgData = base64_encode($pet['image']->getData());
                    echo "<img src='data:$imgType;base64,$imgData' alt='$name' class='pet-image'>";
                } else {
                    echo "<div class='no-image'>$emoji</div>";
                }
                echo "<div class='pet-type-badge'>$type</div>";
                echo "</div>";

                echo "<div class='pet-info'>";
                echo "<div class='pet-name'>$name</div>";
                echo "<div class='pet-details'>";
                echo "<div class='detail-row'><b>Breed:</b> $breed</div>";
                echo "<div class='detail-row'><b>Age:</b> $age</div>";
                echo "</div>";
                echo "<div class='pet-date'>Added on $date</div>";

                // Adopt button
                if ($status === 'pending') {
                    echo "<button class='adopt-btn' disabled>Pending Approval</button>";
                } elseif ($status === 'adopted') {
                    echo "<button class='adopt-btn' disabled>Adopted</button>";
                } else {
                    if ($loggedIn) {
                        echo "<button class='adopt-btn' onclick='adoptPet(\"$id\", this)'>Adopt</button>";
                    } else {
                        echo "<button class='adopt-btn' onclick='alert(\"Please login/signup to adopt a pet.\")'>Adopt</button>";
                    }
                }

                echo "</div></div>";
            }
        } else {
            echo "<h3 style='text-align:center;'>No pets available</h3>";
        }
        ?>
    </div>
</div>

<script>
function adoptPet(petId, btn){
    if(!confirm("Do you want to adopt this pet?")) return;
    btn.disabled = true;
    fetch("available_pets.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "adopt_id=" + petId
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            btn.textContent = "Pending Approval";
            btn.style.background = "#ccc";
        } else {
            alert(data.message || "Error adopting pet.");
            btn.disabled = false;
        }
    })
    .catch(() => {
        alert("Error connecting to server.");
        btn.disabled = false;
    });
}
</script>
</body>
</html>
