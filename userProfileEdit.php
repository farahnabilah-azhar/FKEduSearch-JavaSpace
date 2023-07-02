<?php
session_start();
// connect to the database
$link = mysqli_connect("localhost", "root", "", "miniproject") or die(mysqli_connect_error());

if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['1'])) {
    $userID = $_SESSION['1'];
} else {
    // Handle the case when userID is not set in the session
    // Redirect the user or show an error message
    exit("User ID not found.");
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
} else {
    // Handle the case when email is not submitted
    // Redirect the user or show an error message
    exit("Email not provided.");
}

if (isset($_POST['researchAreaName'])) {
    $researchAreaName = $_POST['researchAreaName'];
} else {
    // Handle the case when researchAreaName is not submitted
    // Redirect the user or show an error message
    exit("Research Area not provided.");
}

if (isset($_POST['academicStatus_type'])) {
    $academicStatusType = $_POST["academicStatus_type"];
    $academicStatus_type = implode(',', $academicStatusType);
} else {
    // Handle the case when academicStatus_type is not submitted
    // Redirect the user or show an error message
    exit("Academic Status not provided.");
}

$instagram_userName = $_POST['instagram_userName'];
$linkedin_userName = $_POST['linkedin_userName'];

$query = "
    UPDATE user
    SET user_email = '$email'
    WHERE user_ID = '$userID';

    UPDATE research_areauserexpert
    SET researchArea_ID = '$researchAreaName'
    WHERE user_ID = '$userID';

    UPDATE academic_statususerexpert
    SET academicStatus_ID = '$academicStatus_type'
    WHERE user_ID = '$userID';

    UPDATE socialmedia
    SET instagram_userName = '$instagram_userName',
        linkedin_userName = '$linkedin_userName'
    WHERE user_ID = '$userID';
";

$result = mysqli_multi_query($link, $query) or die("Could not execute query in userProfile.php");

$alert_message = "User Profile Information has been updated!";
echo "<script>alert('$alert_message');</script>";
echo "<script type='text/javascript'> window.location='userProfileEdit.php' </script>";
?>
