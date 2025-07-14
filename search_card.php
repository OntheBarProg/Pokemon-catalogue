<?php
session_start();
include("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$search_results = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['card_name'];
    $type = $_POST['type'];
    $rarity = $_POST['rarity'];

    $query = "SELECT * FROM cards WHERE 1=1";

    if (!empty($name)) {
        $query .= " AND card_name LIKE '%$name%'";
    }
    if (!empty($type)) {
        $query .= " AND type = '$type'";
    }
    if (!empty($rarity)) {
        $query .= " AND rarity = '$rarity'";
    }

    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $search_results[] = $row;
    }
}
?>

<h2>🔍 Search Pokémon Cards</h2>
<a href="dashboard.php">← Back to Dashboard</a>

<form method="POST">
    Card Name: <input type="text" name="card_name"><br><br>

    Type: 
    <select name="type">
        <option value="">-- Any --</option>
        <option>Fire</option>
        <option>Water</option>
        <option>Grass</option>
        <option>Electric</option>
        <option>Psychic</option>
    </select><br><br>

    Rarity: 
    <select name="rarity">
        <option value="">-- Any --</option>
        <option>Common</option>
        <option>Full Art</option>
        <option>Reverse Hollow</option>
    </select><br><br>

    <input type="submit" value="Search">
</form>

<?php if (!empty($search_results)): ?>
    <h3>Results:</h3>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Card Name</th>
            <th>Type</th>
            <th>Rarity</th>
            <th>Image</th>
        </tr>
        <?php foreach ($search_results as $card): ?>
            <tr>
                <td><?= $card['id'] ?></td>
                <td><?= $card['card_name'] ?></td>
                <td><?= $card['type'] ?></td>
                <td><?= $card['rarity'] ?></td>
                <td><img src="uploads/<?= $card['image'] ?>" width="80" height="100"></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
    <p>No cards found.</p>
<?php endif; ?>
