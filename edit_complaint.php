<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="content_style.css">
  <title>Edit Complaint</title>
  
</head>

  <body>
     <!-- Side navigation pane -->
   <?php include 'side_navigation.php'; ?>

    <div class="content">
      <div class="container">
      <!-- Add your content here -->
        <h2>Edit Complaint</h2>

        <?php
        // Check if the user ID is provided
        if (!isset($_GET['complaintID'])) {
          header("Location: manage_complaint.php");
          exit();
          }

          $complaintID = $_GET['complaintID'];

          include 'db_connection.php';

            // Retrieve the user data from the database
            $stmt = $conn->prepare("SELECT ComplaintType, ComplaintDescription, ComplaintDateTime, ComplaintStatus FROM complaint WHERE ComplaintID = ?");
            $stmt->bind_param("i", $complaintID);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists
            if ($result->num_rows == 0) {
                header("Location: manage_complaint.php");
                exit();
            }

            $row = $result->fetch_assoc();

            $conn->close();
            ?>

        <!-- Edit User Form -->
        <body>
              <div class="edit-content-form">
                  <h2>Edit User</h2>
                  <form action="update_complaint.php" method="post">
                  <input type="hidden" name="complaintID" value="<?php echo $complaintID; ?>"
                      <label for="complaintType">Complaint Type:</label>
                      <select name="complaintType" id="complaintType" required>
                          <option value="Unsatisfied Experts Feedback" <?php if ($row['ComplaintType'] == 'Unsatisfied Experts Feedback') echo 'selected'; ?>>Unsatisfied Experts Feedback</option>
                          <option value="Wrongly Assigned Research Area" <?php if ($row['ComplaintType'] == 'Wrongly Assigned Research Area') echo 'selected'; ?>>Wrongly Assigned Research Area</option>
                          <option value="Others" <?php if ($row['ComplaintType'] == 'Others') echo 'selected'; ?>>Others</option>
                      </select>
                      <label for="complaintDescription">Complaint Description:</label>
                      <input type="textarea" name="complaintDescription" id="complaintDescription" value="<?php echo $row['ComplaintDescription']; ?>" required>
                      <label for="complaintDateTime">Date & Timer:</label>
                      <input type="text" name="complaintDateTime" id="complaintDateTime" value="<?php echo $row['ComplaintDateTime']; ?>" required>
                      <?php
                        if (isset($userRole) && $userRole == 'admin') {
                          echo '
                            <label for="complaintStatus">Complaint Status:</label>
                            <select name="complaintStatus" id="complaintStatus" required>
                              <option value="NULL" >Please select</option>
                              <option value="In Investigation">In Investigation</option>
                              <option value="On Hold">On Hold</option>
                              <option value="Resolved">Resolved...</option>
                            </select>
                              </select>
                          ' ;
                        }
                        ?>
                      <button type="submit">Update</button>
                      <button type="button" onclick="cancelUpdate()">Cancel</button>
                  </form>

                  <script>
                      function cancelUpdate() {
                          window.location = "manage_complaint.php";
                      }
                  </script>
              </div>
        </body>
      </div>
    </div>
  </body>

</html>
