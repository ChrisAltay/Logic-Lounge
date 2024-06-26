<?php //Signin/up.php

/*
This page allows users to both sign in and sign up.
The reason for this is so users may be seen and known by their given usernames.
*/

// Including database connection file
include "dbcon.php";

// Starting session
session_start();

// Redirecting to logged-in notice page if already logged in
if (isset($_SESSION['loggedInUserID'])) {
    header('Location: Loggednotice.php'); // Change 'Welcome.php' to the page you want to redirect to
    exit();
}

try {
    // Creating PDO instance
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    // Handling connection error
    die("Connection failed: " . $e->getMessage());
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['createAccount'])) {
        // Create account section
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];

        // Validating username and password
        $usernamePattern = "/^[a-zA-Z0-9_-]{3,20}$/";
        $passwordPattern = "/^(?=.*[a-zA-Z\d!@#$%^&*()_+]).{10,}$/";

        // Validating username
        if (!preg_match($usernamePattern, $username)) {
            $createMessage = "<p class='error'>Invalid username. It must be 3 to 20 characters and can only contain letters, numbers, etc.</p>";
        } elseif (!preg_match($passwordPattern, $password)) {
            // Validating password
            $createMessage = "<p class='error'>Invalid password. It must be at least 10 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character.</p>";
        } else {
            try {
                // Inserting new account into database
                $query = "INSERT INTO UserAccount (Username, Password, Email) VALUES (:username, :password, :email)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
                $createMessage = "<p class='success'>Account created successfully!</p>";
            } catch (PDOException $e) {
                // Handling database error
                if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    $createMessage = "<p class='error'>Username or email is already taken. Please choose a different one.</p>";
                } else {
                    $createMessage = "<p class='error'>Error creating account: " . $e->getMessage() . "</p>";
                }
            }
        }
    } elseif (isset($_POST['login'])) {
        // Login section
        $loginUsername = $_POST["loginUsername"];
        $loginPassword = $_POST["loginPassword"];

        // Selecting user from database based on username and password
        $query = "SELECT * FROM UserAccount WHERE Username = :username AND Password = :password";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $loginUsername, PDO::PARAM_STR);
        $stmt->bindParam(':password', $loginPassword, PDO::PARAM_STR);

        try {
            // Executing query
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result) {
                // If user found, setting session variables and redirecting to chatroom page
                $_SESSION['loggedInUserID'] = $result['UserID'];
                $_SESSION['loggedInUsername'] = $result['Username'];
                header('Location: Chatroom.php');
                exit();
            } else {
                // If user not found, displaying error message
                $loginMessage = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            // Handling database error
            $loginMessage = "Error during login: " . $e->getMessage();
        }
    }
}

// Instead of printing the message directly, store it in a variable
$messageText = '';
if (isset($createMessage)) {
    $messageText = $createMessage;
}
if (isset($loginMessage)) {
    $messageText = $loginMessage;
}

if (!empty($messageText)) {
    $jsonMessage = json_encode($messageText);
    echo "<script type='text/javascript'>
            window.onload = function() {
                document.getElementById('messageContent').innerHTML = {$jsonMessage};
                document.getElementById('messageOverlay').style.display = 'flex';
            };
          </script>";
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="styles.css"><!-- Linking to a custom stylesheet named styles.css -->


    <!-- Script for showing prompt if user is not logged in -->
    <script>
        function showPrompt() {
            <?php
            // Checks if the user is logged in
            if (!isset($_SESSION['loggedInUserID'])) {
                // If not logged in, show the prompt
                echo 'alert("Please create an account or login to enter the Chat Room");';
            }
            ?>
        }
    </script>


    <title>Logic Lounge</title>
</head>

<body class="SISU">

    <!-- Navigation bar -->
    <header class="NV">

    <div class="logo-container">
        <div class="Logo">
        <img src="Logo.JPG" alt="Logic Lounge Logo" Title="Welcome to Logic Lounge">
        </div>
    </div>
    
    <nav>
        <a href="index.php">Home</a> <!-- Link to Homepage.php -->
        <a href="About_Us.php">About Us</a> <!-- Link to About_Us.php -->


        <?php
        // If the user is not logged in, display the SignUp/Login tab
        if (!isset($_SESSION['loggedInUserID'])) {
        echo '<a href="Signin_up.php">SignUp/Login</a>'; // Link to Signin_up.php
        }
        
        // Displays the Logout link if the user is logged in
        if (isset($_SESSION['loggedInUserID'])) { // Checks if a user is logged in
            echo '<a href="Chatroom.php">Chat Room</a>'; // Link to Chatroom.php
            echo '<a href="Sendpm.php">Send Private Message</a>'; // Link to Sendpm.php
            echo '<a href="Pms.php">Private Messages</a>'; // Link to Pms.php
            echo '<a href="Logout.php">Logout</a>'; // Link to Logout.php
        }
        ?>
    </nav>
    </header>

    <div class="SignInSignUp">

    <!-- Error Message Overlay -->
    <div id="messageOverlay" class="message-overlay" style="display: none;">
        <div class="message-box">
            <p id="messageContent"></p>
            <button class="ErrorBttn" onclick="closeMessage()">Close</button>
        </div>
    </div>

        <!-- Create Account Section -->
        <div class="LeftBox">
        <h1>Join the Community!</h1>
        <h2>Create Account</h2>

        <form method="post" action="">
            <!-- Username input -->
            <input type="text" name="username" placeholder="Username" required><br><br>
            <!-- Password input -->
            <input type="password" name="password" placeholder="Password" required><br><br>
            <!-- Email input -->
            <input type="email" name="email" placeholder="Email@example.com"required><br><br>

            <!-- Create account button -->
            <input class="CreateAccButton" type="submit" name="createAccount" value="Create Account">
        </form>
        </div>


        <!-- Login Section -->
        <div class="RightBox">
        <h2>Or Sign in!</h2>


        <form method="post" action="">
            <!-- Login username input -->
            <input type="text" name="loginUsername" placeholder="Username" required><br><br>

            <!-- Login password input -->
            <input type="password" name="loginPassword" placeholder="Password" required><br><br>

            <!-- Link to forgot password page -->
            <a href="forgot_pass.php">Forgot Password?</a><br><br>

            <!-- Login button -->
            <input class="LogInButton" type="submit" name="login" value="Login">
        </form>
        </div>

        </div>

<script>
function closeMessage() {
    document.getElementById('messageOverlay').style.display = 'none';
}
</script>


</body>

<footer>
<p>&copy; 2024 Logic Lounge. All rights reserved.</p>

<div class="footerlinks">
<a href="Terms.html">Terms of use</a>
<a href="Privacy.html">Privacy Policy</a>
</div>
</footer>

</html>