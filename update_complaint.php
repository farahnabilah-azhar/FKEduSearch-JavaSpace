<?php
session_start();

include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaintID = $_POST['complaintID'];
    $complaintType = $_POST['complaintType'];
    $complaintDescription = $_POST['complaintDescription'];
    $complaintDateTime = $_POST['complaintDateTime'];
    $complaintStatus = $_POST['complaintStatus'];

    // Update the complaint data in the database
    $stmt = $conn->prepare("UPDATE complaint SET ComplaintType = ?, ComplaintDescription = ?, ComplaintDateTime = ?, ComplaintStatus = ? WHERE ComplaintID = ?");
    $stmt->bind_param("ssssi", $complaintType, $complaintDescription, $complaintDateTime, $complaintStatus, $complaintID);

    if ($stmt->execute()) {
        // Complaint data updated successfully
        echo "Complaint data updated successfully.";
    } else {
        // Error occurred while updating complaint data
        echo "Error updating complaint data: " . $conn->error;
    }

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // Redirect to the manage_complaint.php page after updating the user
        header("Location: manage_complaint.php");
        exit();
    } else {
        echo "Error: Failed to update the complaint.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
