<?php
session_start();
include 'db_connection.php';
include 'qr-code/phpqrcode/qrlib.php'; // Include the QR code library

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the user data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $academicArea = $_POST['academicArea'];
    $academicStatus = $_POST['academicStatus'];
    $certificateFilePath = __DIR__ . '/certificate/' . $row['UserCertificate'];

    $status = $_POST['status'];

    // Generate a unique filename for the certificate file
    $certificateFileName = uniqid() . '_' . $certificate['name'];

    // Move the uploaded certificate file to a directory
    $certificateFilePath = __DIR__ . '/certificate/' . $row['UserCertificate'];
    move_uploaded_file($certificate['tmp_name'], $certificateFilePath);

    // Generate the QR code using the certificate file path
    $qrCodeFilePath = 'qr-user/' . $certificateFileName . '.png';
    QRcode::png($certificateFilePath, $qrCodeFilePath, QR_ECLEVEL_L, 10);

    // Insert the user data into the database
    $stmt = $conn->prepare("INSERT INTO user (UserName, UserPassword, UserEmail, UserRole, CategoryID, UserAcademicStatus, UserCertificate, UserQRCode, UserStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $username, $password, $email, $role, $academicArea, $academicStatus, $certificateFileName, $qrCodeFilePath, $status);


    if ($stmt->execute()) {
        // User data inserted successfully
        header("Location: manage_user.php");
        exit();
      } else {
        echo "Error: Failed to retrieve UserID.";
      }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="content_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <title>Add User</title>

</head>

<body>
    <!-- Side navigation pane -->
    <?php include 'side_navigation.php'; ?>

    <div class="content">
        <div class="container">
            <!-- Add your content here -->

            <!-- Add User Form -->
            <div class="add-form" id="addUserForm">
                <!-- <span class="close-btn" onclick="hideAddUserForm()">&times;</span> -->
                <h3>Add User</h3>
                <form action="add_user.php" method="post" enctype="multipart/form-data">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email" required>
                    <label for="role">Role:</label>
                    <select name="role" id="role" required>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                        <option value="expertise">Expertise</option>
                    </select>
                    <label for="academicArea">Academic Area:</label>
                    <select name="academicArea" id="academicArea" required>
                    <?php
                    // Retrieve categories from the database
                    $categoryQuery = "SELECT CategoryID, CategoryName FROM category";
                    $categoryResult = $conn->query($categoryQuery);

                    // Display category options
                    while ($categoryRow = $categoryResult->fetch_assoc()) {
                        echo '<option value="' . $categoryRow['CategoryID'] . '">' . $categoryRow['CategoryName'] . '</option>';
                    }
                    ?>
                    </select>
                    <label for="academicStatus">Academic Status:</label>
                    <select name="academicStatus" id="academicStatus" required>
                        <option value="Diploma">Diploma</option>
                        <option value="Bachelor">Bachelor</option>
                        <option value="Master">Master</option>
                        <option value="PhD">PhD</option>
                    </select>
                    <label for="certificate">Academic Certificate (proof):</label>
                    <input type="file" name="certificate" id="certificate" accept=".pdf">
                    <label for="status">Status:</label>
                    <select name="status" id="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <button type="submit">Add</button>
                    <button type="button" onclick="cancelUpdate()">Cancel</button>
                </form>

                <script>
                    // Submit form on button click
                    document.querySelector("button[type='submit']").addEventListener("click", function (event) {
                        event.preventDefault();
                        var fileInput = document.getElementById("certificate");
                        if (fileInput.files.length > 0) {
                            document.querySelector("form").submit();
                        } else {
                            alert("Please select a certificate file.");
                        }
                    });

                    // Cancel button action
                    function cancelUpdate() {
                        window.location = "manage_user.php";
                    }
                </script>

            </div>
        </div>
    </div>
</body>

</html>
