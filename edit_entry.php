<?php
require_once 'db_config.php';
session_start();

// Check if the user is not logged in, redirect to the login page (replace 'login.php' with your actual login page)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Assuming you have already established a database connection

// Retrieve the user ID from the session
$userId = $_SESSION['user_id'];

// Check if the entry ID is provided in the query parameter
if (isset($_GET['id'])) {
    $entryId = $_GET['id'];

    // Retrieve the entry from the database using the entry ID and the user ID for authorization
    $selectQuery = "SELECT * FROM entries WHERE id = '$entryId' AND user_id = '$userId'";
    $result = mysqli_query($connection, $selectQuery);

    // Check if the entry exists and belongs to the logged-in user
    if (mysqli_num_rows($result) === 1) {
        $entry = mysqli_fetch_assoc($result);
        $entryTitle = $entry['title'];
        $entryContent = $entry['content'];
    } else {
        // Entry not found or does not belong to the user, redirect to the diary page (replace 'index.php' with your actual diary page)
        header('Location: index.php');
        exit;
    }
} else {
    // Entry ID not provided, redirect to the diary page (replace 'index.php' with your actual diary page)
    header('Location: index.php');
    exit;
}

// Check if the form for updating the entry is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Update the entry in the database
    $updateQuery = "UPDATE entries SET title = '$title', content = '$content' WHERE id = '$entryId' AND user_id = '$userId'";
    mysqli_query($connection, $updateQuery);

    // Redirect to the diary page after updating the entry (replace 'index.php' with your actual diary page)
    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'favicon.html'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Diary Entry</title>
    <style>
        /* Add some basic CSS styles to make the page visually appealing */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            background-color: #f1eded;
  color: #333333;
  border: none;
  padding: 10px;
  width: 100%;
  box-sizing: border-box;
  border: 1px solid darkblue;
        }

        .form-group .submit-button {
            background-color: blue;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-bottom: 50px;
        }
        .form-group .submit-button:hover{
            background-color: darkblue;
        }
        textarea{
            height: 400px
        }
    </style>
</head>
<body>
    <?php include 'title.php'; ?>
    <div class="container">
        <h2>Edit Diary Entry</h2>

        <!-- Form for editing the diary entry -->
        <form action="edit_entry.php?id=<?php echo $entryId; ?>" method="POST">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $entryTitle; ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" required><?php echo $entryContent; ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="submit-button" value="Update Entry">
            </div>
        </form>
    </div>
    <?php include 'navandtop.php' ?>
</body>
</html>
