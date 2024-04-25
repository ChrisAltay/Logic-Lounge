<?php // About Us.php

/*Notes
This is an about us page. */

include "dbcon.php"; // Includes the database connection file


session_start(); // Starts a session


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

    <title>Logic Lounge</title>
</head>


<body class="AboutBody">

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

        <div class="AboutUs">

        <h1>About Us</h1>
        <h2>About the Site</h2>
        <p>Logic Lounge is an open-source chat platform where programmers worldwide gather to network, collaborate, and solve coding challenges. 
            Our platform nurtures a thriving community of developers eager to share their knowledge and learn from others. 
            Join Logic Lounge today to be part of our global network and elevate your programming skills!
        </p>
    
        <div class="Container">

        <div class="ChrisSection">
            <div class="ChrisPhoto">
                <h2 class="AboutH2">About Our Team</h2>
                <img class="img1" src="Chris.JPG" alt="photo of chris" title="Chris | Project Manager">
                <p>Christopher Paredes</p>
            </div>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

            <div class="ChrisInfo">
                <p>Front-End Web Developer | Web Designer</p> 

                <div class="centerlinks">
                <a class="link" href="https://github.com/ChrisAltay"><i class="fab fa-github">GitHub</i></a>
                <a class="linkP" href="https://chrisaltay.netlify.app/">Portfolio</a>
                </div>
            </div>
        </div>

        <div class="SolomonSection">
            <div class="SolomonPhoto">
                <img class="img2" src="Solomon.jpeg" alt="photo of solomon" title="Solomon | Project Supervisor">
                <p>Solomon Thomas</p>
            </div>

            <div class="SolomonInfo">
                <p>Back-End Web Developer</p>
                <div class="centerlinks"> 
                <a class="link" href="https://github.com/ThomSolo"><i class="fab fa-github">GitHub</i></a>
                <a class="linkL" href="https://www.linkedin.com/in/solomon-thomas-982548252/">LinkedIn</a>
            </div>
            </div>
        </div>

        <div class="ErickSection">
            <div class="ErickPhoto">
                <img class="img3" src="Erick.jpeg" alt="photo of erick" title="Erick | Project Memeber">
                <p>Erick Chicas</p>   
            </div>

            <div class="ErickInfo">
                <p>Back-End Support</p>
                <div class="centerlinks"> 
                <a class="linkL" href="https://www.linkedin.com/me?trk=p_mwlite_feed_updates-secondary_nav">LinkedIn</a>
            </div>
            </div>
        </div>

        <div class="AndySection">
            <div class="AndyPhoto">
                <img class="img4" src="Andy.jpg" alt="photo of andy" title="Andy | Project Member">
                <p>Andy Allaico</p>
            </div>

            <div class="AndyInfo">
                <p>Back-End Support</p> 
                <div class="centerlinks">
                <a class="link" href="https://github.com/Andierrrr"><i class="fab fa-github">GitHub</i></a>
            </div>
            </div>
        </div>

        <div class="BallaSection">
            <div class="BallaPhoto">
                <img class="img5" src="Balla.jPG" alt="photo of balla" title="Balla | Project Member">
                <p>Balla Diaite</p>
            </div>

            <div class="BallaInfo">
                <p>Front-End Support</p> 
                <div class="centerlinks">
                <a class="linkL" href="https://www.linkedin.com/in/balla-diaite-b73292230">LinkedIn</a>
            </div>
            </div>
        </div>



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