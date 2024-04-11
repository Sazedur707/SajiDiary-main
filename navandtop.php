<?php
session_start();
// Retrieve the user ID from the session
$userId = $_SESSION['user_id'];
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


<style>
body{
    padding-top:66px;
 
}
   /* Floating navbar styles */
    .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 300px;
            background-color: darkblue;
            color: #ffffff;
            padding: 12px 12px;
            text-align: center;
            padding-top: 11px;
            border-radius: 0px 0px 20px 0px;
        }

        /* Go Top button styles */
        .go-top-button {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: blue;
            color: #ffffff;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .go-top-button:hover {
            background-color: darkblue;
        }
        .current_user_navbar{
            display: block;
             margin-bottom: 3px; 
            

        }
        .navbar a{
            margin:0px 7px;
            margin-top: 11px;
            text-decoration:none;
            color: white;
            border: 1px solid white;
            padding: 2px 10px;
            display: inline-block;
            width: 60px;

        }
        .navbar a:hover{
            color: darkblue;
            background-color: white;
        }
        
</style>

<div class="navwrapper">
<div class="navbar">
        <!-- Navbar content -->
        <strong class="current_user_navbar"><?php echo $current_user; ?></strong>
        <!-- Add a "Logout" link -->
        <a href="info.php">Info</a><a href="index.php">Home</a>
        <a href="?logout">Logout</a>

    </div>
</div>

    <button class="go-top-button" onclick="goTop()">Go Top</button>

    <script>
        // Function to scroll to the top of the page
        function goTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Show or hide the "Go Top" button based on scroll position
        window.addEventListener('scroll', function () {
            var goTopButton = document.querySelector('.go-top-button');
            if (window.scrollY > 100) {
                goTopButton.style.display = 'block';
            } else {
                goTopButton.style.display = 'none';
            }
        });
    </script>