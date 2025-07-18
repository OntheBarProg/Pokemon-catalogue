<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Initialize error and success arrays
$errors = [];
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $card_name = filter_input(INPUT_POST, 'card_name', FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $rarity = filter_input(INPUT_POST, 'rarity', FILTER_SANITIZE_STRING);
    
    // Validate inputs
    if (empty($card_name)) {
        $errors[] = "Card name is required";
    }
    if (!in_array($type, ['Fire', 'Water', 'Grass', 'Electric', 'Psychic'])) {
        $errors[] = "Invalid card type selected";
    }
    if (!in_array($rarity, ['Common', 'Full Art', 'Reverse Holo'])) {
        $errors[] = "Invalid rarity selected";
    }

    // Handle file upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        $image = $_FILES["image"]["name"];
        $image_tmp = $_FILES["image"]["tmp_name"];
        $image_type = $_FILES["image"]["type"];
        $image_size = $_FILES["image"]["size"];
        $upload_dir = "uploads/";
        $image_path = $upload_dir . basename($image);

        // Validate file
        if (!in_array($image_type, $allowed_types)) {
            $errors[] = "Only JPEG, PNG, or GIF images are allowed";
        }
        if ($image_size > $max_size) {
            $errors[] = "Image size must not exceed 5MB";
        }
        if (file_exists($image_path)) {
            $errors[] = "An image with this name already exists";
        }

        // Create uploads directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Move uploaded file
        if (empty($errors) && !move_uploaded_file($image_tmp, $image_path)) {
            $errors[] = "Failed to upload image. Check directory permissions or space.";
        }
    } else {
        $errors[] = "Image upload failed or no image provided. Error code: " . $_FILES["image"]["error"];
    }

    // Proceed if no errors
    if (empty($errors)) {
        // Insert into database using prepared statement (removed user_id)
        $query = "INSERT INTO cards (card_name, type, rarity, image) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $card_name, $type, $rarity, $image_path);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Pokémon card added successfully!";
        } else {
            $errors[] = "Failed to add card to database";
            // Delete uploaded file if database insertion fails
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Card Shop - Add Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('anime-art-fon-tekstura-pokemon-4500.jpg'); /* Pokémon background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .add-card-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.85); /* Semi-transparent for readability */
            border: 2px solid #ffcb05; /* Pokémon yellow border */
        }
        .error-message, .success-message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.9);
        }
        .error-message {
            color: #dc3545;
        }
        .success-message {
            color: #28a745;
        }
        .form-label {
            font-weight: bold;
            color: #2a75bb; /* Pokémon blue */
        }
        .btn-primary {
            background-color: #ffcb05; /* Pokémon yellow */
            border-color: #ffcb05;
            color: #2a75bb; /* Pokémon blue */
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #2a75bb;
            border-color: #2a75bb;
            color: #ffcb05;
        }
        .btn-secondary {
            background-color: #3c5aa6; /* Pokémon darker blue */
            border-color: #3c5aa6;
            color: #ffffff;
        }
        .btn-secondary:hover {
            background-color: #ffcb05;
            border-color: #ffcb05;
            color: #2a75bb;
        }
        .form-select, .form-control {
            border-color: #2a75bb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="add-card-container">
            <h2 class="text-center mb-4" style="color: #2a75bb;">Add New Pokémon Card</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <p><?php echo htmlspecialchars($success); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="card_name" class="form-label">Card Name</label>
                    <input type="text" class="form-control" id="card_name" name="card_name" 
                           value="<?php echo isset($card_name) ? htmlspecialchars($card_name) : ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="" disabled <?php echo !isset($type) ? 'selected' : ''; ?>>Select Type</option>
                        <option value="Fire" <?php echo isset($type) && $type == 'Fire' ? 'selected' : ''; ?>>Fire</option>
                        <option value="Water" <?php echo isset($type) && $type == 'Water' ? 'selected' : ''; ?>>Water</option>
                        <option value="Grass" <?php echo isset($type) && $type == 'Grass' ? 'selected' : ''; ?>>Grass</option>
                        <option value="Electric" <?php echo isset($type) && $type == 'Electric' ? 'selected' : ''; ?>>Electric</option>
                        <option value="Psychic" <?php echo isset($type) && $type == 'Psychic' ? 'selected' : ''; ?>>Psychic</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="rarity" class="form-label">Rarity</label>
                    <select class="form-select" id="rarity" name="rarity" required>
                        <option value="" disabled <?php echo !isset($rarity) ? 'selected' : ''; ?>>Select Rarity</option>
                        <option value="Common" <?php echo isset($rarity) && $rarity == 'Common' ? 'selected' : ''; ?>>Common</option>
                        <option value="Full Art" <?php echo isset($rarity) && $rarity == 'Full Art' ? 'selected' : ''; ?>>Full Art</option>
                        <option value="Reverse Holo" <?php echo isset($rarity) && $rarity == 'Reverse Holo' ? 'selected' : ''; ?>>Reverse Holo</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Card Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/gif" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                    <button type="submit" class="btn btn-primary">Add Card</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>