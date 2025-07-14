<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_name = $_POST["card_name"];
    $type = $_POST["type"];
    $rarity = $_POST["rarity"];
    $image = $_FILES["image"]["name"];
    move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $image);

    mysqli_query($conn, "INSERT INTO cards (card_name, type, rarity, image) VALUES ('$card_name', '$type', '$rarity', '$image')");
    echo "Card added successfully.";
}
?>

<form method="POST" enctype="multipart/form-data">
    Card Name: <input type="text" name="card_name"><br>
    Type: 
    <select name="type">
        <option>Fire</option>
        <option>Water</option>
        <option>Grass</option>
        <option>Electric</option>
        <option>Psychic</option>
    </select><br>
    Rarity: 
    <select name="rarity">
        <option>Common</option>
        <option>Full Art</option>
        <option>Reverse Hollow</option>
    </select><br>
    Image: <input type="file" name="image"><br>
    <input type="submit" value="Add Card">
</form>
<a href="logout.php">Logout</a>
