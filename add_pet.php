<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Pet</title>
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

        .highlight {
            color: #ff69b4;
        }

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
        select {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s;
            outline: none;
            background: #f8f9fa;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: #ff69b4;
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 105, 180, 0.1);
        }

        .upload-area {
            border: 3px dashed #e0e0e0;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .upload-area:hover {
            border-color: #ff69b4;
            background: #fff5f9;
        }

        .upload-area.dragover {
            border-color: #ff6348;
            background: #fff0ed;
        }

        input[type="file"] {
            display: none;
        }

        .upload-icon {
            font-size: 48px;
            color: #ff69b4;
            margin-bottom: 15px;
        }

        .upload-text {
            color: #636e72;
            font-size: 14px;
        }

        .upload-text strong {
            color: #ff69b4;
        }

        .preview-container {
            margin-top: 20px;
            display: none;
        }

        .preview-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
            content: '▶';
            font-size: 14px;
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

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            h2 {
                font-size: 28px;
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
include 'db_connect.php';

        
        if(isset($_POST['add_pet'])){
            try {
                $collection = $db->pets;
                
                // Handle image upload
                $imageData = null;
                if(isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] == 0) {
                    $imageData = new MongoDB\BSON\Binary(
                        file_get_contents($_FILES['pet_image']['tmp_name']),
                        MongoDB\BSON\Binary::TYPE_GENERIC
                    );
                }
                
                // Insert pet data
                $result = $collection->insertOne([
                    'name' => $_POST['name'],
                    'type' => $_POST['type'],
                    'breed' => $_POST['breed'] ?? '',
                    'age' => $_POST['age'] ?? '',
                    'image' => $imageData,
                    'image_type' => $_FILES['pet_image']['type'] ?? null,
                    'created_at' => new MongoDB\BSON\UTCDateTime()
                ]);
                
                if($result->getInsertedCount() > 0) {
                    echo "<div class='success-message'>🎉 Pet added successfully!</div>";
                }
            } catch (Exception $e) {
                echo "<div class='error-message'>Error: " . $e->getMessage() . "</div>";
            }
        }
        ?>

        <h2>Add Your <span class="highlight">Pet</span></h2>
        <p class="subtitle">Fill in the details below to add your furry friend</p>

        <form method="POST" enctype="multipart/form-data" id="petForm">
            <div class="form-group">
                <label for="name">Pet Name *</label>
                <input type="text" id="name" name="name" placeholder="e.g., Max, Bella, Luna" required>
            </div>

            <div class="form-group">
                <label for="type">Pet Type *</label>
                <select id="type" name="type" required>
                    <option value="">Select pet type</option>
                    <option value="Dog">Dog 🐕</option>
                    <option value="Cat">Cat 🐈</option>
                    <option value="Bird">Bird 🦜</option>
                    <option value="Rabbit">Rabbit 🐰</option>
                    <option value="Hamster">Hamster 🐹</option>
                    <option value="Fish">Fish 🐠</option>
                    <option value="Other">Other 🐾</option>
                </select>
            </div>

            <div class="form-group">
                <label for="breed">Breed</label>
                <input type="text" id="breed" name="breed" placeholder="e.g., Golden Retriever, Persian">
            </div>

            <div class="form-group">
                <label for="age">Age</label>
                <input type="text" id="age" name="age" placeholder="e.g., 2 years, 6 months">
            </div>

            <div class="form-group">
                <label>Pet Photo</label>
                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">📷</div>
                    <div class="upload-text">
                        <strong>Click to upload</strong> or drag and drop<br>
                        PNG, JPG or JPEG (Max 5MB)
                    </div>
                    <input type="file" id="pet_image" name="pet_image" accept="image/*">
                </div>
                <div class="preview-container" id="previewContainer">
                    <img id="previewImage" class="preview-image" alt="Preview">
                </div>
            </div>

            <button type="submit" name="add_pet" class="btn-submit">Add Pet</button>
        </form>
    </div>

    <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('pet_image');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');

        // Click to upload
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        // File selection
        fileInput.addEventListener('change', handleFileSelect);

        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect();
            }
        });

        function handleFileSelect() {
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                    uploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>