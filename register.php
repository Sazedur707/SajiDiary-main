<?php
require_once 'db_config.php';

$errors = []; // Array to store validation errors
$success = ""; // Variable to store success message

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate form data
    if (empty($username)) {
        $errors['username'] = "Username is required";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = "Passwords do not match";
    }

    // Check if there are any validation errors
    if (count($errors) === 0) {
        // Check if the username already exists in the database
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
            $errors['username'] = "Username already exists";
        } else {
            // Hash and salt the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the user into the database
            $insertQuery = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
            if (mysqli_query($connection, $insertQuery)) {
                $success = "Registration successful!";
            } else {
                $errors['database'] = "Error: " . mysqli_error($connection);
            }
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
    <title>Registration Form</title>
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
            font-size: 12px;
            margin-top: 5px;
        }

        .form-group .success-message {
            color: green;
            font-size: 12px;
            margin-top: 5px;
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
        .form-group .submit-button:hover{
            background-color: darkblue;
        } .title {
            text-align: center;
            margin-top: 20px;
        }

        h1 {
            color: #333333;
            font-size: 40px;
            
        }
        a{
            text-decoration: none;
        }
    </style>
</head>
<body>
<?php include 'title.php'; ?>
    <div class="container">
        <h2>Registration Form</h2>
        <?php if (!empty($success)): ?>
            <div class="form-group">
                <span class="success-message"><?php echo $success; ?><a href="login.php" style="text-decoration: none;">&nbsp;Click here</a> to login.</span>
            </div>
        <?php endif; ?>
        <form action="register.php" method="POST">
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
                <label for="confirm_password">Confirm Password:</label>
                <input class="inputs" type="password" id="confirm_password" name="confirm_password" required>
                <?php if (isset($errors['confirm_password'])): ?>
                    <span class="error-message"><?php echo $errors['confirm_password']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <input type="submit" class="submit-button" value="Register"><br>
                <span class="error-message">Please be sure to remember your password. Your password could not be changed again.</span>
                <?php if (isset($errors['database'])): ?>
                    <span class="error-message"><?php echo $errors['database']; ?></span>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>
