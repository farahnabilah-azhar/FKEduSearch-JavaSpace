<?php
// Assuming you have a database connection established

// Check if the saveComment function is already defined
if (!function_exists('saveComment')) {
    // Function to save a new comment in the database
    function saveComment($connection, $postId, $comment) {
        // Add your database insert query here to save the comment
        // Make sure to sanitize the user input to prevent SQL injection
        $commentDescription = mysqli_real_escape_string($connection, $comment);
        $currentDate = date('Y-m-d H:i:s');

        $query = "INSERT INTO comments (PostID, comments_description, comments_date) VALUES ('$postId', '$commentDescription', '$currentDate')";

        // Execute the query
        $result = mysqli_query($connection, $query);

        if ($result) {
            // Comment saved successfully
            return true;
        } else {
            // Failed to save the comment
            return false;
        }
    }
}

// Assuming you have an array of comments for each post with the post_id as the key
$comments = array(
    1 => array(
        array(
            'author' => 'John',
            'comment' => 'Great post!',
        ),
        array(
            'author' => 'Mary',
            'comment' => 'I enjoyed reading this.',
        ),
    ),
    2 => array(
        array(
            'author' => 'Peter',
            'comment' => 'Interesting topic!',
        ),
        array(
            'author' => 'Sarah',
            'comment' => 'Thanks for sharing.',
        ),
    ),
);

// Get the post_id from the query string parameter
$postId = $_GET['PostID'];

// Check if comments exist for the given post_id
if (isset($comments[$postId])) {
    // Loop through the comments and display them
    foreach ($comments[$postId] as $comment) {
        $author = $comment['author'];
        $commentText = $comment['comments'];

        // Display the comment
        echo "<p><strong>$author:</strong> $commentText</p>";
    }
} else {
    // No comments for this post
    echo "<p>No comments yet.</p>";
}

// Handle the comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the comment form is submitted
    if (isset($_POST['comments'])) {
        $newComment = $_POST['comments'];

        // Save the new comment in the database
       
// if (saveComment($connection, $postId, $newComment)) {
    // Construct the SQL query
    $sql = "INSERT INTO comments (comment_ID, post_ID, comments_description, comments_date) 
            VALUES (NULL, '$postId', '$newComment', NOW())";

    // Execute the SQL query
    if (mysqli_query($link, "fkedusearch")) {
        echo "<p>Your comment has been submitted successfully.</p>";
    } else {
        echo "<p>Failed to submit your comment. Please try again later.</p>";
    }
} else {
    echo "<p>Failed to submit your comment. Please try again later.</p>";
}

    }

?>
