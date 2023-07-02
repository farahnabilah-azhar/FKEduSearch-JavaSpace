<?php
$link = mysqli_connect("localhost", "root") or die(mysqli_connect_error());
mysqli_select_db($link, "fkedusearch") or die(mysqli_error());

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $expertID = $_POST['expert_id'];
    $postAnswerID = $_POST['post_answerID'];
    $ratingValue = $_POST['rating_value'];
    $ratingDate = date('Y-m-d'); // Current date

    // Insert the rating into the database
    $insertRatingQuery = "INSERT INTO rating (expert_ID, post_answerID, rating_value, rating_Date)
                          VALUES ('$expertID', '$postAnswerID', '$ratingValue', '$ratingDate')";
    mysqli_query($link, $insertRatingQuery) or die(mysqli_error($link));

    // Redirect back to the page where the rating was made
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
