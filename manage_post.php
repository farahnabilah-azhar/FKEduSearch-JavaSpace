<?php
session_start();
include 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <link rel="stylesheet" type="text/css" href="content_style.css">

    <title>Manage Post</title>

</head>

<body>
   <!-- Side navigation pane -->
   <?php include 'side_navigation.php'; ?>

   <div class="content">
        <!-- Header area -->
        <?php include 'header.php'; ?>
        <!-- Add your content here -->
        <h1>Manage Post</h1>
        <div class="add-content-button">
            <!-- <a id="add-post-button" href="add_post.php" onclick="showAddPostForm(event)">Add Post</a> -->
            <a id="add-post-button" href="add_post.php">Add Post</a>
        </div>
        <div class="white-box">
        <h2>Posts</h2>
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
                        <th>Post ID</th>
                        <th>Username</th>
                        <th>Title</th>
                        <th>Date</th></th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'db_connection.php';

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

                    // Check if the search query is set
if (isset($_GET['search'])) {
    // Sanitize the search query to prevent SQL injection
    $search = "%" . mysqli_real_escape_string($conn, $_GET['search']) . "%";

    // Construct the SQL query based on user role
    if ($role === 'admin') {
        // Admin can view all posts
        $query = "SELECT post.PostID, user.UserName, post.PostTitle, post.PostCreatedDate, category.CategoryName, post.PostStatus FROM post INNER JOIN user ON post.UserID = user.UserID INNER JOIN category ON post.CategoryID = category.CategoryID WHERE post.PostTitle LIKE '$search' OR user.UserName LIKE '$search' OR category.CategoryName LIKE '$search'";
    } else {
        // Users and experts can only view their own posts
        $query = "SELECT post.PostID, user.UserName, post.PostTitle, post.PostCreatedDate, category.CategoryName, post.PostStatus FROM post INNER JOIN user ON post.UserID = user.UserID INNER JOIN category ON post.CategoryID = category.CategoryID WHERE user.UserName = '$username' AND post.PostTitle LIKE '$search' OR user.UserName LIKE '$search' OR category.CategoryName LIKE '$search'";
    }

    // Execute the query and fetch the results
    $result = $conn->query($query);

    // Display the search results in a table
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['PostID'] . '</td>';
        echo '<td>' . $row['UserName'] . '</td>';
        echo '<td>' . $row['PostTitle'] . '</td>';
        echo '<td>' . $row['PostCreatedDate'] . '</td>';
        echo '<td>' . $row['CategoryName'] . '</td>';
        echo '<td>' . $row['PostStatus'] . '</td>';
        echo '<td>
        <a href="edit_post.php?postID=' . $row['PostID'] . '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
        <a href="delete_post.php?postID=' . $row['PostID'] . '" class="link-dark" onclick="return confirm(\'Are you sure you want to delete this post?\')"><i class="fa-solid fa-trash fs-5"></i></a>
        </td>';
        echo '</tr>';
    }
    // Close the database connection
    $conn->close();
} else {
    // If no search query is provided, retrieve and display all posts

    // Construct the SQL query based on user role
    if ($role === 'admin') {
        // Admin can view all posts
        $query = "SELECT post.PostID, user.UserName, post.PostTitle, post.PostCreatedDate, category.CategoryName, post.PostStatus FROM post INNER JOIN user ON post.UserID = user.UserID INNER JOIN category ON post.CategoryID = category.CategoryID";
    } else {
        // Users and experts can only view their own posts
        $query = "SELECT post.PostID, user.UserName, post.PostTitle, post.PostCreatedDate, category.CategoryName, post.PostStatus FROM post INNER JOIN user ON post.UserID = user.UserID INNER JOIN category ON post.CategoryID = category.CategoryID WHERE user.UserName = '$username'";
    }

    // Execute the query and fetch the results
    $result = $conn->query($query);

    // Display the posts in a table
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['PostID'] . '</td>';
            echo '<td>' . $row['UserName'] . '</td>';
            echo '<td>' . $row['PostTitle'] . '</td>';
            echo '<td>' . $row['PostCreatedDate'] . '</td>';
            echo '<td>' . $row['CategoryName'] . '</td>';
            echo '<td>' . $row['PostStatus'] . '</td>';
            echo '<td>
            <a href="edit_post.php?postID=' . $row['PostID'] . '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
            <a href="delete_post.php?postID=' . $row['PostID'] . '" class="link-dark" onclick="return confirm(\'Are you sure you want to delete this post?\')"><i class="fa-solid fa-trash fs-5"></i></a>
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
    </div>
</body>

</html>
