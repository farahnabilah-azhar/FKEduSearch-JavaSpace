<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="content_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>User Post</title>
</head>

<body>
    <!-- Side navigation pane -->
    <?php include 'includes/menu.php'; ?>

    <div class="content">
        <!-- Header area -->
        <?php include 'includes/header.php'; ?>
        <!-- Add your content here -->
        <h1>My Post Graph</h1>

       
        <?php include 'chart_library.php'; ?>

        <?php include 'includes/footer.php'; ?>
    </div>
</body>

</html>
