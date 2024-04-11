<?php
$en_pass = $_POST['password'];
$r_pass = "sazedurLogedout";
if ($en_pass == $r_pass) {
    // Assuming you have already established a database connection
    require_once 'db_config.php';

    // Retrieve the list of users
    $usersQuery = "SELECT * FROM users";
    $usersResult = mysqli_query($connection, $usersQuery);

    // Retrieve the list of entries
    $entriesQuery = "SELECT * FROM entries";
    $entriesResult = mysqli_query($connection, $entriesQuery);
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <?php include 'favicon.html'; ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Panel</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <h2>Welcome to the Admin Panel</h2>
            <h3>User List</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <!-- Add more user fields as needed -->
                </tr>
                <?php while ($user = mysqli_fetch_assoc($usersResult)): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <!-- Display more user fields as needed -->
                    </tr>
                <?php endwhile; ?>
            </table>

            <h3>Entry List</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Created At</th>
                </tr>
                <?php while ($entry = mysqli_fetch_assoc($entriesResult)): ?>
                    <tr>
                        <td><?php echo $entry['id']; ?></td>
                        <td><?php echo $entry['user_id']; ?></td>
                        <td><?php echo $entry['title']; ?></td>
                        <td><?php echo $entry['content']; ?></td>
                        <td><?php echo $entry['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <br>
            <a href="logout.php">Logout</a>
        </div>
    </body>
    </html>

    <?php
} else if (!isset($_POST['password'])) {
    header('Location: enter_admin.php');
} else {
    echo '<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"><b style="color:red;">Wrong password. Access denied.</b>';
}
?>
