<?php // index.php

/*Notes
This is the homepage, but as an index.php 
Without it, errors would occur.
*/

include "dbcon.php";

// Creates connection
session_start();

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

    <!--Baloo font for homepage bubbly header-->
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400&display=swap" rel="stylesheet">


    <title>Logic Lounge</title>
</head>


<body id=Main>

 <!-- Navigation bar -->
    <header class="NV">

    <div class="logo-container">
        <div class="Logo">
        <img src="Logo.JPG" alt="Logic Lounge Logo" Title="Welcome to Logic Lounge">
        </div>
    </div>

     <!-- Hamburger menu button -->
     <button class="hamburger-menu" onclick="toggleMenu()">
            &#9776;
    </button>

    <div class="nav-links">
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
    </div>

    </header>

    <div class="HomeContent">
            <h1 class="MainHead">Ele<span class="tilt">v</span>ate Yo<span class="tilt">u</span>r Co<span class="tilt">d</span>e</h1>
            
            <p class="HomePara">
                Connect&nbsp; Collaborate&nbsp; Innovate 
            </p>

            <div class="buttons">
                <a href="Signin_up.php" class="button">Sign In</a>  
                <a href="Signin_up.php" class="button">Sign Up</a>
            </div>

    </div>

    <div class="bubbles">
            <img src="bubble.png" alt="bubble">
            <img src="bubble.png" alt="bubble">
            <img src="bubble.png" alt="bubble">
            <img src="bubble.png" alt="bubble">
            <img src="bubble.png" alt="bubble">
            <img src="bubble.png" alt="bubble">
            <img src="bubble.png" alt="bubble">
            <img src="bubble.png" alt="bubble">
        </div>

<script>
  function toggleMenu() {
    var navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
  }
</script>

</body>



<footer>
<p>&copy; 2024 Logic Lounge. All rights reserved.</p>

<div class="footerlinks">
<a href="terms.html">Terms of use</a>
<a href="privacy.html">Privacy Policy</a>
</div>
</footer>


</html>