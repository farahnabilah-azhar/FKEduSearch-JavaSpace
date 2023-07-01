<?php
session_start();

// Check if the user is not logged in or does not have admin role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('You are disallowed to access this page'); window.location.href='./';</script>";
    exit; // Stop further execution of the script
}

// Database credentials
include 'db_connection.php';

// Retrieve the total number of users from the database
$stmt = $conn->prepare("SELECT COUNT(*) as total_users FROM user");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalUsers = $row['total_users'];

// Fetch user role data from the database
$query = "SELECT UserRole, COUNT(*) AS Count FROM user GROUP BY UserRole";
$result = $conn->query($query);

$role = array();
$userCounts = array();

// Store the user role and count data in arrays
while ($row = $result->fetch_assoc()) {
    $role[] = $row['UserRole'];
    $userCounts[] = $row['Count'];
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="content_style.css">
    <title>Admin Landing Page</title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
   <!-- Side navigation pane -->
   <?php include 'side_navigation.php'; ?>

   <div class="content">
        <!-- Header area -->
        <?php include 'header.php'; ?>
        <!-- Add your content here -->
            
        <h1>Dashboard</h1>

        <div class="total-users">
            <h3>Total Users</h3>
            <p><?php echo $totalUsers; ?></p>
        </div>

        <canvas id="userRoleChart"></canvas>
         <script>
            // Create a graph using Chart.js
            var ctx = document.getElementById('userRoleChart').getContext('2d');
            var userRoleChart = new Chart(ctx, {
               type: 'bar',
               data: {
                  labels: <?php echo json_encode($role); ?>,
                  datasets: [{
                     label: 'User Role',
                     data: <?php echo json_encode($userCounts); ?>,
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


    </div>
</body>

</html>
