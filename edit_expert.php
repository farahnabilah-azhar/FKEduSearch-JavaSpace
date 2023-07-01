<?php
session_start();
include 'db_connection.php';

// Check if the expert ID is provided in the query string
if (isset($_GET['expertID'])) {
    $expertID = $_GET['expertID'];

    // Retrieve the expert data from the database
    $stmt = $conn->prepare("SELECT * FROM expert WHERE ExpertID = ?");
    $stmt->bind_param("i", $expertID);
    $stmt->execute();
    $result = $stmt->get_result();
    $expert = $result->fetch_assoc();

    if (!$expert) {
        // Redirect to the manage expert page with an error message if the expert doesn't exist
        header("Location: manage_expert.php?status=error&message=Expert not found");
        exit();
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expertID = $_POST['expertID'];
    $expertName = $_POST['expertName'];
    $researchAreas = implode(", ", $_POST['researchAreas']);
    $academicStatus = $_POST['academicStatus'];

    // Prepare the SQL query to update the expert data
    $stmt = $conn->prepare("UPDATE expert SET ExpertName = ?, ExpertResearchArea = ?, ExpertAcademicStatus = ? WHERE ExpertID = ?");
    $stmt->bind_param("sssi", $expertName, $researchAreas, $academicStatus, $expertID);

    if ($stmt->execute()) {
        // Redirect to the manage expert page with a success message
        header("Location: manage_expert.php?status=success&message=Expert updated successfully");
        exit();
    } else {
        // Redirect to the manage expert page with an error message
        header("Location: manage_expert.php?status=error&message=Failed to update expert");
        exit();
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="content_style.css">
    <title>Edit Expert</title>
</head>
<body>
    <!-- Side navigation pane -->
    <?php include 'side_navigation.php'; ?>

    <div class="content">
            <div class="container">
            <div class="edit-content-form" id="addPostForm">
    <h2>Edit Expert</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="expertID" value="<?php echo $expert['ExpertID']; ?>">

        <label for="expertName">Name:</label>
        <input type="text" name="expertName" value="<?php echo $expert['ExpertName']; ?>" required><br><br>

        <label for="researchAreas">Research Areas:</label>
        <input type="checkbox" name="researchAreas[]" value="Network" <?php if (strpos($expert['ExpertResearchArea'], 'Network') !== false) echo 'checked'; ?>> Network
        <input type="checkbox" name="researchAreas[]" value="Cybersecurity" <?php if (strpos($expert['ExpertResearchArea'], 'Cybersecurity') !== false) echo 'checked'; ?>> Cybersecurity
        <input type="checkbox" name="researchAreas[]" value="Software" <?php if (strpos($expert['ExpertResearchArea'], 'Software') !== false) echo 'checked'; ?>> Software
        <input type="checkbox" name="researchAreas[]" value="Multimedia" <?php if (strpos($expert['ExpertResearchArea'], 'Multimedia') !== false) echo 'checked'; ?>> Multimedia<br><br>

        <label for="academicStatus">Academic Status:</label>
        <input type="text" name="academicStatus" value="<?php echo $expert['ExpertAcademicStatus']; ?>" required><br><br>

        <button type="submit">Update Post</button>
            <button type="button" onclick="cancelUpdate()">Cancel</button>
    </form>

    <script>
                    function cancelUpdate() {
                        window.location = "manage_post.php";
                    }
                </script>
            </div>
            </div>
    </div>
</body>
</html>
