<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: /login.php");
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if id is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Verify database connection
    if (!$conn) {
        error_log("Database connection failed: " . mysqli_connect_error());
        die("Database connection failed. Check logs.");
    }

    // Prepare and execute delete query with detailed error handling
    $stmt = mysqli_prepare($conn, "DELETE FROM cards WHERE id = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . mysqli_error($conn));
        die("Query preparation failed. Check logs.");
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    $execute_result = mysqli_stmt_execute($stmt);
    if ($execute_result === false) {
        error_log("Execute failed for ID $id: " . mysqli_error($conn));
        die("Query execution failed. Check logs.");
    }

    mysqli_stmt_close($stmt);
    header("Location: /search_card.php?delete_status=success");
    exit();
} else {
    error_log("Invalid or missing ID: " . (isset($_GET['id']) ? $_GET['id'] : 'none'));
    die("Invalid card ID.");
}
?>