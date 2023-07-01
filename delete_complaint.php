<?php
session_start();
?>

<?php
// Database credentials
include 'db_connection.php';

// Check if the complaintID is provided in the URL
if (isset($_GET['complaintID'])) {
    $complaintID = $_GET['complaintID']; // Assign the value to $complaintID

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Delete confirmation received
        $confirmation = $_POST['confirmation'];

        if ($confirmation === 'yes') {
            // Prepare and execute the delete statement
            $stmt = $conn->prepare("DELETE FROM complaint WHERE ComplaintID = ?");
            $stmt->bind_param("i", $complaintID);
            $stmt->execute();

            // Check if the delete operation was successful
            if ($stmt->affected_rows > 0) {
                echo "Complaint deleted successfully.";
            } else {
                echo "Failed to delete complaint.";
            }

            // Close the statement
            $stmt->close();

            // Redirect back to manage_complaint.php
            header("Location: manage_complaint.php");
            exit();
        } else {
            echo "Deletion canceled.";
            header("Location: manage_complaint.php");
            exit();
        }
    } else {
        // Retrieve the complaint data from the database
        $stmt = $conn->prepare("SELECT UserID FROM complaint WHERE ComplaintID = ?");
        $stmt->bind_param("i", $complaintID);
        $stmt->execute();
        $result = $stmt->get_result();
        $complaint = $result->fetch_assoc();

        // Display delete confirmation
        echo "Are you sure you want to delete this complaint: " . $complaintID . "?<br>";
        echo '<form method="POST" action="">';
        echo '<input type="hidden" name="complaintID" value="' . $complaintID . '">';
        echo 'Confirmation: <input type="radio" name="confirmation" value="yes"> Yes ';
        echo '<input type="radio" name="confirmation" value="no" checked> No ';
        echo '<button type="submit">Submit</button>';
        echo '</form>';
    }
} else {
    echo "ComplaintID not found.";
}

// Close the database connection
$conn->close();
?>
