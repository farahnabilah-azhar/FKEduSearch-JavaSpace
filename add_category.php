<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['categoryName'];
    
    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO category (CategoryName) VALUES (?)");
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();

    // Redirect to the manage_category.php page after adding the category
    header("Location: manage_category.php");
    exit();
}
?>
