<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      text-align: center;
      margin-top: 100px;
    }

    .message {
      font-size: 24px;
      color: #333;
      margin-bottom: 20px;
    }

    .redirect {
      font-size: 18px;
      color: #666;
    }
  </style>
</head>
<body>
<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the rating value from the form
  $rating = $_POST["rating"];

  // TODO: Perform database connection and insertion logic here
  // Example code using MySQLi
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "fkedusearch";

  // Create a new MySQLi connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement
  $stmt = $conn->prepare ("INSERT INTO `ratings`(`rating`) VALUES (?)");
  $stmt->bind_param("i", $rating);
  $stmt->execute();

  // Close the statement and database connection
  $stmt->close();
  $conn->close();

  // Display the thank you message and redirect
  echo '<div class="message">Thank you for your rating!</div>';
  echo '<div class="redirect">You will be redirected to the homepage shortly...</div>';

  // Redirect to homepage.php after 2 seconds
  header("refresh:2; url=homepage.php");
  exit(); // Ensure no further code execution after the redirect
}
?>
</body>
</html>
