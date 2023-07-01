<?php
session_start();

// Check if the user's role is set in the session
    if (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <link rel="stylesheet" type="text/css" href="content_style.css">

    <title>Manage Category</title>       


</head>

<body>
    <!-- Side navigation pane -->
    <?php include 'side_navigation.php'; ?>

    <!-- Content area -->
    <div class="content">
        <!-- Header area -->
        <?php include 'header.php'; ?>
        <!-- Add your content here -->
        <h1>Manage Category</h1>
        <div class="add-content-button">
            <a href="javascript:void(0)" onclick="showAddCategoryForm()">Add Category</a>
        </div>
        <div class="white-box">
            <h2>Categories</h2>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'db_connection.php';

                    // Retrieve the category data from the database
                    $stmt = $conn->prepare("SELECT CategoryID, CategoryName FROM category");
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Display the user data in a table
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['CategoryID'] . '</td>';
                        echo '<td>' . $row['CategoryName'] . '</td>';
                        echo '<td>
                        <a href="edit_category.php?CategoryName=' . $row['CategoryName'] . '" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                        <a href="delete_category.php?CategoryName=' . $row['CategoryName'] . '" class="link-dark" onclick="return confirm(\'Are you sure you want to delete this expert?\')"><i class="fa-solid fa-trash fs-5"></i></a>
                        </td>';
                        echo '</tr>';
                    }

                    // Close the database connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Add Category Form -->
        <div class="add-content-form" id="addCategoryForm">
            <div class="form-container">
                <span class="close-btn" onclick="hideAddCategoryForm()">&times;</span>
                <h3>Add Category</h3>
                <form action="add_category.php" method="post">
                    <label for="categoryName">Category Name:</label>
                    <input type="text" name="categoryName" id="categoryName" required>
                    <button type="submit">Add</button>
                </form>
            </div>
        </div>


        <script>
            function showAddCategoryForm() {
                document.getElementById('addCategoryForm').style.display = 'block';
            }

            function hideAddCategoryForm() {
                document.getElementById('addCategoryForm').style.display = 'none';
            }
        </script>
    </div>
</body>

</html>
