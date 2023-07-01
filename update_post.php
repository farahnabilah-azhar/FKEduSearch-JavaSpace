<?php
session_start();
include 'db_connection.php';

// Check if the postID and other form fields are provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postID'], $_POST['postTitle'], $_POST['postContent'], $_POST['postCategory'], $_POST['postStatus'])) {
  // Retrieve the form data
  $postID = $_POST['postID'];
  $postTitle = $_POST['postTitle'];
  $postContent = $_POST['postContent'];
  $postCategory = $_POST['postCategory'];
  $postStatus = $_POST['postStatus'];

  // Prepare and execute the SQL query to update the post
  $stmt = $conn->prepare("UPDATE post SET PostTitle = ?, PostContent = ?, CategoryID = ?, PostStatus = ? WHERE PostID = ?");
  $stmt->bind_param("ssisi", $postTitle, $postContent, $postCategory, $postStatus, $postID);
  $stmt->execute();

  // Check if the update was successful
  if ($stmt->affected_rows > 0) {
    // Redirect to the manage_post.php page after updating the post
    header("Location: manage_post.php");
    exit();
  } else {
    echo "Error: Failed to update the post.";
  }

  // Close the prepared statement
  $stmt->close();
} else {
  echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
