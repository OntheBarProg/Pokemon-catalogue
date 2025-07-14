<?php
session_start();
include("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the image filename before deleting
    $result = mysqli_query($conn, "SELECT image FROM cards WHERE id=$id");
    $card = mysqli_fetch_assoc($result);
    if ($card && $card['image']) {
        unlink("uploads/" . $card['image']); // Delete image from folder
    }

    // Delete the card from the database
    mysqli_query($conn, "DELETE FROM cards WHERE id=$id");

    // Redirect back to show_cards.php
    header("Location: show_cards.php");
    exit();
}
?>
