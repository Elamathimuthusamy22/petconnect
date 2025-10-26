<?php
session_start();
include 'db_connect.php';

$loggedIn = isset($_SESSION['user_id']);
$userId = $loggedIn ? $_SESSION['user_id'] : null;

// Handle adoption request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adopt_id'])) {
    header('Content-Type: application/json');
    if (!$loggedIn) exit(json_encode(['success'=>false,'message'=>'Login required']));

    $petIdRaw = $_POST['adopt_id'];
    if (!preg_match('/^[a-f0-9]{24}$/i', $petIdRaw)) exit(json_encode(['success'=>false,'message'=>'Invalid Pet ID']));

    $petId = new MongoDB\BSON\ObjectId($petIdRaw);

    $pet = $db->pets->findOne(['_id'=>$petId]);
    if (!$pet) exit(json_encode(['success'=>false,'message'=>'Pet not found']));

    $status = $pet['status'] ?? 'available';
    if ($status === 'pending') exit(json_encode(['success'=>false,'message'=>'Waiting for admin approval']));
    if ($status === 'adopted') exit(json_encode(['success'=>false,'message'=>'Already adopted']));

    $update = $db->pets->updateOne(
        ['_id'=>$petId, 'status'=>$status],
        ['$set'=>['status'=>'pending','adopter_id'=>$userId,'requested_at'=>new MongoDB\BSON\UTCDateTime()]]
    );

    if ($update->getModifiedCount() > 0) {
        $db->adoptions->insertOne([
            'user_id'=>$userId,
            'pet_id'=>$petId,
            'status'=>'pending',
            'requested_at'=>new MongoDB\BSON\UTCDateTime()
        ]);
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false,'message'=>'Could not adopt pet']);
    }
    exit;
}

// Fetch pets for display
$petsCursor = $db->pets->find([], ['sort'=>['_id'=>-1]]);
$petsArray = iterator_to_array($petsCursor);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Available Pets</title>
<style>
body{font-family:Arial,sans-serif;margin:0;padding:30px;background:#fafafa;}
.container{max-width:1200px;margin:auto;}
h2{text-align:center;margin-bottom:30px;color:#2d3436;}
.pets-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;}
.pet-card{background:#fff;padding:20px;border-radius:15px;box-shadow:0 4px 15px rgba(0,0,0,0.1);transition:0.3s;}
.pet-card:hover{transform:translateY(-5px);}
.pet-name{font-size:20px;font-weight:bold;color:#2d3436;}
.pet-details{margin:10px 0;color:#636e72;}
.pet-status{margin-top:10px;font-weight:bold;}
.adopt-btn{margin-top:10px;padding:10px 18px;border:none;border-radius:25px;background:linear-gradient(135deg,#ff6348,#ff69b4);color:white;font-weight:bold;cursor:pointer;transition:0.3s;}
.adopt-btn:hover{opacity:0.9;}
.adopt-btn:disabled{background:#ccc;cursor:not-allowed;}
</style>
</head>
<body>
<div class="container">
<h2>Available Pets for Adoption</h2>
<div class="pets-grid">
<?php foreach($petsArray as $pet): 
    $id = (string)$pet['_id'];
    $status = $pet['status'] ?? 'available';
    $name = htmlspecialchars($pet['name'] ?? 'Unknown');
    $type = htmlspecialchars($pet['type'] ?? 'Unknown');
    $breed = htmlspecialchars($pet['breed'] ?? 'N/A');
    $age = htmlspecialchars($pet['age'] ?? 'N/A');
?>
<div class="pet-card">
    <div class="pet-name"><?= $name ?> (<?= $type ?>)</div>
    <div class="pet-details">
        <div>Breed: <?= $breed ?></div>
        <div>Age: <?= $age ?></div>
    </div>
    <div class="pet-status">Status: <?= ucfirst($status) ?></div>

    <?php if($status==='available' && $loggedIn): ?>
        <button class="adopt-btn" onclick="adoptPet('<?= $id ?>', this)">Adopt</button>
    <?php elseif($status==='pending'): ?>
        <button class="adopt-btn" disabled>Pending Approval</button>
    <?php elseif($status==='adopted'): ?>
        <button class="adopt-btn" disabled>Adopted</button>
    <?php endif; ?>
</div>
<?php endforeach; ?>
</div>
</div>

<script>
function adoptPet(petId, btn){
    if(!confirm("Do you want to adopt this pet?")) return;
    btn.disabled = true;
    fetch("", {
        method:"POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body:"adopt_id="+petId
    }).then(r=>r.json()).then(d=>{
        if(d.success){
            btn.textContent="Pending Approval";
            btn.style.background="#ccc";
        } else {
            alert(d.message);
            btn.disabled=false;
        }
    }).catch(()=>{
        alert("Server error");
        btn.disabled=false;
    });
}
</script>
</body>
</html>
