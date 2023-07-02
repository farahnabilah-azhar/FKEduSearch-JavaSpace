<!-- index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <style>
        /* CSS styles for the homepage */
    </style>
</head>
<body>
    <div id="posts">
        <?php
        // Connect to the database
        $link = mysqli_connect("localhost", "root", "your_password") or die(mysqli_connect_error());
        mysqli_select_db($link, "fkedusearch") or die(mysqli_error($link));

        // Fetch posts from the database
        $query = "SELECT * FROM userpost";
        $result = mysqli_query($link, $query);

        // Display posts
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='post'>";
            echo "<h3>" . $row['PostTitle'] . "</h3>";
            echo "<p>" . $row['PostContent'] . "</p>";
            echo "<a href='like.php?post_id=" . $row['like_ID'] . "'>Like</a>";
            echo "<a href='comments.php?post_id=" . $row['comment_ID'] . "'>Comments</a>";
            echo "</div>";
        }

        // Close the database connection
        mysqli_close($link);
        ?>
    </div>
</body>
</html>
