<?php
// sql_delete_mes.php (short for delete message)

/*Notes
For SQL Chat
This allows the user to delete their own message.
*/

include "dbcon.php";

// Start the session
session_start();

// Checks if the user is logged in
if (!isset($_SESSION['loggedInUserID'])) {
    // Redirect to the login page if not logged in
    header('Location: Signin_up.php');
    exit();
}

// Creates connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts); // Create a PDO instance for database connection
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode()); // Throw PDOException if connection fails
}

$loggedInUserID = $_SESSION['loggedInUserID']; // Get the logged-in user's ID

// Check if messageID is provided in the URL and is a valid integer
if (isset($_GET['messageID']) && filter_var($_GET['messageID'], FILTER_VALIDATE_INT)) {
    $messageID = $_GET['messageID']; // Get the messageID from the URL

    // Prepare and execute query to delete message from database
$deleteQuery = "DELETE FROM SQLChat WHERE MessageID = :messageID AND UserID = :loggedInUserID";

    $deleteStatement = $pdo->prepare($deleteQuery);

    // Binds parameters
    $deleteStatement->bindParam(':messageID', $messageID, PDO::PARAM_INT);
    $deleteStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);

    try {
        $deleteStatement->execute(); // Execute the delete query
    } catch (PDOException $e) {
        echo "Error deleting message: " . $e->getMessage(); // Display error message if deletion fails
    }

    
    header('Location: sql_chat.php'); // Redirects back to the chatroom after message is deleted
    exit();
} else {
    
    header('Location: sql_chat.php'); // Redirects back to the chatroom if messageID is not set or invalid
    exit();
}
?>