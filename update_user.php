<?php
session_start();

include 'db_connection.php';
include 'qr-code/phpqrcode/qrlib.php'; // Include the QR code library

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the user ID and updated data from the form
  $userID = $_POST['userID'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];
  $role = $_POST['role'];
  $academicArea = $_POST['academicArea'];
  $academicStatus = $_POST['academicStatus'];
  $status = $_POST['status'];

  // Check if a new certificate file is uploaded
  if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === UPLOAD_ERR_OK) {
    // Generate a unique filename for the certificate file
    $certificateFileName = uniqid() . '_' . $_FILES['certificate']['name'];

    // Move the uploaded certificate file to a directory
    $certificateFilePath = 'certificate/' . $certificateFileName;
    move_uploaded_file($_FILES['certificate']['tmp_name'], $certificateFilePath);

    // Generate the QR code using the certificate file path
    $qrCodeFilePath = 'qr-user/' . $certificateFileName . '.png';
    QRcode::png($certificateFilePath, $qrCodeFilePath, QR_ECLEVEL_L, 10);
  } else {
    // No new certificate file uploaded, keep the existing value
    $certificateFilePath = $_POST['current_certificate'];
    $qrCodeFilePath = $_POST['current_qrcode'];
  }

  // Update the user data in the database
  $stmt = $conn->prepare("UPDATE user SET UserName = ?, UserPassword = ?, UserEmail = ?, UserRole = ?, CategoryID = ?, UserAcademicStatus = ?, UserCertificate = ?, UserQRCode = ?, UserStatus = ? WHERE UserID = ?");
  $stmt->bind_param("sssssssssi", $username, $password, $email, $role, $academicArea, $academicStatus, $certificateFilePath, $qrCodeFilePath, $status, $userID);

  if ($stmt->execute()) {
    // User data updated successfully
    echo "User data updated successfully.";
  } else {
    // Error occurred while updating user data
    echo "Error updating user data: " . $conn->error;
  }

  // Check if the update was successful
  if ($stmt->affected_rows > 0) {
    // Redirect to the manage_user.php page after updating the user
    header("Location: manage_user.php");
    exit();
  } else {
    echo "Error: Failed to update the user.";
  }

  // Close the database connection
  $stmt->close();
  $conn->close();
}
?>
