<?php
session_start();
include 'db_connect.php';

$collection = $db->appointments;
$loggedIn = isset($_SESSION['user_id']); // check if user is logged in

// Handle appointment booking via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    if (!$loggedIn) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to book an appointment.']);
        exit;
    }

    try {
        $userId = $_SESSION['user_id'];
        $name = trim($_POST['name']);
        $pet_name = trim($_POST['pet_name']);
        $service = $_POST['service'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $notes = trim($_POST['notes'] ?? '');

        // Validate date not in the past
        $appointmentDate = new DateTime($date);
        $today = new DateTime();
        $today->setTime(0,0,0);
        if($appointmentDate < $today) {
            throw new Exception("Appointment date cannot be in the past.");
        }

        // Insert appointment
        $result = $collection->insertOne([
            'user_id' => $userId,
            'name' => $name,
            'pet_name' => $pet_name,
            'service' => $service,
            'date' => $date,
            'time' => $time,
            'notes' => $notes,
            'status' => 'pending',
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);

        if($result->getInsertedCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Appointment booked successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to book appointment.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Appointment</title>
<style>
body{font-family:Arial;background:#f5f5f5;padding:30px;}
.container{max-width:600px;margin:auto;background:white;padding:40px;border-radius:20px;box-shadow:0 5px 15px rgba(0,0,0,0.1);position:relative;}
h2{text-align:center;color:#2d3436;margin-bottom:10px;}
.subtitle{text-align:center;color:#636e72;margin-bottom:30px;}
input, select, textarea{width:100%;padding:12px;border-radius:10px;border:1px solid #ccc;margin-bottom:20px;font-size:14px;}
button{width:100%;padding:15px;background:linear-gradient(135deg,#ff6348,#ff69b4);color:white;font-weight:bold;border:none;border-radius:10px;cursor:pointer;font-size:16px;}
button:disabled{background:#ccc;cursor:not-allowed;}
.success-message, .error-message{padding:15px;margin-bottom:20px;border-radius:10px;text-align:center;font-weight:bold;}
.success-message{background:#00cec9;color:white;}
.error-message{background:#d63031;color:white;}
.time-slots{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px;}
.time-slot{padding:10px;border:1px solid #ccc;border-radius:8px;text-align:center;cursor:pointer;transition:0.3s;background:white;}
.time-slot.selected{border-color:#ff69b4;background:#ff69b4;color:white;}
@media(max-width:768px){.time-slots{grid-template-columns:repeat(2,1fr);}}
</style>
</head>
<body>
<div class="container">
    <h2>Book <span class="highlight">Appointment</span></h2>
    <p class="subtitle">Schedule a visit for your furry friend</p>

    <?php if(!$loggedIn): ?>
        <div class="error-message">Please <a href="index.php?page=login" style="color:white;text-decoration:underline;">login</a> to book an appointment.</div>
    <?php endif; ?>

    <form id="appointmentForm">
        <input type="text" name="name" placeholder="Your Name *" required <?php if(!$loggedIn) echo 'disabled'; ?>>
        <input type="text" name="pet_name" placeholder="Pet Name *" required <?php if(!$loggedIn) echo 'disabled'; ?>>

        <select name="service" required <?php if(!$loggedIn) echo 'disabled'; ?>>
            <option value="">Select a Service</option>
            <option value="General Checkup">General Checkup</option>
            <option value="Vaccination">Vaccination</option>
            <option value="Grooming">Grooming</option>
            <option value="Surgery Consultation">Surgery Consultation</option>
            <option value="Dental Care">Dental Care</option>
            <option value="Emergency">Emergency</option>
            <option value="Other">Other</option>
        </select>

        <input type="date" name="date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" <?php if(!$loggedIn) echo 'disabled'; ?>>

        <div class="time-slots">
            <?php 
            $times = ["09:00","10:00","11:00","12:00","14:00","15:00","16:00","17:00"];
            foreach($times as $t): 
                $display = date("g:i A", strtotime($t));
            ?>
            <div class="time-slot" data-time="<?php echo $t; ?>"><?php echo $display; ?></div>
            <?php endforeach; ?>
        </div>
        <input type="hidden" name="time" required>

        <textarea name="notes" placeholder="Additional Notes" <?php if(!$loggedIn) echo 'disabled'; ?>></textarea>
        <button type="submit" <?php if(!$loggedIn) echo 'disabled'; ?>>Book Appointment</button>
    </form>

    <div id="message"></div>
</div>

<script>
const form = document.getElementById('appointmentForm');
const timeSlots = document.querySelectorAll('.time-slot');
const timeInput = form.querySelector('input[name="time"]');
const messageDiv = document.getElementById('message');

timeSlots.forEach(slot => {
    slot.addEventListener('click', function(){
        timeSlots.forEach(s => s.classList.remove('selected'));
        this.classList.add('selected');
        timeInput.value = this.dataset.time;
    });
});

form.addEventListener('submit', function(e){
    e.preventDefault();
    if(!timeInput.value){
        alert('Please select a preferred time slot.');
        return;
    }
    const formData = new FormData(form);
    formData.append('book', '1');

    fetch('appointments.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        messageDiv.innerHTML = data.success ? 
            `<div class="success-message">${data.message}</div>` : 
            `<div class="error-message">${data.message}</div>`;
        if(data.success) form.reset();
        timeSlots.forEach(s => s.classList.remove('selected'));
    })
    .catch(() => {
        messageDiv.innerHTML = `<div class="error-message">Error connecting to server.</div>`;
    });
});
</script>
</body>
</html>
