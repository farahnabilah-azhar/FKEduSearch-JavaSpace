<?php
$page = 'homepage';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <link rel="stylesheet" href="path/to/font-awesome/css/all.min.css">
    <style>
        /* CSS styles for the homepage */
        body {
            font-family: Arial, sans-serif;
        }

        .post {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
        }

        .post h3 {
            margin-bottom: 0;
        }

        .post p {
            margin-bottom: 10px;
        }

        .post .icon {
            display: inline-block;
            margin-right: 5px;
        }

        .post .icon.like {
            color: #000080;
        }

        .post .icon.comment {
            color: #000080;
        }

        .post a {
            text-decoration: none;
            color: #337ab7;
            margin-right: 10px;
        }

        .post a:hover {
            text-decoration: underline;
        }

        .add-post {
            margin-bottom: 20px;
        }

        .add-post a {
            display: inline-block;
            padding: 5px;
            background-color: #337ab7;
            color: #fff;
            text-decoration: none;
        }

        /* CSS for side navigation pane */
        .sidenav {
            height: 100%;
            width: 200px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #f1f1f1;
            overflow-x: hidden;
            padding-top: 20px;
        }

        .sidenav a {
            padding: 6px 8px 6px 16px;
            text-decoration: none;
            font-size: 16px;
            color: #333;
            display: block;
        }

        .sidenav a:hover {
            background-color: #ddd;
        }

        .content {
            margin-left: 200px;
            padding: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            padding: 10px;
        }

        .logo {
            width: 40px;
            height: 40px;
            padding-right: 10px;
        }

        .logo-text {
            font-size: 16px;
            font-weight: bold;
        }

        .header {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            background-color: #f1f1f1;
        }

        .header a {
            margin-left: 10px;
            text-decoration: none;
            color: #333;
        }

        .like-link {
            text-align: center;
            margin-bottom: 10px;
        }

        body {
            padding-top: 100px;
        }

        .post {
            width: 30%;
            margin: 10px auto;
            border: 1px solid #cbcbcb;
            padding: 5px 10px 0px 10px;
        }

        .like,
        .unlike,
        .likes_count {
            color: blue;
        }

        .hide {
            display: none;
        }

        .fa-thumbs-up,
        .fa-thumbs-o-up {
            transform: rotateY(-180deg);
            font-size: 1.3em;
        }
    </style>
</head>
<body>
<?php include('side_navigation.php'); ?>
<div class="sidenav">
    <div class="logo-container">
        <img src="includes/FK_logo.png" alt="Logo" class="logo">
        <span class="logo-text">FK-EduSearch</span>
    </div>
</div>

<div class="content">
    <div class="header">
        <a href="logout.php">Logout</a>
    </div>
    <h1>Discussion Board</h1>
    <div id="posts">
        <div class="add-post">
            <a href="add_post.php"><i class="fas fa-plus"></i> Add Post</a>
        </div>

        <?php
        // Connect to the database
        $link = mysqli_connect("localhost", "root", "", "fkedusearch");

        if (!$link) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        if (isset($_POST['liked'])) {
            $post_ID = $_POST['post_ID'];
            $result = mysqli_query($link, "SELECT * FROM likes WHERE post_ID=$post_ID");

            if ($result) {
                $row = mysqli_fetch_array($result);
                $n = $row['likes'];

                mysqli_query($link, "INSERT INTO likes (user_ID, post_ID) VALUES (1, $post_ID)");
                mysqli_query($link, "UPDATE userpost SET likes=$n+1 WHERE PostID=$post_ID");

                echo $n + 1;
                exit();
            } else {
                die("Query execution failed: " . mysqli_error($link));
            }
        }

        if (isset($_POST['unliked'])) {
            $post_ID = $_POST['post_ID'];
            $result = mysqli_query($link, "SELECT * FROM userpost WHERE PostID=$post_ID");

            if ($result) {
                $row = mysqli_fetch_array($result);
                $n = $row['likes'];

                mysqli_query($link, "DELETE FROM likes WHERE post_ID=$post_ID AND user_ID=1");
                mysqli_query($link, "UPDATE userpost SET likes=$n-1 WHERE PostID=$post_ID");

                echo $n - 1;
                exit();
            } else {
                die("Query execution failed: " . mysqli_error($link));
            }
        }

        // Retrieve posts from the database
        $posts = mysqli_query($link, "SELECT * FROM userpost");

        if (!$posts) {
            die("Query execution failed: " . mysqli_error($link));
        }
        ?>

        <!-- Display posts retrieved from the database -->
        <?php while ($row = mysqli_fetch_array($posts)) { ?>
            <div class="post">
                <?php echo $row['text']; ?>
                <div style="padding: 2px; margin-top: 5px;">
                    <?php
                    // Determine if the user has already liked this post
                    $results = mysqli_query($link, "SELECT * FROM likes WHERE user_ID=1 AND post_ID=" . $row['PostID']);

                    if (mysqli_num_rows($results) == 1): ?>
                        <!-- User already likes the post -->
                        <span class="unlike fa fa-thumbs-up" data-post_ID="<?php echo $row['PostID']; ?>"></span>
                        <span class="like hide fa fa-thumbs-o-up" data-post_ID="<?php echo $row['PostID']; ?>"></span>
                    <?php else: ?>
                        <!-- User has not yet liked the post -->
                        <span class="like fa fa-thumbs-o-up" data-post_ID="<?php echo $row['PostID']; ?>"></span>
                        <span class="unlike hide fa fa-thumbs-up" data-post_ID="<?php echo $row['PostID']; ?>"></span>
                    <?php endif ?>

                    <span class="likes_count"><?php echo $row['likes']; ?> likes</span>
                </div>
            </div>
        <?php } ?>

        <!-- Add jQuery to the page -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function () {
                // When the user clicks on like
                $('.like').on('click', function () {
                    var post_ID = $(this).data('post_ID');
                    $post = $(this);

                    $.ajax({
                        url: 'homepage.php',
                        type: 'post',
                        data: {
                            'liked': 1,
                            'post_ID': post_ID
                        },
                        success: function (response) {
                            $post.parent().find('span.likes_count').text(response + " likes");
                            $post.addClass('hide');
                            $post.siblings().removeClass('hide');
                        }
                    });
                });

                // When the user clicks on unlike
                $('.unlike').on('click', function () {
                    var post_ID = $(this).data('post_ID');
                    $post = $(this);

                    $.ajax({
                        url: 'homepage.php',
                        type: 'post',
                        data: {
                            'unliked': 1,
                            'post_ID': post_ID
                        },
                        success: function (response) {
                            $post.parent().find('span.likes_count').text(response + " likes");
                            $post.addClass('hide');
                            $post.siblings().removeClass('hide');
                        }
                    });
                });
            });
        </script>
    </div>
</div>
</body>
</html>
