<?php
// sql_chat.php

/*Notes
This is a public chat room for (SQL).
The user can comment, edit, and/or delete their own messages.
*/

include "dbcon.php"; // Includes the database connection file

session_start(); // Start the session

if (!isset($_SESSION['loggedInUserID'])) { // Checks if the user is logged in
    header('Location: Signin_up.php'); // Redirects to the login page if not logged in
    exit();
}

$loggedInUserID = $_SESSION['loggedInUserID']; // Gets the logged-in user's ID
$loggedInUsername = $_SESSION['loggedInUsername']; // Gets the logged-in user's username

try {
    $pdo = new PDO($attr, $user, $pass, $opts); // Creates a PDO instance for database connection
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode()); // Throws PDOException if connection fails
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendMessage'])) { // Checks if the form is submitted and the send message button is clicked

    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_token']) { // Verify token
        header('Location: sql_chat.php');
        exit();
    }

    $messageContent = $_POST["messageContent"]; // Retrieves the message content from the form
    $isPrivate = isset($_POST["isPrivate"]) ? 1 : 0; // Checks if the message is private or not

    // Inserts message into the SQLChat table
    $insertQuery = "INSERT INTO SQLChat (UserID, Content, IsPrivate) VALUES (:userID, :content, :isPrivate)";
    $insertStatement = $pdo->prepare($insertQuery);

    // Binds parameters
    $insertStatement->bindParam(':userID', $loggedInUserID, PDO::PARAM_INT);
    $insertStatement->bindParam(':content', $messageContent, PDO::PARAM_STR);
    $insertStatement->bindParam(':isPrivate', $isPrivate, PDO::PARAM_BOOL);

    try {
        $insertStatement->execute(); // Executes the insert query
        unset($_SESSION['csrf_token']); // Remove token to prevent resubmission
    } catch (PDOException $e) {
        echo "Error sending message: " . $e->getMessage(); // Displays an error message if insertion fails
    }
}

// Generate CSRF token
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

// Select the 50 most recent messages from the SQLChat table
$selectQuery = "SELECT m.MessageID, m.Content, m.IsPrivate, m.Timestamp, u.Username, m.UserID
                FROM SQLChat m
                LEFT JOIN UserAccount u ON m.UserID = u.UserID
                ORDER BY m.Timestamp DESC LIMIT 50";

$statement = $pdo->prepare($selectQuery); // Prepares the select query
$statement->execute(); // Executes the select query
$messages = $statement->fetchAll(PDO::FETCH_ASSOC); // Fetches the messages from the database
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="styles.css"><!-- Linking to a custom stylesheet named styles.css -->

    <title>Logic Lounge
    </title> <!-- For displaying the user's username by the Title -->
</head>

<body class="SqlBody">
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

        <div class="SQLChat">
        <h1>SQL Chat</h1> 
        </div>

        <?php
        foreach ($messages as $message) {
        $messageClass = '';

        if (isset($message['UserID'])) {
            $messageClass = ($message['UserID'] == $loggedInUserID) ? 'user-message' : 'other-message';
        }

        echo "<div class='chat-message {$messageClass}'>";
        echo "<strong>{$message['Username']}</strong>: {$message['Content']}";
        

        if (isset($message['UserID']) && $message['UserID'] == $loggedInUserID) {
            echo " <a href='sql_edit_mes.php?messageID={$message['MessageID']}' class='EditMes'>Edit</a>";
            echo " <a href='sql_delete_mes.php?messageID={$message['MessageID']}' class='DelMes'>Delete</a>";
        }

        echo "</div>";
    }
?>



        <form method="post" action="">
            <input type="hidden" name="token" value="<?php echo $csrf_token; ?>">
            <!-- For amking a message unique (preventing errors) -->
            <textarea name="messageContent" rows="3" required placeholder="Type your message..."></textarea>

            <!-- Text area for entering the message content -->
            <input class="MesButton" type="submit" name="sendMessage" value="Send"> <!-- Submit button for sending the message -->
        </form>

    <!-- JavaScript to convert UTC timestamps to local time -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const chatMessages = document.querySelectorAll('.chat-message');
        chatMessages.forEach(message => {
            const timestamp = message.getAttribute('data-timestamp');
            const date = new Date(timestamp);
            const formattedTimestamp = document.createElement('span');
            formattedTimestamp.classList.add('timestamp');
            formattedTimestamp.textContent = date.toLocaleString();
            message.appendChild(formattedTimestamp);
        });
    });
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