<?php
require_once 'db_config.php';

$errors = []; // Array to store validation errors

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate form data
    if (empty($username)) {
        $errors['username'] = "Username is required";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    // Check if there are any validation errors
    if (count($errors) === 0) {
        // Fetch the user from the database
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Start a new session and set session variables
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the diary page (replace 'diary.php' with your actual diary page)
                header('Location: index.php');
                exit;
            } else {
                $errors['password'] = "Invalid password!";
            }
        } else {
            $errors['username'] = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include 'favicon.html'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        /* Add some basic CSS styles to make the form visually appealing */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .form-group .inputs {
            width: 94%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group .error-message {
            color: red;
        }

        .form-group .submit-button {
            background-color: blue;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .form-group .submit-button:hover {
            background-color: darkblue;
        }

        .title {
            text-align: center;
            margin-top: 20px;
        }

        h1 {
            color: #333333;
            font-size: 40px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body><?php include 'title.php'; ?>
<div class="container">
    <h2>Login Form</h2>
    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input class="inputs" type="text" id="username" name="username" required>
            <?php if (isset($errors['username'])): ?>
                <span class="error-message"><?php echo $errors['username']; ?></span>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input class="inputs" type="password" id="password" name="password" required>
            <?php if (isset($errors['password'])): ?>
                <span class="error-message"><?php echo $errors['password']; ?></span>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <input type="submit" class="submit-button" value="Login">
        </div>
    </form>
</div>
</body>
</html>
