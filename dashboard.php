<?php
session_start();
include("db.php"); // Include database connection for potential future queries

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Sanitize username for display
$username = htmlspecialchars($_SESSION["username"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Card Shop - Dashboard</title>
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
        .dashboard-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.85); /* Semi-transparent for readability */
            border: 2px solid #ffcb05; /* Pokémon yellow border */
        }
        .welcome-message {
            color: #2a75bb; /* Pokémon blue */
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: #2a75bb; /* Pokémon blue */
            font-weight: bold;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #ffcb05; /* Pokémon yellow */
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
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-container">
            <h2 class="welcome-message text-center mb-4">Welcome to the Pokémon Card Shop, <?php echo $username; ?>!</h2>
            <ul class="nav flex-column mb-4">
                <li class="nav-item">
                    <a href="add_card.php" class="nav-link">Add New Pokémon Card</a>
                </li>
                <li class="nav-item">
                    <a href="show_cards.php" class="nav-link">Show All Pokémon Cards</a>
                </li>
                <li class="nav-item">
                    <a href="search_card.php" class="nav-link">Find a Pokémon Card</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Logout</a>
                </li>
            </ul>
            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
                <a href="logout.php" class="btn btn-primary">Logout</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>