<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: /login.php");
    exit();
}

// Initialize search results
$search_results = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['card_name'] ?? '', ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars($_POST['type'] ?? '', ENT_QUOTES, 'UTF-8');
    $rarity = htmlspecialchars($_POST['rarity'] ?? '', ENT_QUOTES, 'UTF-8');

    $query = "SELECT * FROM cards WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($name)) {
        $query .= " AND card_name LIKE ?";
        $params[] = "%" . $name . "%";
        $types .= "s";
    }
    if (!empty($type) && $type !== "-- Any --") {
        $query .= " AND type = ?";
        $params[] = $type;
        $types .= "s";
    }
    if (!empty($rarity) && $rarity !== "-- Any --") {
        $query .= " AND rarity = ?";
        $params[] = $rarity;
        $types .= "s";
    }

    if (count($params) > 0) {
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt) {
            if (!empty($types)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $search_results[] = $row;
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Card Shop - Search Cards</title>
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
        .search-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.85);
            border: 2px solid #ffcb05;
        }
        h2 {
            color: #2a75bb;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: bold;
            color: #2a75bb;
        }
        .form-select, .form-control {
            border-color: #2a75bb;
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
        .btn-primary {
            background-color: #ffcb05;
            border-color: #ffcb05;
            color: #2a75bb;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #2a75bb;
            border-color: #2a75bb;
            color: #ffcb05;
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
        <div class="search-container">
            <h2 class="text-center mb-4">🔍 Search Pokémon Cards</h2>
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

            <form method="POST">
                <div class="mb-3">
                    <label for="card_name" class="form-label">Card Name</label>
                    <input type="text" class="form-control" id="card_name" name="card_name" 
                           value="<?php echo isset($name) ? $name : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="" <?php echo !isset($type) || $type === "" ? 'selected' : ''; ?>>-- Any --</option>
                        <option value="Fire" <?php echo isset($type) && $type === "Fire" ? 'selected' : ''; ?>>Fire</option>
                        <option value="Water" <?php echo isset($type) && $type === "Water" ? 'selected' : ''; ?>>Water</option>
                        <option value="Grass" <?php echo isset($type) && $type === "Grass" ? 'selected' : ''; ?>>Grass</option>
                        <option value="Electric" <?php echo isset($type) && $type === "Electric" ? 'selected' : ''; ?>>Electric</option>
                        <option value="Psychic" <?php echo isset($type) && $type === "Psychic" ? 'selected' : ''; ?>>Psychic</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="rarity" class="form-label">Rarity</label>
                    <select class="form-select" id="rarity" name="rarity">
                        <option value="" <?php echo !isset($rarity) || $rarity === "" ? 'selected' : ''; ?>>-- Any --</option>
                        <option value="Common" <?php echo isset($rarity) && $rarity === "Common" ? 'selected' : ''; ?>>Common</option>
                        <option value="Full Art" <?php echo isset($rarity) && $rarity === "Full Art" ? 'selected' : ''; ?>>Full Art</option>
                        <option value="Reverse Holo" <?php echo isset($rarity) && $rarity === "Reverse Holo" ? 'selected' : ''; ?>>Reverse Holo</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <?php if (!empty($search_results)): ?>
                <h3 class="mt-4" style="color: #2a75bb;">Results:</h3>
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
                            <?php foreach ($search_results as $card): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($card['id']); ?></td>
                                    <td><?php echo htmlspecialchars($card['card_name']); ?></td>
                                    <td><?php echo htmlspecialchars($card['type']); ?></td>
                                    <td><?php echo htmlspecialchars($card['rarity']); ?></td>
                                    <td>
                                        <?php
                                        $image_path = "/" . htmlspecialchars($card['image']);
                                        $full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . $image_path;
                                        $full_file_path = $_SERVER['DOCUMENT_ROOT'] . $image_path;
                                        if (file_exists($full_file_path)) {
                                            $headers = @get_headers($full_url);
                                            if ($headers && strpos($headers[0], '200') !== false) {
                                                echo "<img src='$image_path' alt='" . htmlspecialchars($card['card_name']) . "' class='card-image'>";
                                            } else {
                                                echo "<div class='placeholder-image'>Image Unavailable</div><br><span class='error-message'>Image URL inaccessible: $full_url</span>";
                                            }
                                        } else {
                                            echo "<div class='placeholder-image'>No Image</div><br><span class='error-message'>Image not found at: $full_file_path</span>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                       <a href='delete_card.php?id=<?php echo htmlspecialchars($card['id']); ?>' 
                                            class='btn btn-danger btn-sm' 
                                                onclick="return confirm('Are you sure you want to delete this Pokémon card?')">Delete</a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
                <p class="mt-3 text-center" style="color: #dc3545;">No Pokémon cards found.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>