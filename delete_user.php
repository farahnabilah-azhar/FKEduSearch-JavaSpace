<?php
// Database credentials
include 'db_connection.php';

// Check if the userID is provided in the URL
if (isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Delete confirmation received
        $confirmation = $_POST['confirmation'];

        if ($confirmation === 'yes') {
            // Prepare and execute the delete statement
            $stmt = $conn->prepare("DELETE FROM user WHERE UserID = ?");
            $stmt->bind_param("i", $userID);
            $stmt->execute();

            // Check if the delete operation was successful
            if ($stmt->affected_rows > 0) {
                echo "User deleted successfully.";
            } else {
                echo "Failed to delete user.";
            }

            // Close the statement
            $stmt->close();

            // Redirect back to manage_user.php
            header("Location: manage_user.php");
            exit();
        } else {
            echo "Deletion canceled.";
            header("Location: manage_user.php");
            exit();
        }
    } else {
        // Retrieve the user data from the database
        $stmt = $conn->prepare("SELECT UserName FROM user WHERE UserID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Display delete confirmation
        echo "Are you sure you want to delete user: " . $user['UserName'] . "?<br>";
        echo '<form method="POST" action="">';
        echo '<input type="hidden" name="userID" value="' . $userID . '">';
        echo 'Confirmation: <input type="radio" name="confirmation" value="yes"> Yes ';
        echo '<input type="radio" name="confirmation" value="no" checked> No ';
        echo '<button type="submit">Submit</button>';
        echo '</form>';
    }
} else {
    echo "UserID not provided.";
}

// Close the database connection
$conn->close();
?>
