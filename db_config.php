<?php
$servername = "localhost";
$username = "id20994902_sazedur";
$password = "Sazedur937943@";
$database = "id20994902_diary_db";

// Create the database connection
$connection = mysqli_connect($servername, $username, $password, $database);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
