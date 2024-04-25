<?php 
// Loggednotice.php

/*Notes
This page allows informs the user (if logged in) that they are already logged in.
It's triggered if the logged in user clicks on "Login.php".*/

include "dbcon.php"; // Includes the database connection file


session_start(); // Starts a session



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

    <title>Logged in Notice</title>
</head>

<body class="alreadyloggedin">
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

        <div class="LoggedInNotif">
    <header>
        <h7>Its quiet, very quiet, no one to talk to..OH Hello!</h7>
        <h1>You are already in the Chat Space! Roaming around I see (^_^)/ </h1>
    </header>

    <main>

        <p>
            Feel free to log out, or choose another option from the tabs above.
        <p>

    </main>

    </div>

    <footer>
<p>&copy; 2024 Logic Lounge. All rights reserved.</p>

<div class="footerlinks">
<a href="terms.html">Terms of use</a>
<a href="privacy.html">Privacy Policy</a>
</div>
</footer>

</body>

</html>