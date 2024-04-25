<?php // Chatroom.php

/*Notes
This is a link to the other Chatrooms for Java, 
SQL, Python, and HTML/CSS*/

include "dbcon.php"; // Includes the database connection file

session_start(); // Start the session

// Checks if the user is logged in
if (!isset($_SESSION['loggedInUserID'])) {
    // Redirects to the login page if not logged in
    header('Location: Signin_up.php');
    exit();
}

// Gets the logged-in user's information
$loggedInUserID = $_SESSION['loggedInUserID'];
$loggedInUsername = $_SESSION['loggedInUsername'];

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts); // Creates a PDO instance for database connection
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode()); // Throws PDOException if connection fails
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="styles.css"><!-- Linking to a custom stylesheet named styles.css -->

    <title>Logic Lounge
    </title>

    <script src="jquery-3.7.1.min.js"></script>
    <!-- Linking the java script query in the files named jquery-3.7.1.min.js -->

</head>

<body class="ChatsBody">

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

    <div class="ChatPage">

    <header class="ChatHeader">
        <h1>Welcome to our Chat Space
            <span><?php echo $loggedInUsername; ?>!</span>
        </h1>
    </header>
    
    <div class="Chats">
        <h2>Navigate to your preferred chat below:</h2>
        <nav>
            <a href="java_chat.php">Java Chat</a>
            <a href="sql_chat.php">SQL Chat</a>
            <a href="python_chat.php">Python Chat</a>
            <a href="htmlcss_chat.php">HTML/CSS Chat</a>
        </nav>
    </div>

    <div class="ChatRules">
        <p>Please follow these rules:</p>
        <ul>
            <li>Be respectful and civil.</li>
            <li>No spamming or sharing of copyrighted material without permission.</li>
            <li>Do not share personal information about yourself or others.</li>
            <li>Keep discussions on topic, relevant to the chat's focus.</li>
        </ul>
    </div> 
</div>


</body>

<footer>
<p>&copy; 2024 Logic Lounge. All rights reserved.</p>

<div class="footerlinks">
<a href="terms.html">Terms of use</a>
<a href="privacy.html">Privacy Policy</a>
</div>
</footer>

</html>