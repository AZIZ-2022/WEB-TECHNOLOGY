<?php
// Database connection details
$server = "localhost";
$username = "root";
$password = "";
$database = "jobarul_bs"; // Replace with your database name

// Create connection
$con = new mysqli($server, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Initialize variables
$ousername = "";
$message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate username input
    $ousername = trim($_POST['ousername']);

    if (empty($ousername)) {
        $message = "Username is required!";
    } else {
        // Check if the username exists in the database
        $sql = "SELECT * FROM owner WHERE ousername = '$ousername'";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            // Username exists, proceed with password reset logic
            // Generate a unique token for password reset
            $token = bin2hex(random_bytes(32)); // Secure random token
            $token_hash = hash('sha256', $token); // Hash the token for storage

            // Set token expiration (e.g., 1 hour from now)
            $expiry = date("Y-m-d H:i:s", time() + 3600);

            // Update the database with the token and expiry
            $update_sql = "UPDATE owner SET reset_token_hash = '$token_hash', reset_token_expiry = '$expiry' WHERE ousername = '$ousername'";

            if ($con->query($update_sql) === TRUE) {
                // Fetch the user's email (if available) to send the reset link
                $user_data = $result->fetch_assoc();
                $email = $user_data['email']; // Ensure the `email` column exists in the table

                if (!empty($email)) {
                    // Send the password reset email (this is a placeholder)
                    $reset_link = "http://yourwebsite.com/reset_password.php?token=$token";
                    $subject = "Password Reset Request";
                    $body = "Click the link below to reset your password:\n\n$reset_link";

                    // Use PHP's mail() function or a library like PHPMailer to send the email
                    if (mail($email, $subject, $body)) {
                        $message = "Password reset link has been sent to your email.";
                    } else {
                        $message = "Failed to send the password reset email.";
                    }
                } else {
                    $message = "No email address associated with this username.";
                }
            } else {
                $message = "Error updating the database: " . $con->error;
            }
        } else {
            $message = "Username not found in our system!";
        }
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>

    <!-- Display messages -->
    <?php if (!empty($message)): ?>
        <div style="color: <?php echo (strpos($message, 'Error') !== false || strpos($message, 'Failed') !== false) ? 'red' : 'green'; ?>;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <p>Please enter your username below. We will send you a link to reset your password.</p>

    <form action="fpassword.php" method="POST">
        <div>
            <label for="ousername">Username:</label>
            <input type="text" id="ousername" name="ousername" placeholder="Enter your username" required value="<?php echo htmlspecialchars($ousername); ?>">
        </div>

        <div>
            <button type="submit">Reset Password</button>
        </div>
    </form>

    <p>Remember your password? <a href="O_c_login.php">Log in here</a>.</p>
</body>
<script src="../js/fpassword.js"></script>
</html>