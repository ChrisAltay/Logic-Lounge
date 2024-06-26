<?php
//Sendpm.php

/*Notes
This page allows the user to send a private message to the user of choice. Once they
have selected that user, they can send the message.*/

// Including the database connection file
include "dbcon.php";

// Starting the session
session_start();

// Redirecting to the login page if the user is not logged in
if (!isset($_SESSION['loggedInUserID'])) {
    header('Location: Signin_up.php');
    exit();
}

// Getting the logged-in user's information
$loggedInUserID = $_SESSION['loggedInUserID'];

// Creating a PDO database connection
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}

// Handling sending private messages
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sendPrivateMessage'])) {
    // Retrieving message content from the form
    $messageContent = $_POST["privateMessageContent"];

    // Retrieving recipient ID from the form
    if (isset($_POST["recipientID"])) {
        $recipientID = $_POST["recipientID"];
    } else {
        $createMessage = "<p class='error'> Error: Recipient user not specified.</p>";
    }

    // Checking if the recipient user exists
    $checkRecipientQuery = "SELECT COUNT(*) AS count FROM UserAccount WHERE UserID = :recipientID";
    $checkRecipientStatement = $pdo->prepare($checkRecipientQuery);
    $checkRecipientStatement->bindParam(':recipientID', $recipientID, PDO::PARAM_INT);
    $checkRecipientStatement->execute();
    $recipientExists = $checkRecipientStatement->fetchColumn();

    if ($recipientExists) {
        // Inserting message into the Messages table
        $insertQuery = "INSERT INTO Messages (UserID, RecipientID, Content, IsPrivate) VALUES (:loggedInUserID, :recipientID, :content, 1)";
        $insertStatement = $pdo->prepare($insertQuery);
        // Binding parameters
        $insertStatement->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);
        $insertStatement->bindParam(':recipientID', $recipientID, PDO::PARAM_INT);
        $insertStatement->bindParam(':content', $messageContent, PDO::PARAM_STR);

        // Executing the query
        try {
            $insertStatement->execute();
            // Redirecting to Pms.php after sending the private message
            header('Location: Pms.php');
            exit();
        } catch (PDOException $e) {
            $createMessage = "<p class='error'> Error sending private message: </p>" . $e->getMessage();
        }
    } else {
        $createMessage = "<p class='error'> Cannot send message: Recipient user does not exist. </p>";
    }
}

// Fetching usernames and user IDs based on the input query
$userData = array();
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $searchQuery = "SELECT UserID, Username FROM UserAccount WHERE Username LIKE :query";
    $searchStatement = $pdo->prepare($searchQuery);
    $searchStatement->bindValue(':query', $query . '%', PDO::PARAM_STR); // Change the LIKE pattern to search for names starting with the query
    $searchStatement->execute();

    // Fetching usernames and user IDs and returning as JSON
    $userData = $searchStatement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($userData);
    exit(); // Terminating script after sending JSON data
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

<body class="sendprivmes">
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

        <div class="SendPrivMes">
        <h1>Send Private Message</h1>
        

        <?php if (isset($createMessage))
            echo $createMessage; ?>
        <!-- Displaying any error message if it's set -->

        <!-- Form for sending private messages -->
        <form autocomplete="off" method="post" action="">
            <!-- Disabling autocomplete to prevent browser suggestions -->

            <!-- Textarea for typing the message -->
            <textarea name="privateMessageContent" rows="3" placeholder="Type Message Here" required></textarea><br>

            <!-- Label for selecting the recipient -->
            <label for="recipientUsername">Who would you like to send this message to?</label>
            <!-- Autocomplete feature -->
            <div class="autocomplete" style="width:300px;">
                <!-- Input field for entering recipient's username -->
                <input type="text" id="recipientUsername" name="recipientUsername" class="form-control" required
                    autocomplete="off">

                <!-- Hidden input field to store the recipient's ID -->
                <input type="hidden" id="recipientID" name="recipientID">

                <!-- Container to display autocomplete results -->
                <div id="autocompleteResults" class="autocomplete-items"></div>
            </div>

            <!-- Button to send the private message -->
            <input class="MESBUTTON" type="submit" name="sendPrivateMessage" value="Send Message">
        </form>

        </div>




    <script>

        // Wait for the DOM content to be fully loaded before executing the code
        document.addEventListener('DOMContentLoaded', function () {
            // Get references to DOM elements
            const recipientInput = document.getElementById('recipientUsername'); // Input field for recipient's username
            const recipientIDInput = document.getElementById('recipientID'); // Hidden input field for recipient's ID
            const autocompleteResults = document.getElementById('autocompleteResults'); // Container for autocomplete results

            // Add event listener for input event on recipientInput
            recipientInput.addEventListener('input', function () {
                // Trim whitespace from the input value
                const query = recipientInput.value.trim();

                // If the input query is empty, clear autocomplete results and return
                if (query.length === 0) {
                    autocompleteResults.innerHTML = '';
                    return;
                }

                // Fetch autocomplete results from the server
                fetch(`<?php echo $_SERVER['PHP_SELF']; ?>?query=${query}`)
                    .then(response => response.json()) // Parse the response as JSON
                    .then(data => {
                        console.log(data); // Log the received data to the console for debugging
                        showAutocompleteResults(data); // Call the function to display autocomplete results
                    })
                    .catch(error => {
                        console.error('Error fetching autocomplete results:', error); // Log any errors to the console
                    });
            });

            // Function to display autocomplete results
            function showAutocompleteResults(results) {
                autocompleteResults.innerHTML = ''; // Clear previous autocomplete results

                // If there are no results, hide autocompleteResults and return
                if (results.length === 0) {
                    autocompleteResults.style.display = 'none';
                    return;
                }

                // Create a new div element to hold the autocomplete results
                const resultList = document.createElement('div');

                // Iterate over each result and create a div element for each one
                results.forEach(result => {
                    const item = document.createElement('div');
                    item.textContent = result.Username; // Set the text content of the div to the username

                    // Add event listener for click event on each result item
                    item.addEventListener('click', function () {
                        recipientInput.value = result.Username; // Set the value of the recipient input field to the selected username
                        recipientIDInput.value = result.UserID; // Set the value of the hidden input field to the selected user's ID
                        autocompleteResults.innerHTML = ''; // Clear autocomplete results
                    });

                    // Append the result item to the resultList
                    resultList.appendChild(item);
                });

                // Append the resultList to the autocompleteResults container
                autocompleteResults.appendChild(resultList);

                // Make the autocompleteResults container visible
                autocompleteResults.style.display = 'block';
            }
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