<?php
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $expertName = $_POST['expertName'];
    $researchAreas = $_POST['researchAreas'];
    $academicStatus = $_POST['academicStatus'];

    // Convert the research areas array to a comma-separated string
    $researchAreasString = implode(", ", $researchAreas);

    // Prepare the CV file upload
    $cvFileName = $_FILES['cv']['name'];
    $cvTempName = $_FILES['cv']['tmp_name'];
    $cvPath = 'cv/' . $cvFileName;

    // Move the uploaded CV file to the desired location
    if (move_uploaded_file($cvTempName, $cvPath)) {
        // Insert the expert data into the database
        $stmt = $conn->prepare("INSERT INTO expert (ExpertName, ExpertResearchArea, ExpertAcademicStatus, ExpertCV, ExpertSocialMedia) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $expertName, $researchAreasString, $academicStatus, $cvPath, $_POST['socialMedia']);

        if ($stmt->execute()) {
            // Redirect to the manage expert page with a success message
            header("Location: manage_expert.php?status=success&message=Expert added successfully");
            exit();
        } else {
            // Redirect to the manage expert page with an error message
            header("Location: manage_expert.php?status=error&message=Failed to add expert");
            exit();
        }
    } else {
        // Redirect to the manage expert page with an error message
        header("Location: manage_expert.php?status=error&message=Failed to upload CV");
        exit();
    }
}

$conn->close();
?>
