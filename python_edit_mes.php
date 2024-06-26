<?php
//htmlcss_edit_mes.php

/*Notes
For Python Chat
This allows the user to edit their own message.
*/

include "dbcon.php"; // Include the database connection file

session_start(); // Start the session

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedInUserID'])) {
    header('Location: Signin_up.php');
    exit();
}

try {
    $pdo = new PDO($attr, $user, $pass, $opts); // Create a PDO instance for database connection
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode()); // Throw PDOException if connection fails
}

$loggedInUserID = $_SESSION['loggedInUserID']; // Get the logged-in user's ID

// Redirect to chatroom if messageID is not provided or not a valid integer
if (!isset($_GET['messageID']) || !filter_var($_GET['messageID'], FILTER_VALIDATE_INT)) {
    header('Location: Chatroom.php');
    exit();
}

$messageID = $_GET['messageID']; // Get the messageID from the URL

// Prepare and execute query to select message from database
$selectQuery = "SELECT * FROM PythonChat WHERE MessageID = :messageID AND UserID = :loggedInUserID";

$selectStatement = $pdo->prepare($selectQuery);

$selectStatement->bindParam(':messageID', $messageID, PDO::PARAM_INT);
$selectStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);

try {
    $selectStatement->execute();
    $message = $selectStatement->fetch(PDO::FETCH_ASSOC); // Fetch the message data
} catch (PDOException $e) {
    echo "Error retrieving message: " . $e->getMessage(); // Display error message if retrieval fails
    exit();
}

// Redirect to chatroom if message does not exist or user is not authorized to edit it
if (!$message) {
    header('Location: Chatroom.php');
    exit();
}

// Update message if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateMessage'])) {
    $editedContent = $_POST["editedContent"]; // Get the edited content from the form

    // Prepare and execute query to update message in database
    $updateQuery = "UPDATE PythonChat SET Content = :editedContent WHERE MessageID = :messageID AND UserID = :loggedInUserID";
    $updateStatement = $pdo->prepare($updateQuery);

    $updateStatement->bindParam(':editedContent', $editedContent, PDO::PARAM_STR);
    $updateStatement->bindParam(':messageID', $messageID, PDO::PARAM_INT);
    $updateStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);

    try {
        $updateStatement->execute(); // Execute the update query
    } catch (PDOException $e) {
        echo "Error updating message: " . $e->getMessage(); // Display error message if update fails
    }

    header('Location: python_chat.php'); // Redirect back to the chatroom after updating
    exit();
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

<body class="editbody">
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

        <div class="editmes">
        <h1>Edit Message</h1>
    

    

        <form method="post" action="">
            <textarea name="editedContent" rows="3" required><?php echo $message['Content']; ?></textarea><br>
            <!-- Text area for entering the message content -->

            <input class="mesbutton" type="submit" name="updateMessage" value="Update Message">
            <!-- Submit button for updating the message -->
        </form>
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