<?php
session_start();
include 'db_connection.php';

// Check if the expert ID is provided in the query string
if (isset($_GET['expertID'])) {
    $expertID = $_GET['expertID'];

    // Delete the expert from the database
    $stmt = $conn->prepare("DELETE FROM expert WHERE ExpertID = ?");
    $stmt->bind_param("i", $expertID);

    if ($stmt->execute()) {
        // Redirect to the manage expert page with a success message
        header("Location: manage_expert.php?status=success&message=Expert deleted successfully");
        exit();
    } else {
        // Redirect to the manage expert page with an error message
        header("Location: manage_expert.php?status=error&message=Failed to delete expert");
        exit();
    }
}

$conn->close();
?>
