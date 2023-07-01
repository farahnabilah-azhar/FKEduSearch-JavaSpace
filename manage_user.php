<?php
session_start();

// Check if the user's role is set in the session
    if (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
    }

    // Check if the userID is set in the session
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        // Redirect to the login page if the userID is not set
        header("Location: index.html");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="content_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <title>Manage Users</title>

</head>

<body>
    <!-- Side navigation pane -->
   <?php include 'side_navigation.php'; ?>

   <div class="content">
        <!-- Header area -->
        <?php include 'header.php'; ?>
        <!-- Add your content here -->
        <h1>Manage User</h1>
        <div class="add-content-button">
            <a id="add-post-button" href="add_user.php">Add User</a>
        </div>
        <div class="white-box">
            <h2>Users</h2>
            <div class="content-table">
                <!-- Search Form -->
                <form action="" method="GET" class="search-form">
                    <!-- Your form inputs here -->
                    <input type="text" name="search" placeholder="Search">
                    <button type="submit">Search</button>
                </form>
            </div>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Area</th>
                        <th>Academic Status</th>
                        <th>Certificate</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    include 'db_connection.php';

                    // Check if the search query is set
                    if (isset($_GET['search'])) {
                        // Sanitize the search query to prevent SQL injection
                        $search = "%" . mysqli_real_escape_string($conn, $_GET['search']) . "%";

                    // Only admin can search all users
                    if($role === 'admin'){
                    $stmt = $conn->prepare("SELECT user.UserID, user.UserName, user.UserEmail, user.UserRole, category.CategoryName, user.UserAcademicStatus, user.UserQRCode, user.UserStatus FROM user INNER JOIN category ON user.CategoryID = category.CategoryID WHERE UserName LIKE ? OR UserRole LIKE ? OR UserAcademicStatus LIKE ? OR UserStatus LIKE ?");
                    $stmt->bind_param("ssss", $search, $search, $search, $search);
                    }else{
                    $stmt = $conn->prepare("SELECT user.UserID, userUserName, user.UserEmail, user.UserRole, category.CategoryName, user.UserAcademicStatus, user.UserQRCode, user.UserStatus FROM user INNER JOIN category ON user.CategoryID = category.CategoryID WHERE UserName = '$username' OR UserRole LIKE ? OR UserAcademicStatus LIKE ? OR UserStatus LIKE ?");
                    $stmt->bind_param("ssss", $search, $search, $search, $search);
                    }

                    // Execute the prepared statement
                    $stmt->execute();

                    // Get the result set
                    $result = $stmt->get_result();

                    // Display the search results in a table
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['UserID'] . '</td>';
                        echo '<td>' . $row['UserName'] . '</td>';
                        echo '<td>' . $row['UserEmail'] . '</td>';
                        echo '<td>' . $row['UserRole'] . '</td>';
                        echo '<td>' . $row['CategoryName'] . '</td>';
                        echo '<td>' . $row['UserAcademicStatus'] . '</td>';
                        echo '<td><img src="' . $row['UserQRCode'] . '" alt="QR Code" width="100" height="100"></td>';
                        echo '<td>' . $row['UserStatus'] . '</td>';
                        echo '<td>
                            <a href="edit_user.php?userID=' . $row['UserID'] . '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                            <a href="delete_user.php?userID=' . $row['UserID'] . '" class="link-dark" onclick="return confirm(\'Are you sure you want to delete this user?\')"><i class="fa-solid fa-trash fs-5"></i></a>
                            </td>';
                        echo '</tr>';
                    }
                    

                    // Close the prepared statement
                    $stmt->close();
                } else {
                    // If no search query is provided, retrieve and display all users

                    if ($role === 'admin') {
                    // Admin can view all users
                    $query = "SELECT user.UserID, user.UserName, user.UserEmail, user.UserRole, category.CategoryName, user.UserAcademicStatus, user.UserQRCode, user.UserStatus FROM user INNER JOIN category ON user.CategoryID = category.CategoryID";
                    }else{
                        $query = "SELECT user.UserID, user.UserName, user.UserEmail, user.UserRole, category.CategoryName, user.UserAcademicStatus, user.UserQRCode, user.UserStatus FROM user INNER JOIN category ON user.CategoryID = category.CategoryID WHERE user.UserName='$username'";  
                    }

                    // Execute the query and fetch the results
                    $result = $conn->query($query);

                    // Display the users in a table
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['UserID'] . '</td>';
                        echo '<td>' . $row['UserName'] . '</td>';
                        echo '<td>' . $row['UserEmail'] . '</td>';
                        echo '<td>' . $row['UserRole'] . '</td>';
                        echo '<td>' . $row['CategoryName'] . '</td>';
                        echo '<td>' . $row['UserAcademicStatus'] . '</td>';
                        echo '<td><img src="' . $row['UserQRCode'] . '" alt="QR Code" width="100" height="100"></td>';
                        echo '<td>' . $row['UserStatus'] . '</td>';
                        echo '<td>
                            <a href="edit_user.php?userID=' . $row['UserID'] . '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                            <a href="delete_user.php?userID=' . $row['UserID'] . '" class="link-dark" onclick="return confirm(\'Are you sure you want to delete this user?\')"><i class="fa-solid fa-trash fs-5"></i></a>
                            </td>';
                        echo '</tr>';
                    }
                    
                    // Close the database connection
                    $conn->close();
                    }
                    ?>
                </tbody>
            </table>
        </div>

    
</body>

</html>
