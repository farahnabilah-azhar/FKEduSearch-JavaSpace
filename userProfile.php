<?php
session_start();
// connect to the database
$link = mysqli_connect("localhost", "root", "", "fkedusearch") or die(mysqli_connect_error());

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

$query = "SELECT user.*, research_areauserexpert.researchArea_ID, academic_statususerexpert.academicStatus_ID, socialmedia.instagram_userName, socialmedia.linkedin_userName
          FROM user
          LEFT JOIN research_areauserexpert ON user.user_ID = research_areauserexpert.user_ID
          LEFT JOIN academic_statususerexpert ON user.user_ID = academic_statususerexpert.user_ID
          LEFT JOIN socialmedia ON user.user_ID = socialmedia.user_ID
          WHERE user.user_ID = '$userID'";

$result = mysqli_query($link, $query) or die("Could not execute query in userProfile.php");

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $name = $row['user_name'];
    $email = $row['user_email'];
    $researchAreaID = $row['researchArea_ID'];
    $academicStatusID = $row['academicStatus_ID'];
    $instagramUsername = $row['instagram_userName'];
    $linkedinUsername = $row['linkedin_userName'];

    // Fetch research area name based on the research area ID
    $researchAreaQuery = "SELECT researchAreaName FROM research_area WHERE researchArea_ID = '$researchAreaID'";
    $researchAreaResult = mysqli_query($link, $researchAreaQuery);
    $researchAreaName = mysqli_fetch_assoc($researchAreaResult)['researchAreaName'];

    // Fetch academic status types based on the academic status IDs
    $academicStatusQuery = "SELECT academicStatus_type FROM academic_status WHERE academicStatus_ID IN ($academicStatusID)";
    $academicStatusResult = mysqli_query($link, $academicStatusQuery);
    $academicStatusTypes = [];
    while ($academicStatusRow = mysqli_fetch_assoc($academicStatusResult)) {
        $academicStatusTypes[] = $academicStatusRow['academicStatus_type'];
    }
} else {
    // Handle the case when no user profile data is found
    $name = "";
    $email = "";
    $researchAreaName = "";
    $academicStatusTypes = [];
    $instagramUsername = "";
    $linkedinUsername = "";
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="content_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <title>User Profile</title>
</head>

<body>
    <!-- Side navigation pane -->
    <?php include 'includes/menu.php'; ?>

    <div class="content">
        <h1>User Profile</h1>

        <div class="container">
            <div class="profile-info">
                <h3>User Profile Information</h3>
                <p><strong>Name:</strong> <?php echo $name; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Research Area:</strong> <?php echo $researchAreaName; ?></p>
                <p><strong>Academic Status:</strong> <?php echo implode(', ', $academicStatusTypes); ?></p>
                <p><strong>Instagram Username:</strong> <?php echo $instagramUsername; ?></p>
                <p><strong>LinkedIn Username:</strong> <?php echo $linkedinUsername; ?></p>
                <!-- Add more profile fields as per your database structure -->
                <a href="userProfileEdit.php">Edit Profile</a>
            </div>
        </div>
    </div>
</body>

</html>
