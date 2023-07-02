<?php
// Connect to the database
$link = mysqli_connect("localhost", "root", "", "fkedusearch");

// Check the connection
if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $userID = $_POST['userID'];
    $userName = $_POST['userName'];
    $userEmail = $_POST['userEmail'];
    // ... Retrieve other form fields

    // Validate and update the user profile in the database
    // ...

    // Redirect back to the user profile page
    header("Location: userProfile.php");
    exit;
}

// Fetch user profile data
$userID = 3; // Change this with the actual user ID
$query = "SELECT * FROM user WHERE UserID = $userID";
$result = mysqli_query($link, $query);

// Check if the query executed successfully
if (!$result) {
    die("Query execution failed: " . mysqli_error($link));
}

// Check if user exists
if (mysqli_num_rows($result) === 0) {
    die("User not found.");
}

// Fetch user data from the result
$userData = mysqli_fetch_assoc($result);

// Close the database connection
mysqli_close($link);
?>
<?php
// Check if a file was uploaded
if (isset($_FILES['userCertificate'])) {
    $file = $_FILES['userCertificate'];

    // Check for errors during the file upload
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileName = $file['name'];
        $fileTmpPath = $file['tmp_name'];

        // Specify the directory where you want to save the uploaded file
        $targetDirectory = 'uploads/';
        $targetFilePath = $targetDirectory . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
            // File upload was successful
            echo 'File uploaded successfully.';
        } else {
            // File upload failed
            echo 'File upload failed.';
        }
    } else {
        // Error during file upload
        echo 'Error uploading file.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
        }

        .form-heading {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        .form-field {
            margin-bottom: 10px;
        }

        .form-actions {
            text-align: center;
            margin-top: 20px;
        }

        .form-actions .button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="form-heading">Edit User Profile</h2>
        <form method="POST" action="">
            
            <div class="form-field">
                <label class="form-label" for="userName">UserName:</label>
                <input type="text" id="userName" name="userName" value="<?php echo $userData['UserName']; ?>">
            </div>
            <div class="form-field">
                <label class="form-label" for="userEmail">UserEmail:</label>
                <input type="email" id="userEmail" name="userEmail" value="<?php echo $userData['UserEmail']; ?>">
            </div>
            <div class="form-field">
                <label class="form-label" for="userRole">UserRole:</label>
                <input type="text" id="userRole" name="userRole" value="<?php echo $userData['UserRole']; ?>">
            </div>
            <div class="form-field">
                <label class="form-label" for="categoryID">CategoryID:</label>
                <input type="text" id="categoryID" name="categoryID" value="<?php echo $userData['CategoryID']; ?>">
            </div>
            <div class="form-field">
                <label class="form-label" for="userAcademicStatus">UserAcademicStatus:</label>
                <input type="text" id="userAcademicStatus" name="userAcademicStatus" value="<?php echo $userData['UserAcademicStatus']; ?>">
            </div>
            <div class="form-field">
                <label class="form-label" for="userCertificate">UserCertificate:</label>
                <input type="file" id="userCertificate" name="userCertificate">
            </div>

            <div class="form-field">
                <label class="form-label" for="userQRCode">UserQRCode:</label>
                <?php if (!empty($userData['UserQRCode'])): ?>
                 <img src="<?php echo $userData['UserQRCode']; ?>" alt="QR Code">
                <?php endif; ?>
                <input type="file" id="userQRCode" name="userQRCode">
            </div>

            <div class="form-field">
                <label class="form-label" for="userStatus">UserStatus:</label>
                <span id="userStatus"><?php echo $userData['UserStatus']; ?></span>
            </div>

            
            <div class="form-field">
                <label class="form-label" for="comment">Comment:</label>
                <input type="text" id="comment" name="comment" value="<?php echo $userData['Comment']; ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="button">Save Profile</button>
            </div>
        </form>
    </div>
</body>
</html>
