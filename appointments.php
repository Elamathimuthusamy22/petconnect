<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
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
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            position: relative;
        }

        h2 {
            font-size: 36px;
            color: #2d3436;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #636e72;
            margin-bottom: 40px;
            font-size: 16px;
        }

        /* .highlight {
            color: #ff69b4;
        } */

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            color: #2d3436;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select,
        textarea {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s;
            outline: none;
            background: #f8f9fa;
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus,
        select:focus,
        textarea:focus {
            border-color: #ff69b4;
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon::before {
            content: attr(data-icon);
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            pointer-events: none;
            z-index: 1;
        }

        .input-icon input {
            padding-left: 50px;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #ff6348 0%, #ff4757 100%);
            color: white;
            padding: 18px 35px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 99, 72, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit::after {
            content: '📅';
            font-size: 18px;
        }

        .success-message {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
            animation: slideDown 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .success-message::before {
            content: '✓';
            font-size: 24px;
            background: white;
            color: #00b894;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-message {
            background: linear-gradient(135deg, #d63031 0%, #e17055 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .paw-decoration {
            position: absolute;
            color: #ff69b4;
            opacity: 0.1;
            font-size: 80px;
        }

        .paw-1 {
            top: -20px;
            right: -20px;
            transform: rotate(45deg);
        }

        .paw-2 {
            bottom: -20px;
            left: -20px;
            transform: rotate(-45deg);
        }

        .info-box {
            background: #fff5f9;
            border-left: 4px solid #ff69b4;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            color: #636e72;
            font-size: 14px;
        }

        .info-box strong {
            color: #2d3436;
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .time-slot {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
            font-size: 14px;
            font-weight: 500;
        }

        .time-slot:hover {
            border-color: #ff69b4;
            background: #fff5f9;
        }

        .time-slot.selected {
            border-color: #ff69b4;
            background: #ff69b4;
            color: white;
        }

        input[type="time"] {
            display: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            h2 {
                font-size: 28px;
            }

            .time-slots {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="paw-decoration paw-1">🐾</div>
        <div class="paw-decoration paw-2">🐾</div>

        <?php
        // Include DB connection
        require_once 'db_connect.php';
        
        $collection = $db->appointments;
        
        if(isset($_POST['book'])){
            try {
                // Validate input
                $name = trim($_POST['name']);
                $pet_name = trim($_POST['pet_name']);
                $date = $_POST['date'];
                $time = $_POST['time'] ?? '';
                $service = $_POST['service'] ?? '';
                $notes = trim($_POST['notes'] ?? '');
                
                // Check if date is not in the past
                $appointmentDate = new DateTime($date);
                $today = new DateTime();
                $today->setTime(0, 0, 0);
                
                if($appointmentDate < $today) {
                    throw new Exception("Appointment date cannot be in the past.");
                }
                
                // Insert appointment
                $result = $collection->insertOne([
                    'name' => $name,
                    'pet_name' => $pet_name,
                    'date' => $date,
                    'time' => $time,
                    'service' => $service,
                    'notes' => $notes,
                    'status' => 'pending',
                    'created_at' => new MongoDB\BSON\UTCDateTime()
                ]);
                
                if($result->getInsertedCount() > 0) {
                    echo "<div class='success-message'>Appointment booked successfully!</div>";
                }
            } catch (Exception $e) {
                echo "<div class='error-message'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
        ?>

        <h2>Book <span class="highlight">Appointment</span></h2>
        <p class="subtitle">Schedule a visit for your furry friend</p>

        <div class="info-box">
            <strong>📍 Note:</strong> Our clinic is open Monday to Saturday, 9 AM - 6 PM. Please book at least 24 hours in advance.
        </div>

        <form method="POST" id="appointmentForm">
            <div class="form-group">
                <label for="name">Your Name *</label>
                <div class="input-icon" data-icon="👤">
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="pet_name">Pet Name *</label>
                <div class="input-icon" data-icon="🐾">
                    <input type="text" id="pet_name" name="pet_name" placeholder="Enter your pet's name" required>
                </div>
            </div>

            <div class="form-group">
                <label for="service">Service Type *</label>
                <select id="service" name="service" required>
                    <option value="">Select a service</option>
                    <option value="General Checkup">General Checkup</option>
                    <option value="Vaccination">Vaccination</option>
                    <option value="Grooming">Grooming</option>
                    <option value="Surgery">Surgery Consultation</option>
                    <option value="Dental Care">Dental Care</option>
                    <option value="Emergency">Emergency</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Appointment Date *</label>
                <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>

            <div class="form-group">
                <label>Preferred Time *</label>
                <div class="time-slots">
                    <div class="time-slot" data-time="09:00">9:00 AM</div>
                    <div class="time-slot" data-time="10:00">10:00 AM</div>
                    <div class="time-slot" data-time="11:00">11:00 AM</div>
                    <div class="time-slot" data-time="12:00">12:00 PM</div>
                    <div class="time-slot" data-time="14:00">2:00 PM</div>
                    <div class="time-slot" data-time="15:00">3:00 PM</div>
                    <div class="time-slot" data-time="16:00">4:00 PM</div>
                    <div class="time-slot" data-time="17:00">5:00 PM</div>
                </div>
                <input type="time" id="time" name="time" required>
            </div>

            <div class="form-group">
                <label for="notes">Additional Notes (Optional)</label>
                <textarea id="notes" name="notes" placeholder="Any special instructions or concerns about your pet..."></textarea>
            </div>

            <button type="submit" name="book" class="btn-submit">Book Appointment</button>
        </form>
    </div>

    <script>
        // Time slot selection
        const timeSlots = document.querySelectorAll('.time-slot');
        const timeInput = document.getElementById('time');

        timeSlots.forEach(slot => {
            slot.addEventListener('click', function() {
                // Remove selected class from all slots
                timeSlots.forEach(s => s.classList.remove('selected'));
                
                // Add selected class to clicked slot
                this.classList.add('selected');
                
                // Set the time input value
                timeInput.value = this.dataset.time;
            });
        });

        // Form validation
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            if (!timeInput.value) {
                e.preventDefault();
                alert('Please select a preferred time slot.');
                return false;
            }
        });

        // Set minimum date to tomorrow
        const dateInput = document.getElementById('date');
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.min = tomorrow.toISOString().split('T')[0];

        // Disable Sundays
        dateInput.addEventListener('input', function() {
            const selectedDate = new Date(this.value);
            if (selectedDate.getDay() === 0) {
                alert('Sorry, we are closed on Sundays. Please select another date.');
                this.value = '';
            }
        });
    </script>
</body>
</html>