<?php
session_start();

// Check if the user's role is set in the session
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <link rel="stylesheet" href="content_style.css">
    <style>
        /* CSS styles for the homepage */
        body {
            font-family: Arial, sans-serif;
        }

        .post {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
        }

        .post h3 {
            margin-bottom: 0;
        }

        .post p {
            margin-bottom: 10px;
        }

        .post .icon {
            display: inline-block;
            margin-right: 5px;
        }

        .post .icon.like {
            color: #000080;
        }

        .post .icon.comment {
            color: #000080;
        }

        .post a {
            text-decoration: none;
            color: #337ab7;
            margin-right: 10px;
        }

        .post a:hover {
            text-decoration: underline;
        }

        .add-post {
            margin-bottom: 20px;
        }

        .add-post a {
            display: inline-block;
            padding: 5px;
            background-color: #337ab7;
            color: #fff;
            text-decoration: none;
        }

        .comment-box {
            display: none; /* Hide the comment box by default */
            margin-top: 10px;
        }

        .comment-box form {
            margin-top: 10px;
        }

        .comment-box textarea {
            width: 100%;
            resize: vertical;
        }
    </style>
    <script src="path/to/font-awesome/js/all.min.js"></script>
    <script>
        function toggleCommentBox(postId) {
            var commentBox = document.getElementById('comment-box-' + postId);
            var commentLink = document.getElementById('comment-link-' + postId);

            if (commentBox.style.display === 'none' || commentBox.style.display === '') {
                commentBox.style.display = 'block';
                commentLink.innerHTML = '<i class="fas fa-comments icon comment"></i> Hide Comments';
            } else {
                commentBox.style.display = 'none';
                commentLink.innerHTML = '<i class="fas fa-comments icon comment"></i> Comments';
            }
        }
    </script>
</head>
<body>
    <!-- Side navigation pane -->
    <?php include 'side_navigation.php'; ?>

    <div class="content">
        <!-- Header area -->
        <?php include 'header.php'; ?>
        <!-- Add your content here -->

        <h1>Discussion Board</h1>

        <div id="posts">
            <div class="add-content-button">
                <a href="add_post.php"><i class="fas fa-plus"></i> Add Post</a>
            </div>

            <?php
            include 'db_connection.php';

            // Retrieve all posts
            $query = "SELECT * FROM post ORDER BY PostID DESC";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // Display each post
                while ($row = $result->fetch_assoc()) {
                    $postId = $row['PostID'];
                    $postTitle = $row['PostTitle'];
                    $postContent = $row['PostContent'];
                    // $postLikes = $row['likes'];

                    echo '<div class="post">';
                    echo '<h3>' . $postTitle . '</h3>';
                    echo '<p>' . $postContent . '</p>';
                    echo '<a href="#"><i class="fas fa-thumbs-up icon like"></i> Like</a>';
                    echo '<a href="#" onclick="toggleCommentBox(' . $postId . ');" id="comment-link-' . $postId . '"><i class="fas fa-comments icon comment"></i> Comments</a>';
                    echo '<div id="comment-box-' . $postId . '" class="comment-box">';
                    echo '<!-- Include comments.php here -->';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo 'No posts available.';
            }

            // Close the database connection
            $conn->close();
            ?>

        </div>
    </div>
</body>
</html>
