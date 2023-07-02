<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="content_style.css">
  <title>View User</title>
  
</head>

<body>
  <!-- Side navigation pane -->
  <?php include 'side_navigation.php'; ?>

  <div class="content">
    <div class="container">
      <!-- Add your content here -->
      <h2>View User</h2>

      <?php
      // Check if the user ID is provided
      if (!isset($_GET['userID'])) {
        header("Location: manage_user.php");
        exit();
      }

      $userID = $_GET['userID'];

      include 'db_connection.php';

      // Retrieve the user data from the database
      $stmt = $conn->prepare("SELECT UserName, UserPassword, UserEmail, UserRole, UserStatus, Likes, Comment, rating FROM user WHERE UserID = ?");
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
      ?>

      <!-- User Details -->
      <table>
        <div class="user-details">
          <h3>Username: <?php echo $row['UserName']; ?></h3>
          <p>Password: <?php echo $row['UserPassword']; ?></p>
          <p>Email: <?php echo $row['UserEmail']; ?></p>
          <p>Role: <?php echo $row['UserRole']; ?></p>
          <p>Status: <?php echo $row['UserStatus']; ?></p>
          <p>Number of Likes: <?php echo $row['Likes']; ?></p>
          <p>Number of Comments: <?php echo $row['Comment']; ?></p>
          <p>Rating: <?php echo $row['rating']; ?></p>
        </div>
      </table>

      <!-- QR Code -->
      <?php
      $data = array(
        'UserID' => $userID,
        'Rating' => $row['rating']
      );
      $qrData = json_encode($data);
      $qrCodeURL = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($qrData) . '&amp;size=200x200';
      ?>
      <img src="<?php echo $qrCodeURL; ?>" alt="QR Code">

      <button type="button" onclick="goBack()">Go Back</button>

      <script>
        function goBack() {
          window.history.back();
        }
      </script>
    </div>
  </div>
</body>

</html>
