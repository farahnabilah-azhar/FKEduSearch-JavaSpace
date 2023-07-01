<?php
include "db_connection.php";

if (isset($_POST["researchTitle"])) {
   $researchTitle = $_POST['researchTitle'];
   $researchDescription = $_POST['researchDescription'];
   $researchDate = $_POST['researchDate'];
   $researchArea = implode(", ", $_POST['researchArea']); // Convert array to string
   $researchType = $_POST['researchType'];

   // Convert researchDate to the appropriate format based on the dateType selected
   $dateType = $_POST['dateType'];
   if ($dateType === 'year') {
      $researchDate = $_POST['researchYear'];
   } elseif ($dateType === 'yearMonth') {
      $researchDate = $_POST['researchMonth'];
   } elseif ($dateType === 'fullDate') {
      $researchDate = $_POST['researchDate'];
   }

   $sql = "INSERT INTO research (ResearchTitle, ResearchDescription, ResearchDate, ResearchArea, ResearchType)
           VALUES (?, ?, ?, ?, ?)";

   $stmt = $conn->prepare($sql);
   $stmt->bind_param("sssss", $researchTitle, $researchDescription, $researchDate, $researchArea, $researchType);

   if ($stmt->execute()) {
      header("Location: manage_research.php?msg=New record created successfully");
      exit();
   } else {
      echo "Failed: " . $stmt->error;
   }

   $stmt->close();
   $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Add Research</title>
</head>
<body>
   <h2>Add Research</h2>
   <form method="POST" action="">
      <label for="researchTitle">Title:</label>
      <input type="text" name="researchTitle" required><br><br>

      <label for="researchDescription">Description:</label>
      <textarea name="researchDescription" required></textarea><br><br>

      <label for="researchDate">Date:</label>
      <input type="text" name="researchDate" required><br><br>

      <label for="researchArea">Area:</label>
      <input type="checkbox" name="researchArea[]" value="Area 1"> Area 1
      <input type="checkbox" name="researchArea[]" value="Area 2"> Area 2
      <input type="checkbox" name="researchArea[]" value="Area 3"> Area 3<br><br>

      <label for="researchType">Type:</label>
      <select name="researchType" required>
         <option value="">Select Type</option>
         <option value="Type 1">Type 1</option>
         <option value="Type 2">Type 2</option>
         <option value="Type 3">Type 3</option>
      </select><br><br>

      <label for="dateType">Date Type:</label>
      <select name="dateType" required>
         <option value="year">Year</option>
         <option value="yearMonth">Year and Month</option>
         <option value="fullDate">Full Date</option>
      </select><br><br>

      <input type="submit" name="submit" value="Add Research">
   </form>
</body>
</html>
