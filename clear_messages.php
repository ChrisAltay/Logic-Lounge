<?php
// clear_messages.php
/*This code will clear all messages from the senders private chat,
 it will also even delete their messages from the recievers side but for respect
 both users cannot delete the others messages*/

include "dbcon.php";
session_start();

// Check if the user is logged in and the request is POST
if (isset($_SESSION['loggedInUserID']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $loggedInUserID = $_SESSION['loggedInUserID'];
    
    // Create connection
    try {
        $pdo = new PDO($attr, $user, $pass, $opts);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
    
    // SQL to delete all messages where the logged-in user is the sender
    $deleteQuery = "DELETE FROM Messages WHERE UserID = :loggedInUserID AND IsPrivate = 1";
    $deleteStatement = $pdo->prepare($deleteQuery);
    
    // Bind parameters
    $deleteStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);
    
    // Execute and redirect
    if ($deleteStatement->execute()) {
        // Redirect back to the private messages page with a success message
        header('Location: Pms.php?status=success');
        exit();
    } else {
        // Redirect back with an error message
        header('Location: Pms.php?status=error');
        exit();
    }
} else {
    // Redirect user to the login page if not logged in
    header('Location: Signin_up.php');
    exit();
}
?>

