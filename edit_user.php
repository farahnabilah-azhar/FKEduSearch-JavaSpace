<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="content_style.css">
  <title>Edit User</title>
  
</head>

<body>
  <!-- Side navigation pane -->
  <?php include 'side_navigation.php'; ?>

  <div class="content">
    <div class="container">
      <!-- Add your content here -->
      <h2>Edit User</h2>

      <?php
      // Check if the user ID is provided
      if (!isset($_GET['userID'])) {
        header("Location: manage_user.php");
        exit();
      }

      $userID = $_GET['userID'];

      include 'db_connection.php';

      // Retrieve the user data from the database
      $stmt = $conn->prepare("SELECT user.UserID, user.UserName, user.UserEmail, user.UserRole, user.CategoryID, user.UserAcademicStatus, user.UserQRCode, user.UserStatus, category.CategoryName FROM user INNER JOIN category ON user.CategoryID = category.CategoryID WHERE UserID = ?");
      $stmt->bind_param("i", $userID);
      $stmt->execute();
      $result = $stmt->get_result();

      // Check if the user exists
      if ($result->num_rows == 0) {
        header("Location: manage_user.php");
        exit();
      }

      $row = $result->fetch_assoc();

      $conn->close();

      // Retrieve all categories
      $conn = new mysqli("localhost", "root", "", "fkedusearch");
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $categoriesQuery = "SELECT CategoryID, CategoryName FROM category";
      $categoriesResult = $conn->query($categoriesQuery);
      ?>

      <!-- Edit User Form -->
      <div class="edit-content-form">
        <h2>Edit User</h2>
        <form action="update_user.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="userID" value="<?php echo $userID; ?>">
          <label for="username">Username:</label>
          <input type="text" name="username" id="username" value="<?php echo $row['UserName']; ?>" required>
          <label for="email">Email:</label>
          <input type="text" name="email" id="email" value="<?php echo $row['UserEmail']; ?>" required>
          <label for="role">Role:</label>
          <select name="role" id="role" required>
            <option value="admin" <?php if ($row['UserRole'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="user" <?php if ($row['UserRole'] == 'user') echo 'selected'; ?>>User</option>
            <option value="expertise" <?php if ($row['UserRole'] == 'expertise') echo 'selected'; ?>>Expertise</option>
          </select>
          <label for="academicArea">Academic Area:</label>
          <select name="academicArea">
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
          <label for="academicStatus">Academic Status:</label>
          <select name="academicStatus" id="academicStatus" required>
            <option value="Diploma" <?php if ($row['UserAcademicStatus'] == 'Diploma') echo 'selected'; ?>>Diploma</option>
            <option value="Bachelor" <?php if ($row['UserAcademicStatus'] == 'Bachelor') echo 'selected'; ?>>Bachelor</option>
            <option value="Master" <?php if ($row['UserAcademicStatus'] == 'Master') echo 'selected'; ?>>Master</option>
            <option value="PhD" <?php if ($row['UserAcademicStatus'] == 'PhD') echo 'selected'; ?>>PhD</option>
          </select>
          <label for="certificate">Academic Certificate (proof):</label>
          <input type="file" name="certificate" id="certificate" accept=".pdf">
          <?php
            // Check if a certificate file exists for the user
            if (!empty($row['UserCertificate'])) {
              $certificateFilePath = __DIR__ . '/certificate/' . $row['UserCertificate'];
              if (file_exists($certificateFilePath)) {
                // Display the current certificate file name
                echo '<p>Current Certificate: ' . $row['UserCertificate'] . '</p>';
              } else {
                // The certificate file is missing, display an error message
                echo '<p class="error">Certificate file not found.</p>';
              }
            } else {
              // No certificate file found
              echo '<p>No certificate file found.</p>';
            }
            ?>
          <label for="status">Status:</label>
          <select name="status" id="status" required>
            <option value="active" <?php if ($row['UserStatus'] == 'active') echo 'selected'; ?>>Active</option>
            <option value="inactive" <?php if ($row['UserStatus'] == 'inactive') echo 'selected'; ?>>Inactive</option>
          </select>
          <button type="submit">Update</button>
          <button type="button" onclick="cancelUpdate()">Cancel</button>
        </form>
      </div>

      <script>
         // Submit form on button click
         document.querySelector("button[type='submit']").addEventListener("click", function (event) {
                        event.preventDefault();
                        var fileInput = document.getElementById("certificate");
                        if (fileInput.files.length > 0) {
                            document.querySelector("form").submit();
                        } else {
                            alert("Please select a certificate file.");
                        }
                    });
                    
        function cancelUpdate() {
          window.location = "manage_user.php";
        }
      </script>
    </div>
  </div>
</body>

</html>
