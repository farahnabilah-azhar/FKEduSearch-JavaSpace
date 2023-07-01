<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Retrieve username from session
    $postTitle = $_POST['postTitle'];
    $postContent = $_POST['postContent'];
    $postDate = $_POST['postDate'];
    $postCategory = $_POST['postCategory'];
    $postStatus = $_POST['postStatus'];

    // Retrieve the UserID based on the username
    $stmt = $conn->prepare("SELECT UserID FROM user WHERE UserName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $userID = $row['UserID'];

      // Prepare and execute the SQL query
      $stmt = $conn->prepare("INSERT INTO post (UserID, PostTitle, PostContent, PostCreatedDate, CategoryID, PostStatus) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("isssis", $userID, $postTitle, $postContent, $postDate, $postCategory, $postStatus);
      $stmt->execute();

      // Redirect to the manage_post.php page after adding the post
      header("Location: manage_post.php");
      exit();
    } else {
      echo "Error: Failed to retrieve UserID.";
    }
  } else {
    // Redirect to the login page if the user is not logged in
    header("Location: index.html");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="content_style.css">
  <title>Add Post</title>
</head>

<body>
  <!-- Side navigation pane -->
  <?php include 'side_navigation.php'; ?>

  <div class="content">
    <div class="container">
      <!-- Add your content here -->
      <h2>Add Post</h2>
      <div class="add-form" id="addPostForm">
          <!-- <span class="close-btn" onclick="hideAddPostForm()">&times;</span> -->
          <h3>Add Post</h3>
          <form action="add_post.php" method="post">
            <input type="hidden" name="username" value="<?php echo $username; ?>">
            <label for="postTitle">Title:</label>
            <input type="text" name="postTitle" id="postTitle" required>
            <label for="postContent">Content:</label>
            <input type="textarea" name="postContent" id="postContent" required>
            <input type="hidden" name="postDate" value="<?php echo date('Y-m-d'); ?>">
            <label for="postCategory">Category:</label>
            <select name="postCategory" id="postCategory" required>
              <?php
              // Retrieve categories from the database
              $categoryQuery = "SELECT CategoryID, CategoryName FROM category";
              $categoryResult = $conn->query($categoryQuery);

              // Display category options
              while ($categoryRow = $categoryResult->fetch_assoc()) {
                echo '<option value="' . $categoryRow['CategoryID'] . '">' . $categoryRow['CategoryName'] . '</option>';
              }
              ?>
            </select>
            <label for="postStatus">Status:</label>
            <select name="postStatus" id="postStatus" required>
              <option value="accepted">Accepted</option>
              <option value="revised">Revised</option>
              <option value="completed">Completed</option>
            </select>
            <button type="submit">Add</button>
            <button type="button" onclick="cancelUpdate()">Cancel</button>
          </form>
        
      </div>
    </div>
  </div>

  <!-- <script>
    function showAddPostForm() {
      var addPostForm = document.getElementById('addPostForm');
      addPostForm.style.display = 'block';
    }

    function hideAddPostForm() {
      var addPostForm = document.getElementById('addPostForm');
      addPostForm.style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
      var addPostButton = document.getElementById('add-post-button');

      addPostButton.addEventListener('click', function(event) {
        event.preventDefault();
        showAddPostForm();
      });

      var closeButton = document.querySelector('.close-btn');

      closeButton.addEventListener('click', function() {
        hideAddPostForm();
      });
    });
  </script> -->

                <script>
                    function cancelUpdate() {
                        window.location = "manage_post.php";
                    }
                </script>
</body>

</html>
