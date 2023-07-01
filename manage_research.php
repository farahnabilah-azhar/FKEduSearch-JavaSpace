<?php
session_start();

// Check if the user's role is set in the session
    if (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
  <link rel="stylesheet" type="text/css" href="content_style.css">

  <title>Manage Research</title>
  </head>

<body>
   <!-- Side navigation pane -->
   <?php include 'side_navigation.php'; ?>

   <div class="content">
        <!-- Header area -->
        <?php include 'header.php'; ?>
        <!-- Add your content here -->
  <div class="container">
    <?php
    if (isset($_GET["msg"])) {
      $msg = $_GET["msg"];
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      ' . $msg . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    ?>
    <h1>Manage Research</h1>
    <div class="add-content-button">
            <a href="javascript:void(0)" onclick="showAddResearchForm()">Add Research</a>
    </div>

    <div class="white-box">
            <h2>Research</h2>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>Research ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  
                  <?php
                     include 'db_connection.php';

                     // Retrieve the expert data from the database
                     $stmt = $conn->prepare("SELECT ResearchID, ResearchTitle, ResearchArea, ResearchDate, ResearchType FROM research");
                     $stmt->execute();
                     $result = $stmt->get_result();

                     // Display the expert data in a table
                     while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['ResearchID'] . '</td>';
                        echo '<td>' . $row['ResearchTitle'] . '</td>';
                        echo '<td>' . $row['ResearchArea'] . '</td>';
                        echo '<td>' . $row['ResearchDate'] . '</td>';
                        echo '<td>' . $row['ResearchType'] . '</td>';
                        echo '<td>
                        <a href="edit_research.php?ResearchID=' . $row['ResearchID'] . '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                        <a href="delete_research.php?ResearchID=' . $row['ResearchID'] . '" class="link-dark" onclick="return confirm(\'Are you sure you want to delete this expert?\')"><i class="fa-solid fa-trash fs-5"></i></a>
                        </td>';
                        echo '</tr>';
                     }

                     // Close the database connection
                     $conn->close();
                     ?>
                </tbody>
            </table>
        </div>

       <!-- Add Research Form -->
            <div class="add-content-form" id="addResearchForm">
                <div class="form-container">
                    <span class="close-btn" onclick="hideAddResearchForm()">&times;</span>
                    <h3>Add Reserch</h3>
                    <form action="add_research.php" method="post" enctype="multipart/form-data">
                           <label for="researchTitle">Research Title:</label>
                           <input type="text" name="researchTitle" id="researchTitle">
                           <label for="researchDescription">Research Description:</label>
                           <input type="text" name="researchDescription" id="researchDescription">
                           <label for="researchDate">Date:</label>
                              <select name="dateType" id="dateType" onchange="handleDateTypeChange()">
                                 <option value=NULL>Please select</option>
                                 <option value="year">Year only</option>
                                 <option value="yearMonth">Year and month</option>
                                 <option value="fullDate">Full date</option>
                              </select>
                                 
                              <div id="yearField" style="display: none;">
                                 <label for="researchYear">Year:</label>
                                 <input type="text" name="researchYear" id="researchYear" required>
                              </div>

                              <div id="monthField" style="display: none;">
                                 <label for="researchMonth">Year and Month:</label>
                                 <input type="month" name="researchMonth" id="researchMonth" required>
                              </div>

                              <div id="fullDateField" style="display: none;">
                                 <label for="reseachDate">Date:</label>
                                 <input type="date" name="researchDate" id="researchDate" required>
                              </div>
                           <label for="researchArea">Research Areas:</label>
                              <input type="checkbox" name="researchArea[]" value="Network"> Network<br>
                              <input type="checkbox" name="researchArea[]" value="Cybersecurity"> Cybersecurity<br>
                              <input type="checkbox" name="researchArea[]" value="Software"> Software<br>
                              <input type="checkbox" name="researchArea[]" value="Multimedia"> Multimedia<br>
                           <label for="researchType">Research Type:</label>
                           <select name="researchType" id="researchType" required>
                              <option value="Journal">Journal</option>
                              <option value="Article">Article</option>
                              <option value="Book">Book</option>
                           </select>
                        <button type="submit" name="submit">Add</button>
                    </form>
                </div>
            </div>

        <script>
              function showAddResearchForm() {
                document.getElementById('addResearchForm').style.display = 'block';
            }

            function hideAddResearchForm() {
                document.getElementById('addResearchForm').style.display = 'none';
            }

            function handleDateTypeChange() {
            var dateType = document.getElementById('dateType').value;
            var yearField = document.getElementById('yearField');
            var monthField = document.getElementById('monthField');
            var fullDateField = document.getElementById('fullDateField');

            if (dateType === 'year') {
                  yearField.style.display = 'block';
                  monthField.style.display = 'none';
                  fullDateField.style.display = 'none';
            } else if (dateType === 'yearMonth') {
                  yearField.style.display = 'none';
                  monthField.style.display = 'block';
                  fullDateField.style.display = 'none';
            } else if (dateType === 'fullDate') {
                  yearField.style.display = 'none';
                  monthField.style.display = 'none';
                  fullDateField.style.display = 'block';
            } else {
                  yearField.style.display = 'none';
                  monthField.style.display = 'none';
                  fullDateField.style.display = 'none';
            }

         }
        </script>
   
    </div>
  </div>

  <!-- Bootstrap -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script> -->

</body>

</html>