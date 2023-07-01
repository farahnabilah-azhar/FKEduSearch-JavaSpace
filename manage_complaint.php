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

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <title>Manage Complaint</title>
  
</head>

<body>
   <!-- Side navigation pane -->
   <?php include 'side_navigation.php'; ?>

   <?php include 'db_connection.php'; 

   
   // Retrieve the total number of complaints from the database
   $stmt = $conn->prepare("SELECT COUNT(*) as total_complaints FROM complaint");
   $stmt->execute();
   $result = $stmt->get_result();
   $row = $result->fetch_assoc();
   $totalComplaints = $row['total_complaints'];

   // Fetch complaint type data from the database
   $query = "SELECT ComplaintType, COUNT(*) AS Count FROM complaint GROUP BY ComplaintType";
   $result = $conn->query($query);

   $complaintType = array();
   $complaintCounts = array();

   // Store the complaint type and count data in arrays
   while ($row = $result->fetch_assoc()) {
      $complaintType[] = $row['ComplaintType'];
      $complaintCounts[] = $row['Count'];
   }

   ?>

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
         <h1>Manage Complaint</h1>
         <div class="add-content-button">
            <a href="javascript:void(0)" onclick="showAddComplaintForm()">Add Complaint</a>
         </div>

         <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
         <div class="total-users">
            <h3>Total Complaints</h3>
            <p><?php echo $totalComplaints; ?></p>
         </div>
         <?php endif ?>

         <div class="white-box">
            <h2>Complaint</h2>
            <table class="content-table">
               <thead>
                  <tr>
                     <th>Complaint ID</th>
                     <th>Username</th>
                     <th>Type</th>
                     <th>Date & Time</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  include 'db_connection.php';

                  // Check if the user's role is set in the session
                  if (isset($_SESSION['role'])) {
                     $role = $_SESSION['role'];
                  }

                  // Check if the userID is set in the session
                  if (isset($_SESSION['username'])) {
                     $username = $_SESSION['username'];
                  } else {
                     // Redirect to the login page if the userID is not set
                     header("Location: index.html");
                     exit();
                  }

                  if ($role === 'admin') {
                     // Admin can view all complaints
                     $stmt = $conn->prepare("SELECT complaint.ComplaintID, user.UserName, complaint.ComplaintType, complaint.ComplaintDateTime, complaint.ComplaintStatus FROM complaint INNER JOIN user ON complaint.UserID = user.UserID");
                  } else {
                     // Users can only view their own complaints
                     $stmt = $conn->prepare("SELECT complaint.ComplaintID, user.UserName, complaint.ComplaintType, complaint.ComplaintDateTime, complaint.ComplaintStatus FROM complaint INNER JOIN user ON complaint.UserID = user.UserID WHERE user.UserName = ?");
                     $stmt->bind_param("s", $username);
                  }
                  $stmt->execute();
                  $result = $stmt->get_result();

                  // Display the complaint data in a table
                  while ($row = $result->fetch_assoc()) {
                     echo '<tr>';
                     echo '<td>' . $row['ComplaintID'] . '</td>';
                     echo '<td>' . $row['UserName'] . '</td>';
                     echo '<td>' . $row['ComplaintType'] . '</td>';
                     echo '<td>' . $row['ComplaintDateTime'] . '</td>';
                     echo '<td>' . $row['ComplaintStatus'] . '</td>';
                     echo '<td>
                     <a href="edit_complaint.php?complaintID=' . $row['ComplaintID'] . '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                     <a href="delete_complaint.php?complaintID=' . $row['ComplaintID'] . '" class="link-dark" onclick="return confirm(\'Are you sure you want to delete this complaint?\')"><i class="fa-solid fa-trash fs-5"></i></a>
                     </td>';
                     echo '</tr>';
                  }

                  // Close the database connection
                  $conn->close();
                  ?>
               </tbody>
            </table>
         </div>

         <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
         <canvas id="complaintTypeChart"></canvas>
         <script>
            // Create a graph using Chart.js
            var ctx = document.getElementById('complaintTypeChart').getContext('2d');
            var complaintTypeChart = new Chart(ctx, {
               type: 'bar',
               data: {
                  labels: <?php echo json_encode($complaintType); ?>,
                  datasets: [{
                     label: 'Complaint Types',
                     data: <?php echo json_encode($complaintCounts); ?>,
                     backgroundColor: 'rgba(75, 192, 192, 0.2)',
                     borderColor: 'rgba(75, 192, 192, 1)',
                     borderWidth: 1
                  }]
               },
               options: {
                  scales: {
                     y: {
                        beginAtZero: true,
                        stepSize: 1
                     }
                  }
               }
            });
         </script>
         <?php endif ?>

         <!-- Add Complaint Form -->
         <div class="add-content-form" id="addComplaintForm" style="display: none;">
            <div class="form-container">
               <span class="close-btn" onclick="hideAddComplaintForm()">&times;</span>
               <h3>Add Complaint</h3>
               <form action="add_complaint.php" method="post" enctype="multipart/form-data">
                  <label for="complaintType">Complaint Type:</label>
                  <select name="complaintType" id="complaintType" required>
                     <option value="Unsatisfied Experts Feedback">Unsatisfied Experts Feedback</option>
                     <option value="Wrongly Assigned Research Area">Wrongly Assigned Research Area</option>
                     <option value="Others">Others...</option>
                  </select>
                  <label for="complaintDescription">Description:</label>
                  <textarea name="complaintDescription" id="complaintDescription"></textarea>
                  <label for="complaintDateTime">Date & Time:</label>
                  <input type="text" name="complaintDateTime" id="complaintDateTime" readonly>
                  <?php
                  if (isset($role) && $role == 'admin') {
                     echo '
                     <label for="complaintStatus">Complaint Status:</label>
                     <select name="complaintStatus" id="complaintStatus" required>
                        <option value="NULL">Please select</option>
                        <option value="In Investigation">In Investigation</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Resolved">Resolved...</option>
                     </select>
                     ';
                  }
                  ?>
                  <button type="submit" name="submit">Add</button>
               </form>
            </div>
         </div>

         <script>
            function showAddComplaintForm() {
               document.getElementById('addComplaintForm').style.display = 'block';
            }

            function hideAddComplaintForm() {
               document.getElementById('addComplaintForm').style.display = 'none';
            }

            // Function to auto-generate date and time
            function generateDateTime() {
               var currentDateTime = new Date();
               var formattedDateTime = currentDateTime.toLocaleString();
               document.getElementById("complaintDateTime").value = formattedDateTime;
            }

            // Call the function to generate date and time when the form is loaded
            window.onload = generateDateTime;
         </script>

      </div>
   </div>
</body>
</html>
