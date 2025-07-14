<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch cards
$result = mysqli_query($conn, "SELECT * FROM cards");
?>

<h2>All Pokémon Cards</h2>
<a href="dashboard.php">← Back to Dashboard</a>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Card Name</th>
        <th>Type</th>
        <th>Rarity</th>
        <th>Image</th>
        <th>Action</th>
    </tr>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['card_name'] ?></td>
        <td><?= $row['type'] ?></td>
        <td><?= $row['rarity'] ?></td>
        <td><img src="uploads/<?= $row['image'] ?>" width="80" height="100"></td>
        <td>
            <a href="delete_card.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this card?')">Delete</a>
        </td>
    </tr>
<?php endwhile; ?>
</table>
