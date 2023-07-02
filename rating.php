<?php
session_start();

// Check if the user is not logged in or does not have the 'user' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    echo "<script>alert('You are disallowed to access this page'); window.location.href='./';</script>";
    exit; // Stop further execution of the script
}

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fkedusearch";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle rating submission
$ratingSubmitted = false; // Flag to track if rating was submitted

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['rating'])) {
        $rating = $_POST['rating'];
        $username = $_SESSION['username'];

        // Prepare and execute the database query
        $stmt = $conn->prepare("INSERT INTO ratings (username, rating) VALUES (?, ?)");
        $stmt->bind_param("si", $username, $rating);
        $stmt->execute();

        // Check if the rating was successfully inserted
        if ($stmt->affected_rows > 0) {
            $ratingSubmitted = true;
            header("Location: rate.php"); // Redirect to rate.php
            exit;
        }

        // Close the prepared statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="content_style.css">
    <style>
        /* Add custom styles for the rating scale bar */
        input[type="range"] {
            width: 200px;
            margin-top: 10px;
            background-color: #8080ff;
            height: 10px;
            border-radius: 5px;
            appearance: none;
        }

        input[type="range"]::-webkit-slider-thumb {
            appearance: none;
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            cursor: pointer;
        }

        input[type="range"]::-webkit-slider-thumb:hover {
            background-color: lightgray;
        }

        input[type="submit"] {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Hide the rating div if rating is submitted */
        <?php if ($ratingSubmitted) : ?>
        .rating-div {
            display: none;
        }
        <?php endif; ?>
    </style>
    <title>User Landing Page</title>
</head>

<body>
    <!-- Side navigation pane -->
    <?php include 'side_navigation.php'; ?>

    <div class="content" style="text-align: center;">
    <!-- Header area -->
    <?php include 'header.php'; ?>
    <!-- Add your content here -->

    <!-- Rating form -->
    <form id="ratingForm" action="submit_rating.php" method="POST" <?php if ($ratingSubmitted) echo 'class="rating-div"'; ?>>
        <h3>Rate the Website:</h3>
        <div class="rating">
            <input type="range" name="rating" min="1" max="5" step="1">
        </div>
        <input style="background: gray" type="submit" value="Submit Rating">
    </form>
</div>

</div>

    <!-- Thank you message -->
    
</body>

</html>

</body>

</html>
