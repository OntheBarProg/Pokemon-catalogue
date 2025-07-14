<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>
<ul>
    <li><a href="add_card.php">Add New Card</a></li>
    <li><a href="show_cards.php">Show All Cards</a></li>
    <li><a href="search_card.php">Find a Card</a></li>
    <li><a href="logout.php">Logout</a></li>
    <li><a href="show_cards.php">Show All Cards</a></li>

</ul>

<a href="logout.php">Logout</a>
