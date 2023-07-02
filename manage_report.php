<?php
session_start();
include 'db_connection.php';

$stmt = $conn->prepare("SELECT COUNT(*) as total_post FROM post");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_post = $row['total_post'];

// Fetch user role data from the database
$query = "SELECT PostStatus, COUNT(*) AS Count FROM post GROUP BY PostStatus";
$result = $conn->query($query);

$userRoles = array();
$userCounts = array();

// Store the user role data in arrays
while ($row = $result->fetch_assoc()) {
    $PostStatus[] = $row['PostStatus'];
    $userCounts[] = $row['Count'];
}

// Close the database connection
$stmt->close();
$conn->close();
?>




<?php

include 'db_connection.php';

$stmt = $conn->prepare("SELECT COUNT(*) as total_complaint FROM complaint");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalUsers = $row['total_complaint'];

// Fetch user role data from the database
$query = "SELECT ComplaintType, COUNT(*) AS Count1 FROM complaint GROUP BY ComplaintType";
$result = $conn->query($query);

$userComplaint = array();
$Counts = array();

// Store the user role data in arrays
while ($row = $result->fetch_assoc()) {
    $ComplaintType[] = $row['ComplaintType'];
    $Counts[] = $row['Count1'];
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <link rel="stylesheet" type="text/css" href="content_style.css">

    <title>Manage Report</title>

</head>

<body>
   <!-- Side navigation pane -->
   <?php include 'side_navigation.php'; ?>

   <div class="content">
        <!-- Header area -->
        <?php include 'header.php'; ?>
        <!-- Add your content here -->
        <h1>Manage Report</h1>
        
        <div class="white-box">
        <h2>All posts</h2>
            <div class="content-table">
                <!-- Search Form -->
                <form action="" method="GET" class="search-form">
                    <!-- Your form inputs here -->
                    <input type="text" name="search" placeholder="Search">
                    <button type="submit">Search</button><br><br>
                    <div class="content-table"><button onclick="sortTable()">Sort</button></div>
                </form>
                
            </div>
            
    
  
</form>

            

            <table id="myTable" class="content-table">
                <thead>
                    <tr>
                        <th>Post ID</th>
                        <th>Username</th>
                        <th>Title</th>
                        <th>Date</th></th>
                        <th>Category</th>
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

                    // Check if the search query is set
if (isset($_GET['search'])) {
    // Sanitize the search query to prevent SQL injection
    $search = "%" . mysqli_real_escape_string($conn, $_GET['search']) . "%";

    // Construct the SQL query based on user role
    if ($role === 'admin') {
        // Admin can view all posts
        $query = "SELECT post.PostID, user.UserName, post.PostTitle, post.PostCreatedDate, category.CategoryName, post.PostStatus FROM post INNER JOIN user ON post.UserID = user.UserID INNER JOIN category ON post.CategoryID = category.CategoryID WHERE post.PostTitle LIKE '$search' OR user.UserName LIKE '$search' OR category.CategoryName LIKE '$search'";
    } else {
        // Users and experts can only view their own posts
        $query = "SELECT post.PostID, user.UserName, post.PostTitle, post.PostCreatedDate, category.CategoryName, post.PostStatus FROM post INNER JOIN user ON post.UserID = user.UserID INNER JOIN category ON post.CategoryID = category.CategoryID WHERE user.UserName = '$username' AND post.PostTitle LIKE '$search' OR user.UserName LIKE '$search' OR category.CategoryName LIKE '$search'";
    }

    // Execute the query and fetch the results
    $result = $conn->query($query);

    // Display the search results in a table
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['PostID'] . '</td>';
        echo '<td>' . $row['UserName'] . '</td>';
        echo '<td>' . $row['PostTitle'] . '</td>';
        echo '<td>' . $row['PostCreatedDate'] . '</td>';
        echo '<td>' . $row['CategoryName'] . '</td>';
        echo '<td>' . $row['PostStatus'] . '</td>';
        echo '<td>
        <a href="edit_post.php?postID=' . $row['PostID'] . '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
        <a href="delete_post.php?postID=' . $row['PostID'] . '" class="link-dark" onclick="return confirm(\'Are you sure you want to delete this post?\')"><i class="fa-solid fa-trash fs-5"></i></a>
        </td>';
        echo '</tr>';
    }
    // Close the database connection
    $conn->close();
} else {
    // If no search query is provided, retrieve and display all posts

    // Construct the SQL query based on user role
    if ($role === 'admin') {
        // Admin can view all posts
        $query = "SELECT post.PostID, user.UserName, post.PostTitle, post.PostCreatedDate, category.CategoryName, post.PostStatus FROM post INNER JOIN user ON post.UserID = user.UserID INNER JOIN category ON post.CategoryID = category.CategoryID";
    } else {
        // Users and experts can only view their own posts
        $query = "SELECT post.PostID, user.UserName, post.PostTitle, post.PostCreatedDate, category.CategoryName, post.PostStatus FROM post INNER JOIN user ON post.UserID = user.UserID INNER JOIN category ON post.CategoryID = category.CategoryID WHERE user.UserName = '$username'";
    }

    // Execute the query and fetch the results
    $result = $conn->query($query);

                        // Display the posts in a table
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['PostID'] . '</td>';
                            echo '<td>' . $row['UserName'] . '</td>';
                            echo '<td>' . $row['PostTitle'] . '</td>';
                            echo '<td>' . $row['PostCreatedDate'] . '</td>';
                            echo '<td>' . $row['CategoryName'] . '</td>';
                            echo '<td>' . $row['PostStatus'] . '</td>';
                            echo '<td>
                            <a href="edit_post.php?postID=' . $row['PostID'] . '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                            <a href="delete_post.php?postID=' . $row['PostID'] . '" class="link-dark" onclick="return confirm(\'Are you sure you want to delete this post?\')"><i class="fa-solid fa-trash fs-5"></i></a>
                            </td>';
                            echo '</tr>';
                        }

                        // Close the database connection
                        $conn->close();
                    }
                    
                    ?>
                </tbody>
            </table>
        </div>


        
    <div class="add-content-button">
       
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
   
   


    


    <div class="total-users">
            <h3>Total Post</h3>
            <p><?php echo $total_post; ?></p>
        </div>

        <div style="display: flex; justify-content: center;">
  <div style="width: 600px; height: 400px;">
    <canvas id="userRoleChart"></canvas>
  </div>
</div>
<style>
  canvas {
    width: 100%;
    height: 100%;
  }
</style>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Create a graph using Chart.js
  var ctx = document.getElementById('userRoleChart').getContext('2d');
  var userRoleChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($PostStatus); ?>,
      datasets: [
        {
          label: 'User Roles',
          data: <?php echo json_encode($userCounts); ?>,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }
      ]
    },
    options: {
      responsive: false, // Disable responsiveness
      maintainAspectRatio: false, // Disable aspect ratio calculation
     
      scales: {
        y: {
          beginAtZero: true,
          stepSize: 1
        }
      }
    }
  });
</script>


<div class="white-box">
            <h2>Complaint</h2>
            <table id="mytable" class="content-table">
              <div class="content-table"><button onclick="sortTable()">Sort</button></div>
                <thead>
                    <tr>
                        <th>ComplaintID ID</th>
                        <th>UserID</th>
                        <th>FeedbackID</th>
                        <th>ComplaintType</th>
                        <th>ComplaintDateTime</th>
                        <th>ComplaintStatus</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  
                  <?php
                     include 'db_connection.php';
                    

                     // Retrieve the expert data from the database
                     $stmt = $conn->prepare("SELECT ComplaintID, UserID, FeedbackID, ComplaintType, ComplaintDateTime, ComplaintStatus FROM complaint");
                     $stmt->execute();
                     $result = $stmt->get_result();

                     // Display the expert data in a table
                     while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['ComplaintID'] . '</td>';
                        echo '<td>' . $row['UserID'] . '</td>';
                        echo '<td>' . $row['FeedbackID'] . '</td>';
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



<div class="total-users">
            <h3>Total complaint</h3>
            <p><?php echo $totalUsers; ?></p>
        </div>

        <div style="display: flex; justify-content: center;">
  <div style="width: 600px; height: 400px;">
    <canvas id="RoleChart"></canvas>
  </div>
</div>
<style>
  canvas {
    width: 100%;
    height: 100%;
  }
</style>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Create a graph using Chart.js
  var ctxs = document.getElementById('RoleChart').getContext('2d');
  var RoleChart = new Chart(ctxs, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($ComplaintType); ?>,
      datasets: [
        {
          label: 'User Roles',
          data: <?php echo json_encode($Counts); ?>,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }
      ]
    },
    options: {
      responsive: false, // Disable responsiveness
      maintainAspectRatio: false, // Disable aspect ratio calculation
     
      scales: {
        y: {
          beginAtZero: true,
          stepSize: 1
        }
      }
    }
  });
</script>

...
<script>
    function sortTable() {
        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("myTable");
        switching = true;
        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[0];
                y = rows[i + 1].getElementsByTagName("TD")[0];
                if (Number(x.innerHTML) > Number(y.innerHTML)) {
                    shouldSwitch = true;
                    break;
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
        }
    }
</script>
...



...
<script>
    function sortTable() {
        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("myTable");
        switching = true;
        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[0];
                y = rows[i + 1].getElementsByTagName("TD")[0];
                if (Number(x.innerHTML) > Number(y.innerHTML)) {
                    shouldSwitch = true;
                    break;
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
        }
    }
</script>
...






</body>

</html>