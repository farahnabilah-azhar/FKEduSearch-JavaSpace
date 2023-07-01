<?php
include 'db_connection.php';

// Check if the postID is provided in the URL
if (isset($_GET['postID'])) {
    $postID = $_GET['postID'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Delete confirmation received
        $confirmation = $_POST['confirmation'];

        if ($confirmation === 'yes') {
            // Prepare and execute the delete statement
            $stmt = $conn->prepare("DELETE FROM post WHERE PostID = ?");
            $stmt->bind_param("i", $postID);
            $stmt->execute();

            // Check if the delete operation was successful
            if ($stmt->affected_rows > 0) {
                echo "Post deleted successfully.";
            } else {
                echo "Failed to delete post.";
            }

            // Close the statement
            $stmt->close();

            // Redirect back to manage_post.php
            header("Location: manage_post.php");
            exit();
        } else {
            echo "Deletion canceled.";
            header("Location: manage_post.php");
            exit();
        }
    } else {
        // Retrieve the post data from the database
        $stmt = $conn->prepare("SELECT PostTitle FROM post WHERE PostID = ?");
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();

        // Display delete confirmation
        echo "Are you sure you want to delete post: " . $post['PostTitle'] . "?<br>";
        echo '<form method="POST" action="">';
        echo '<input type="hidden" name="postID" value="' . $postID . '">';
        echo 'Confirmation: <input type="radio" name="confirmation" value="yes"> Yes ';
        echo '<input type="radio" name="confirmation" value="no" checked> No ';
        echo '<button type="submit">Submit</button>';
        echo '</form>';
    }
} else {
    echo "PostID not provided.";
}

// Close the database connection
$conn->close();
?>
