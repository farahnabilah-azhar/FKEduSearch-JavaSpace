<?php
session_start();
include 'db_connection.php';

// Check if the user's role is set in the session
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($role==='admin'){
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username']; // Retrieve username from session
            $complaintType = $_POST['complaintType'];
            $complaintDescription = $_POST['complaintDescription'];
            $complaintDateTime = $_POST['complaintDateTime'];
            $complaintStatus = $_POST['complaintStatus'];

            // Retrieve the UserID based on the username
            $stmt = $conn->prepare("SELECT UserID FROM user WHERE UserName = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $userID = $row['UserID'];

                // Get the current date and time
                $complaintDateTime = date('Y-m-d H:i:s');

                if($role==='admin'){
                // Prepare and execute the SQL query
                $stmt = $conn->prepare("INSERT INTO complaint (UserID, ComplaintType, ComplaintDescription, ComplaintDateTime, ComplaintStatus) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $userID, $complaintType, $complaintDescription, $complaintDateTime, $complaintStatus);
                $stmt->execute();
                }else{
                    $stmt = $conn->prepare("INSERT INTO complaint (UserID, ComplaintType, ComplaintDescription, ComplaintDateTime) VALUES (?, ?, ?, ?) ");
                $stmt->bind_param("isss", $userID, $complaintType, $complaintDescription, $complaintDateTime);
                $stmt->execute();
                }

                // Redirect to the manage_complaint.php page after adding the complaint
                header("Location: manage_complaint.php?msg=Complaint added successfully");
                exit();
            } else {
                echo "Error: Failed to retrieve UserID.";
            }
        } else {
            // Redirect to the login page if the user is not logged in
            header("Location: index.html");
            exit();
        }
    }else{
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username']; // Retrieve username from session
            $complaintType = $_POST['complaintType'];
            $complaintDescription = $_POST['complaintDescription'];
            $complaintDateTime = $_POST['complaintDateTime'];

            // Retrieve the UserID based on the username
            $stmt = $conn->prepare("SELECT UserID FROM user WHERE UserName = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $userID = $row['UserID'];

                // Get the current date and time
                $complaintDateTime = date('Y-m-d H:i:s');

                if($role==='admin'){
                // Prepare and execute the SQL query
                $stmt = $conn->prepare("INSERT INTO complaint (UserID, ComplaintType, ComplaintDescription, ComplaintDateTime, ComplaintStatus) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $userID, $complaintType, $complaintDescription, $complaintDateTime, $complaintStatus);
                $stmt->execute();
                }else{
                    $stmt = $conn->prepare("INSERT INTO complaint (UserID, ComplaintType, ComplaintDescription, ComplaintDateTime) VALUES (?, ?, ?, ? )");
                $stmt->bind_param("isss", $userID, $complaintType, $complaintDescription, $complaintDateTime);
                $stmt->execute();
                }

                // Redirect to the manage_complaint.php page after adding the complaint
                header("Location: manage_complaint.php?msg=Complaint added successfully");
                exit();
            } else {
                echo "Error: Failed to retrieve UserID.";
            }
        } else {
            // Redirect to the login page if the user is not logged in
            header("Location: index.html");
            exit();
        }
    }
}
?>
