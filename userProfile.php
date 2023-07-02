<?php
// Connect to the database
$link = mysqli_connect("localhost", "root", "", "fkedusearch");

// Check the connection
if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch user profile data
$userID = 3; // Change this with the actual user ID
$query = "SELECT * FROM user WHERE UserID = $userID";
$result = mysqli_query($link, $query);

// Check if the query executed successfully
if (!$result) {
    die("Query execution failed: " . mysqli_error($link));
}

// Check if user exists
if (mysqli_num_rows($result) === 0) {
    die("User not found.");
}

// Fetch user data from the result
$userData = mysqli_fetch_assoc($result);

// Close the database connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .profile {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
        }

        .profile-heading {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .profile-label {
            font-weight: bold;
        }

        .profile-data {
            margin-bottom: 10px;
        }

        .update-button {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="profile">
        <h2 class="profile-heading">User Profile</h2>
        <div class="profile-data">
            <span class="profile-label">UserID:</span> <?php echo $userData['UserID']; ?>
        </div>
        <div class="profile-data">
            <span class="profile-label">UserName:</span> <?php echo $userData['UserName']; ?>
        </div>
        <div class="profile-data">
            <span class="profile-label">UserEmail:</span> <?php echo $userData['UserEmail']; ?>
        </div>
        <div class="profile-data">
            <span class="profile-label">UserRole:</span> <?php echo $userData['UserRole']; ?>
        </div>
        <div class="profile-data">
            <span class="profile-label">CategoryID:</span> <?php echo $userData['CategoryID']; ?>
        </div>
        <div class="profile-data">
            <span class="profile-label">UserAcademicStatus:</span> <?php echo $userData['UserAcademicStatus']; ?>
        </div>
        <!-- ... Add other profile fields here -->
        <div class="update-button">
            <a href="userProfileEdit.php" class="button">Update Profile</a>
        </div>
    </div>
</body>
</html>
