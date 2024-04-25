<!-- forgot_pass.php -->

<!--Notes
This is an entry form so that the user can enter in their name if they forget their password.
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="styles.css"><!-- Linking to a custom stylesheet named styles.css -->

    <title>Logic Lounge</title>
</head>

<body class="forgotpassbody">

    <div class="forgotpass">
    <h1>Forgot Password</h1> <!-- Heading for the forgot password section -->

    <!-- Form for entering username to send reset link -->
    <form action="send_reslink.php" method="post">
        <label for="username">Enter your username:</label><br> <!-- Label and input field for entering username -->
        <input type="text" id="username" name="username" required><br> <!-- Text input field for entering username, with 'required' attribute to make it mandatory -->
        <input class="mesbutton" type="submit" value="Send Reset Link"> <!-- Submit button to send the reset link -->
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