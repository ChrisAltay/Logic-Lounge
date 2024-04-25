<?php
//dbcon.php

/*Notes
This allows the connection of the whole site to work.
The chatroom, private chat, etc. 
Without it, the majority of he site WILL NOT work.
*/

$host = 'localhost';    // Database host
$dbname = 'testbase'; // Database name (change as necessary)
$user = 'tester';        // Database username (change as necessary)
$pass = 'comptest';        // Database password (change as necessary)
$chrs = 'utf8mb4';      // Character set

// Database connection attributes
$attr = "mysql:host=$host;dbname=$dbname;charset=$chrs"; // Connection string
$opts =
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // Set error mode to exception
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Set default fetch mode to associative array
        PDO::ATTR_EMULATE_PREPARES => false,             // Disable emulated prepared statements
    ];
?>