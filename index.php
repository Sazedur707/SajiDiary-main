<?php
session_start();

// Check if the user is not logged in, redirect to the login page (replace 'login.php' with your actual login page)
if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

// Assuming you have already established a database connection
require_once 'db_config.php';

// Retrieve the user ID from the session
$userId = $_SESSION['user_id'];


// Check if the form for creating new entries is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Get the current date and time
    $datetime = date('Y-m-d H:i:s');

    // Insert the new entry into the database
    $insertQuery = "INSERT INTO entries (user_id, title, content, created_at) VALUES ('$userId', '$title', '$content', '$datetime')";
    mysqli_query($connection, $insertQuery);
}

// Retrieve the filter option
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Retrieve the search keyword
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

// Set the sort order for the query
$sortQuery = ($sortOrder === 'newest') ? 'ORDER BY created_at DESC' : 'ORDER BY created_at ASC';

// Set the search condition for the query
$searchCondition = '';
if (!empty($searchKeyword)) {
    $searchCondition = "AND (title LIKE '%$searchKeyword%' OR content LIKE '%$searchKeyword%')";
}

// Retrieve the existing diary entries for the logged-in user with the applied filters
$selectQuery = "SELECT * FROM entries WHERE user_id = '$userId' $searchCondition $sortQuery";
$result = mysqli_query($connection, $selectQuery);
// for displaying current username 
$usersQuery = "SELECT * FROM users";
$usersResult = mysqli_query($connection, $usersQuery);
while ($user = mysqli_fetch_assoc($usersResult)){
  $id = $user['id'];
  if($userId == $id){
      $current_user = $user['username'];
  }
}
// Logout logic
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: home.php');
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'favicon.html'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/handwriting" rel="stylesheet">   
    <link href="https://fonts.cdnfonts.com/css/kg-first-time-in-forever" rel="stylesheet">
                
    <title>Saji Diary</title>
    <style>
    /* Google font */
@import url('https://fonts.googleapis.com/css?family=Dancing+Script');
                
    body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 10px;
  background-color: #f9f9f9;
  padding-top:62px!important;
}
.centerFilter{
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid darkblue;
    padding: 10px;
}

.container {
  max-width: 800px;
  margin: 0 auto;
  background-color: #ffffff;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}
.blueHover:hover{
    background-color: darkblue;
}

h2 {
  text-align: center;
  color: #333333;
  margin-top: 0;
  padding-top: 10px;
  border-top: 3px solid darkblue;
}

h3 {
  color: #333333;
  margin-bottom: 10px;
}

.form-popup label {
  color: #333333;
}

.form-popup input,
.form-popup textarea {
  background-color: ghostwhite;
  color: #333333;
  border: none;
  padding: 10px;
  width: 100%;
  box-sizing: border-box;
  border: 1px solid darkblue;
}
.form-group .inputkey{
    background-color: ghostwhite;
  color: #333333;
  border: 1px solid darkblue;
   
  padding: 9px;
  /* width: 100%; */
  box-sizing: border-box;
}

.basic-button{
    background-color: blue;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
}
.current_user{
  border-radius: 40px;
  padding: 10px 20px;
  cursor: pointer;
  border: 1px solid darkblue;
  width: 84%;
  background-color: ghostwhite;
  box-shadow: 0px 0px 16px lightgray inset;

}
.current_user_button{
  width: 15%;
}


.entry {
  background-color: ghostwhite;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  border: 1px solid darkblue;
}

.entry h3 {
  color: #333333;
  font-size: 24px;
  margin: 0 0 18px;
}

.entry p {
  color: black;
  margin: 0 0 26px;
}

.action-links a {
  color: blue;
  text-decoration: none;
  margin-right: 10px;
}
.action-links a:hover{
    color: darkblue;
}

.filter label {
  color: #333333;
  font-weight: bold;
  margin-right: 10px;
}

.filter select {
  background-color: ghostwhite;
  color: #333333;
  border: 1px solid darkblue;
  padding: 5px 7px;
   
}
/* Pop-up form styles */
.form-popup {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

.form-container {
    max-width: 800px;
    margin: 50px auto;
    background-color: #ffffff;
    padding: 20px;
}
.form-container textarea{
  height: 340px;
}


@media (max-width: 600px) {
  .container {
    padding: 10px;
  }
  
  .entry {
    padding: 10px;
    margin-bottom: 10px;
  }
  
  .entry h3 {
    font-size: 24px;
  }
}
@media (max-width: 800px) {
  .current_user{
    width: 68%;
    font-size:13px;
  }
  .current_user_button{
    width: 29%;
    font-size:12px;
    padding: 10px 18px;
  }
}
@media (max-width: 400px){
  .current_user_button{
    width: 30%;
  }
}

    </style>
</head>

<body><?php include 'line_bg.php'; include 'title.php'; ?>
    <div class="container">
        <h2>My Diary</h2>
        <div style="text-align: center;">
          
        <!-- Form for creating new diary entries -->
        <button id="openFormButton" class="current_user" onclick="openForm()">What are you thinking, <?php echo $current_user; ?>?</button>
        
        <!-- Add button to open the form -->
        <button id="openFormButton" class=" basic-button blueHover current_user_button" onclick="openForm()">Add Entry?</button>

        </div>

        <!-- Form for creating new diary entries (pop-up) -->
        <div class="form-popup" id="entryForm">
            <form action="index.php" method="POST" class="form-container ">
                <h3>Create New Diary Entry</h3><br>
                
                <input type="text" id="title" name="title" required placeholder="Title"><br><br>
                
                <textarea id="content" name="content" required placeholder="Entry"></textarea><br><br>
                <button type="submit" class="submit-button basic-button blueHover">Save Entry</button>
                <button type="button" class="cancel-button basic-button blueHover" onclick="closeForm()">Cancel</button>
                <br>
                <span style="font-size:13px;">Please don't use this symbol: (') <br>Otherwise, your entry will not be saved.</span>
            </form>
        </div>
<br>
        <div class="centerFilter">
        <div>
              <!-- Filter options -->
        <div class="filter">
            <label for="sort"><b>Sort by:</b></label>
            <select id="sort" name="sort" onchange="filterEntries(this.value)">
                <option value="newest" <?php if ($sortOrder === 'newest') echo 'selected'; ?>>Newest First</option>
                <option value="oldest" <?php if ($sortOrder === 'oldest') echo 'selected'; ?>>Oldest First</option>
            </select>
        </div>
<br>
        <!-- Search form -->
        <form action="index.php" method="GET">
            <div class="form-group">
                <label for="search"><b></b></label>
                <input class="inputkey" type="text" id="search" name="search" placeholder="Search by Keywords" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <input type="submit" class="submit-button basic-button blueHover" value="Go">
            </div>
        </form>
        </div>
        </div>

        <!-- Display existing diary entries -->
        <h3>My Entries</h3>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="entry"><div id="paper"><div id="pattern"><div id="content_in_paper">
                <h3><?php echo $row['title']; ?></h3>
                <details>
                  <summary>
                    Read Now
                  </summary>
                  <p>
                    <?php echo $row["content"]; ?>
                  </p>
                </details>
                <p style="color: gray;font-size:13px; text-align: right;">Created at: <?php echo $row['created_at']; ?></p>
                <div class="action-links" style="text-align: right;">
                    <a href="edit_entry.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="delete_entry.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this entry?')"><span style="color: red;">Delete</span></a>
                </div>
            </div></div></div></div>
        <?php endwhile; ?>

        <script>
            function filterEntries(sortOrder) {
                window.location.href = 'index.php?sort=' + sortOrder;
            }
            function openForm() {
    document.getElementById("entryForm").style.display = "block";
}

function closeForm() {
    document.getElementById("entryForm").style.display = "none";
}

        </script>
    

    
    <?php include 'navandtop.php'; ?>
</body>
</html>

<style>
    #paper {
  max-width: 600px;
  position: relative;
  margin: 20px auto;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: white;
  box-shadow: 0px 0px 5px 0px #888;
}

/* styling red vertical line */
#paper::before {
  content: '';
  width: 2px;
  height: 100%;
  position: absolute;
  top: 0;
  left: 40px;
  background-color: rgba(255,0,0,0.6);
}

/* styling blue horizontal lines */
#pattern {
  height: 100%;
  background-image: repeating-linear-gradient(white 0px, white 24px, teal 25px);
}

/* styling text content */
#content_in_paper {
  padding-top: 6px;
  padding-left: 56px;
  padding-right: 16px;
  line-height: 25px;
  font-size: 19px;
  letter-spacing: 1px;
  word-spacing: 5px;
  /*font-family: 'Dancing Script', cursive;*/
  /*font-family: 'Handwriting', sans-serif;*/
 font-family: 'KG First Time In Forever', sans-serif;
                                                
                                                
}
</style>
