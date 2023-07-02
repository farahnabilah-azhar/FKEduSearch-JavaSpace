<?php
// Connect to the database
$link = mysqli_connect("localhost", "root", "", "fkedusearch") or die(mysqli_connect_error($link));

// Check if post_id and status parameters are set
if (isset($_GET['PostID']) && isset($_GET['PostStatus'])) {
    $postID = $_GET['PostID'];
    $status = $_GET['PostStatus'];

    // Update the status of the post in the database
    $query = "UPDATE userpost SET PostStatus = '$status' WHERE PostID = '$postID'";
    mysqli_query($link, $query);

    // Redirect back to the homepage or the page where the status was changed
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

// Close the database connection
mysqli_close($link);
?>
