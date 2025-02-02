<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['owner_log_in']) || $_SESSION['owner_log_in'] !== true) {
    header('Location: O_c_login.php');
    exit();
}

$server = "localhost";
$username = "root";
$password = "";
$database = "jobarul_bs"; // Database name

// Connection
$con = new mysqli($server, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$o_username = $_SESSION['ousername'];
$message = "";

// Fetch current owner information
$sql = "SELECT * FROM owner WHERE ousername = '$o_username'";
$result = $con->query($sql);
$owner_data = $result->fetch_assoc();

// Profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $current_password = $_POST['current_password'];
    $new_username = trim($_POST['new_username']);
    $new_password = trim($_POST['new_password']);

    // Validate current password
    if (password_verify($current_password, $owner_data['password'])) {
        // Validate new username
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9 ]{2,19}$/', $new_username)) {
            $message = "Invalid username! It must start with a letter, be 3-20 characters long, and contain only letters, numbers, and spaces.";
        }
        // Validate new password
        elseif (!preg_match('/^[a-zA-Z0-9]{4,10}$/', $new_password)) {
            $message = "Invalid password! It must be 4-10 characters long and contain only letters and numbers.";
        } else {
            // Hash new password
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

            // Update owner information
            $update_query = "UPDATE owner SET ousername = '$new_username', password = '$new_password_hashed' WHERE ousername = '$o_username'";

            if ($con->query($update_query) === TRUE) {
                // Update session with new username
                $_SESSION['ousername'] = $new_username;
                $message = "Profile updated successfully!";
                // Refresh owner data
                $sql = "SELECT * FROM owner WHERE ousername = '$new_username'";
                $result = $con->query($sql);
                $owner_data = $result->fetch_assoc();
            } else {
                $message = "Error updating profile: " . $con->error;
            }
        }
    } else {
        $message = "Incorrect current password!";
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Profile</title>
    
</head>
<body>
    <h1>Owner Profile</h1>

    <!-- Display current owner information -->
    <div class="profile-info">
        <h3>Current Information</h3>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($owner_data['ousername']); ?></p>
    </div>

    <!-- Display messages -->
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Profile update form -->
    <form action="profile.php" method="POST">
        <div class="form-group">
            <label for="current_password">Current Password:</label>
            <input type="password" name="current_password" id="current_password" placeholder="Enter current password" required>
        </div>

        <div class="form-group">
            <label for="new_username">New Username:</label>
            <input type="text" name="new_username" id="new_username" placeholder="Enter new username" value="<?php echo htmlspecialchars($owner_data['ousername']); ?>" required>
        </div>

        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" placeholder="Enter new password" required>
        </div>

        <div class="button-group">
            <button type="submit" name="update_profile">Update Profile</button>
        </div>
    </form>

    <!-- Navigation buttons -->
    <div class="button-group">
        <button onclick="window.location.href='dashboard.php'">Dashboard</button>
        <button onclick="window.location.href='O_c_login.php'">Logout</button>
    </div>
    <script src="../js/profile.js"></script>
</body>
</html>