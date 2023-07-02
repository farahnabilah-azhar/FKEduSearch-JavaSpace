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
  echo"thank you for your rate";
  

  // Close the statement and database connection
  $stmt->close();
  $conn->close();
}
?>
