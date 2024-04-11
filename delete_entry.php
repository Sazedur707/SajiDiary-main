<?php
session_start();

// Check if the user is not logged in, redirect to the login page (replace 'login.php' with your actual login page)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Assuming you have already established a database connection
require_once 'db_config.php';

// Check if the entry ID is provided in the URL
if (isset($_GET['id'])) {
    $entryId = $_GET['id'];

    // Retrieve the user ID from the session
    $userId = $_SESSION['user_id'];

    // Check if the entry belongs to the logged-in user
    $selectQuery = "SELECT * FROM entries WHERE id = '$entryId' AND user_id = '$userId'";
    $result = mysqli_query($connection, $selectQuery);

    if (mysqli_num_rows($result) === 1) {
        // Entry belongs to the logged-in user, proceed with deletion

        // Create the delete query
        $deleteQuery = "DELETE FROM entries WHERE id = '$entryId'";
        mysqli_query($connection, $deleteQuery);

        // Redirect back to the diary page
        header('Location: index.php');
        exit;
    } else {
        // Entry doesn't exist or doesn't belong to the logged-in user
        echo 'Invalid entry or permission denied.';
    }
} else {
    // Entry ID is not provided in the URL
    echo 'Invalid entry ID.';
}
