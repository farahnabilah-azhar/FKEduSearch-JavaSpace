<!-- Side navigation pane -->
<div class="sidenav">
    <div class="logo-container">
        <img src="fk.png" alt="Logo" class="logo">
        <span class="logo-text">FK-EduSearch</span>
    </div>
    
    <!-- Common navigation links for all users -->
    
    <?php
    // Check if the user's role is set in the session
    if (isset($_SESSION['role'])) {
        $userRole = $_SESSION['role'];
    }

    // Check the user role to determine additional navigation links
    if (isset($userRole) && $userRole == 'admin') {
        // Admin-specific navigation links
        echo '
            <a href="admin.php">Home</a>
            <a href="manage_user.php">Manage User</a>
            <a href="manage_post.php">Manage Post</a>
            <a href="manage_expert.php">Manage Expert</a>
            <a href="manage_research.php">Manage Research</a>
            <a href="manage_category.php">Manage Category</a>
            <a href="manage_complaint.php">Manage Complaint</a>
            <!-- Add more admin-specific links if needed -->
        ';
    } elseif (isset($userRole) && $userRole == 'user') {
        echo '
            <a href="homepage.php">Home</a>
            <a href="manage_post.php">Manage Post</a>
            <a href="manage_complaint.php">Manage Complaint</a>
        ';
        // No additional links for users
    } elseif (isset($userRole) && $userRole == 'expertise') {
        // Expert-specific navigation links
        echo '
            <a href="expertise.php">Home</a>
            <a href="manage_post.php">Manage Post</a>
            <a href="manage_expert.php">Manage Expert</a>
            <a href="manage_research.php">Manage Research</a>
            <a href="manage_complaint.php">Manage Complaint</a>
            <!-- Add more expert-specific links if needed -->
        ';
    }
    ?>
    
    <!-- Add more common navigation links if needed -->
</div>
