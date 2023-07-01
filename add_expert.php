<?php
session_start();
include 'db_connection.php';

// Check if the user's role is set in the session
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
}

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['role'], $_POST['status'])) {
    // Retrieve the form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    // Prepare and execute the SQL query to insert the user data into the expert table
    $stmt = $conn->prepare("INSERT INTO expert (ExpertUsername, ExpertPassword, ExpertEmail, ExpertRole, ExpertStatus) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $password, $email, $role, $status);
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        // Redirect to the manage_expert.php page after adding the expert
        header("Location: manage_expert.php");
        exit();
    } else {
        echo "Error: Failed to add the expert.";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
