<?php
session_start();

// Check if the user is not logged in or does not have admin role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    echo "<script>alert('You are disallowed to access this page'); window.location.href='./';</script>";
    exit; // Stop further execution of the script
}

// Database credentials
include 'db_connection.php';

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="content_style.css">
    <title>User Landing Page</title>
    
    </style>
</head>

<body>
   <!-- Side navigation pane -->
   <?php include 'side_navigation.php'; ?>

   <div class="content">
        <!-- Header area -->
        <?php include 'header.php'; ?>
        <!-- Add your content here -->
        <h1>Dashboard</h1>

        <!-- Create Post Button -->
        <!-- <div class="create-post-button">
            <a href="add_post.php">Create Post</a>
        </div> -->
    </div>
</body>

</html>
