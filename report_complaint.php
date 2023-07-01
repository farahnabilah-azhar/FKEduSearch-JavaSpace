<?php
session_start();
include 'db_connection.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Complaint List</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
	<link rel="stylesheet" type="text/css" href="content_style.css">
	
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="../../Functions/myfunctions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
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
  
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script>


        $complaintType = array();
        $sql = "SELECT ComplaintType, COUNT(*) AS count FROM complaint GROUP BY ComplaintType";
        $result = mysqli_query($mysql, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $complaintType['labels'][] = $row['type'];
                $complaintType['counts'][] = $row['count'];
            }
        }

        var complaintsType = <?php echo json_encode($complaintType); ?>;

        // Create the complaints type chart
        var complaintTypeCtx = document.getElementById("complaintTypeChart").getContext("2d");
        var complaintTypeChart = new Chart(complaintTypeCtx, {
            type: 'bar',
            data: {
                labels: complaintType.labels,
                datasets: [{
                    label: "Complaints by Type",
                    data: complaintType.counts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                }
            }
        });

        // Fetch complaints status from PHP
        $complaintStatus = array();
        $sql = "SELECT status, COUNT(*) AS count FROM complaint GROUP BY status";
        $result = mysqli_query($mysql, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $complaintStatus['labels'][] = $row['status'];
                $complaintStatus['counts'][] = $row['count'];
            }
        }

        var complaintStatus = <?php echo json_encode($complaintStatus); ?>;

        // Create the complaints status chart
        var complaintStatusCtx = document.getElementById("complaintStatusChart").getContext("2d");
        var complaintStatusChart = new Chart(complaintStatusCtx, {
            type: 'pie',
            data: {
                labels: complaintStatus.labels,
                datasets: [{
                    data: complaintStatus.counts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)', //resolved
                        'rgba(75, 192, 192, 0.5)', //ininvestigation
                        'rgba(54, 162, 235, 0.5)' //onhold
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)', //resolved
                        'rgba(75, 192, 192, 0.5)', //ininvestigation
                        'rgba(54, 162, 235, 1)' //onhold
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>


</body>

</html>