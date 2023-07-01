<?php
session_start();
include 'db_connection.php';

// Check if the postID is provided as a query parameter
if (isset($_GET['postID'])) {
  $postID = $_GET['postID'];

  // Retrieve the post details based on the postID
  $stmt = $conn->prepare("SELECT post.*, category.CategoryName FROM post INNER JOIN category ON post.CategoryID = category.CategoryID WHERE post.PostID = ?");
  $stmt->bind_param("i", $postID);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      // Retrieve all categories from the category table
      $categoriesQuery = "SELECT * FROM category";
      $categoriesResult = $conn->query($categoriesQuery);

        // Display the edit form with pre-filled values
        ?>

        <!-- HTML form for editing the post -->
        <!DOCTYPE html>
        <html lang="en">

        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <link rel="stylesheet" type="text/css" href="content_style.css">
          <title>Edit Post</title>
        </head>

        <body>
          <!-- Side navigation pane -->
          <?php include 'side_navigation.php'; ?>

          <div class="content">
            <div class="container">
              <!-- Add your content here -->
              <h2>Edit Post</h2>
              <div class="edit-content-form" id="addPostForm">
                  <!-- <span class="close-btn" onclick="hideAddPostForm()">&times;</span> -->
                  <h3>Edit Post</h3>
        <form action="update_post.php" method="POST">
            <input type="hidden" name="postID" value="<?php echo $row['PostID']; ?>">
            <label for="postTitle">Title:</label>
            <input type="text" name="postTitle" value="<?php echo $row['PostTitle']; ?>"><br>
            <label for="postContent">Content:</label>
            <textarea name="postContent"><?php echo $row['PostContent']; ?></textarea><br>
            <label for="postCategory">Category:</label>
            <select name="postCategory">
                <?php
                // Iterate over the categories and generate the options
                while ($categoryRow = $categoriesResult->fetch_assoc()) {
                    $categoryID = $categoryRow['CategoryID'];
                    $categoryName = $categoryRow['CategoryName'];
                    $selected = ($categoryID == $row['CategoryID']) ? 'selected' : '';
                    echo "<option value=\"$categoryID\" $selected>$categoryName</option>";
                }
                ?>
            </select><br>
            <label for="postStatus">Status:</label>
            <select name="postStatus" id="postStatus" required>
                <option value="accepted" <?php if ($row['PostStatus'] == 'accepted') echo 'selected'; ?>>Accepted</option>
                <option value="revised" <?php if ($row['PostStatus'] == 'revised') echo 'selected'; ?>>Revised</option>
                <option value="completed" <?php if ($row['PostStatus'] == 'completed') echo 'selected'; ?>>Completed</option>
            </select><br>
            <button type="submit">Update Post</button>
            <button type="button" onclick="cancelUpdate()">Cancel</button>
        </form>

        <?php
    } else {
        echo "Post not found.";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "Post ID not provided.";
}

// Close the database connection
$conn->close();
?>

<script>
                    function cancelUpdate() {
                        window.location = "manage_post.php";
                    }
                </script>
</body>

</html>
