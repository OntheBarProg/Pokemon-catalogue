<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: /login.php");
    exit();
}

// Fetch all cards
$result = mysqli_query($conn, "SELECT * FROM cards");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Card Shop - All Cards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('anime-art-fon-tekstura-pokemon-4500.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .cards-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.85);
            border: 2px solid #ffcb05;
        }
        .table {
            background-color: rgba(255, 255, 255, 0.9);
        }
        .table th {
            background-color: #2a75bb;
            color: #ffcb05;
            font-weight: bold;
        }
        .table td {
            vertical-align: middle;
        }
        .card-image {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #2a75bb;
        }
        .placeholder-image {
            width: 80px;
            height: 100px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            border: 1px solid #2a75bb;
            color: #6c757d;
            font-size: 12px;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
            font-weight: bold;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
        .btn-secondary {
            background-color: #3c5aa6;
            border-color: #3c5aa6;
            color: #ffffff;
            font-weight: bold;
        }
        .btn-secondary:hover {
            background-color: #ffcb05;
            border-color: #ffcb05;
            color: #2a75bb;
        }
        h2 {
            color: #2a75bb;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .error-message {
            color: #dc3545;
            margin-top: 10px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
        }
        .success-message {
            color: #28a745;
            margin-top: 10px;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cards-container">
            <h2 class="text-center mb-4">All Pokémon Cards</h2>
            <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>
            
            <?php
            // Check for delete feedback
            if (isset($_GET['delete_status'])) {
                if ($_GET['delete_status'] === 'success') {
                    echo '<div class="success-message">Card deleted successfully!</div>';
                } elseif ($_GET['delete_status'] === 'error') {
                    echo '<div class="error-message">Failed to delete card. Please check the error log or try again.</div>';
                }
            }
            ?>

            <?php if (mysqli_num_rows($result) == 0): ?>
                <div class="alert alert-info text-center">
                    No Pokémon cards found. <a href="add_card.php" class="alert-link">Add a new card</a> to start your collection!
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Card Name</th>
                                <th>Type</th>
                                <th>Rarity</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['card_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['rarity']); ?></td>
                                    <td>
                                        <?php
                                       $image_path = "/" . htmlspecialchars($row['image']);
                                        $full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . $image_path;
                                        $full_file_path = $_SERVER['DOCUMENT_ROOT'] . $image_path;
                                        if (file_exists($full_file_path)) {
                                            $headers = @get_headers($full_url);
                                            if ($headers && strpos($headers[0], '200') !== false) {
                                                echo "<img src='$image_path' alt='" . htmlspecialchars($row['card_name']) . "' class='card-image'>";
                                            } else {
                                                echo "<div class='placeholder-image'>Image Unavailable</div><br><span class='error-message'>Image URL inaccessible: $full_url</span>";
                                            }
                                        } else {
                                            echo "<div class='placeholder-image'>No Image</div><br><span class='error-message'>Image not found at: $full_file_path</span>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        
                                          <a href='delete_card.php?id=<?php echo htmlspecialchars($row['id']); ?>' 
                                                class='btn btn-danger btn-sm' 
                                                    onclick="return confirm('Are you sure you want to delete this Pokémon card?')">Delete</a>


                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>