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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="content_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Manage Expert</title>
</head>

<body>
    <!-- Side navigation pane -->
    <?php include 'side_navigation.php'; ?>
    <?php include 'db_connection.php';

    // Retrieve the total number of users from the database
    $stmt = $conn->prepare("SELECT COUNT(*) as total_experts FROM expert");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalExperts = $row['total_experts'];

    // Retrieve the count of experts for each academic status
    $stmt = $conn->prepare("SELECT ExpertAcademicStatus, COUNT(*) as count FROM expert GROUP BY ExpertAcademicStatus");
    $stmt->execute();
    $result = $stmt->get_result();

    // Store the research areas and their respective counts in arrays
    $academicStatus = [];
    $academicStatusCounts = [];

    while ($row = $result->fetch_assoc()) {
        $academicStatus[] = $row['ExpertAcademicStatus'];
        $academicStatusCounts[] = $row['count'];
    }
    ?>

    <div class="content">
        <!-- Header area -->
        <?php include 'header.php'; ?>
        <!-- Add your content here -->

        <h1>Manage Expert</h1>


        <div class="add-content-button">
            <a href="javascript:void(0)" onclick="showAddExpertForm()">Add Expert</a>
        </div>

        <div class="total-users">
            <h3>Total Experts</h3>
            <p><?php echo $totalExperts; ?></p>
        </div>


        <div class="white-box">
            <h2>Experts</h2>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>Expert ID</th>
                        <th>Name</th>
                        <th>Research Areas</th>
                        <th>Academic Status</th>
                        <th>CV</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'db_connection.php';

                    // Retrieve the expert data from the database
                    $stmt = $conn->prepare("SELECT ExpertID, ExpertName, ExpertResearchArea, ExpertAcademicStatus, ExpertCV FROM expert");
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Display the expert data in a table
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['ExpertID'] . '</td>';
                        echo '<td>' . $row['ExpertName'] . '</td>';
                        echo '<td>' . $row['ExpertResearchArea'] . '</td>';
                        echo '<td>' . $row['ExpertAcademicStatus'] . '</td>';
                        echo '<td>' . $row['ExpertCV'] . '</td>';
                        echo '<td>
                            <a href="edit_expert.php?expertID=' . $row['ExpertID'] . '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                            <a href="delete_expert.php?expertID=' . $row['ExpertID'] . '" class="link-dark" onclick="return confirm(\'Are you sure you want to delete this expert?\')"><i class="fa-solid fa-trash fs-5"></i></a>
                            </td>';
                        echo '</tr>';
                    }

                    // Close the database connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Add Expert Form -->
        <div class="add-content-form" id="addExpertForm">
            <div class="form-container">
                <span class="close-btn" onclick="hideAddExpertForm()">&times;</span>
                <h3>Add Expert</h3>
                <form action="add_expertold.php" method="post" enctype="multipart/form-data">
                    <label for="expertName">Name:</label>
                    <input type="text" name="expertName" id="expertName" required>
                    <label for="researchAreas">Research Areas:</label>
                    <input type="checkbox" name="researchAreas[]" value="Network"> Network<br>
                    <input type="checkbox" name="researchAreas[]" value="Cybersecurity"> Cybersecurity<br>
                    <input type="checkbox" name="researchAreas[]" value="Software"> Software<br>
                    <input type="checkbox" name="researchAreas[]" value="Multimedia"> Multimedia<br>
                    <label for="academicStatus">Academic Status:</label>
                    <input type="text" name="academicStatus" id="academicStatus" required>
                    <label for="cv">CV:</label>
                    <input type="file" name="cv" id="cv" required accept=".pdf">
                    <label for="socialMedia">Social Media:</label>
                    <input type="url" name="socialMedia" id="socialMedia" required>
                    <button type="submit">Add</button>
                </form>
            </div>
        </div>

        <!-- Chart for Expert Research Areas -->
        <canvas id="academicStatusChart"></canvas>

        <script>
            function showAddExpertForm() {
                document.getElementById('addExpertForm').style.display = 'block';
            }

            function hideAddExpertForm() {
                document.getElementById('addExpertForm').style.display = 'none';
            }

            // Create a graph using Chart.js
            var ctx = document.getElementById('academicStatusChart').getContext('2d');
            var academicStatusChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($academicStatus); ?>,
                    datasets: [{
                        label: 'Academic Status',
                        data: <?php echo json_encode($academicStatusCounts); ?>,
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
