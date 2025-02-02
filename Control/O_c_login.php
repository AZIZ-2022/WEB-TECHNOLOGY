<?php
session_start();

$server = "localhost";
$username = "root";
$password = "";
$database = "jobarul_bs";

$con = new mysqli($server, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // inputs
    $ousername = trim($_POST['ousername']);
    $opassword = trim($_POST['opassword']);

    // Validate inputs
    if (empty($ousername) || empty($opassword)) {
        $error_message = "Username and Password cannot be empty!";
    } elseif (!filter_var($ousername, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        $sql = "SELECT id, ousername, opassword FROM owner WHERE ousername = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $ousername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($opassword, $row['opassword'])) {
                $_SESSION['o_id'] = $row['id'];
                $_SESSION['ousername'] = $row['ousername'];
                $_SESSION['owner_log_in'] = true;
                header("Location: dashboard.php");
                exit();
            } else {
                $error_message = "Invalid username or password!";
            }
        } else {
            $error_message = "Invalid username or password!";
        }
        $stmt->close();
    }
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Login - Jobarul Broadband</title>
    <link rel="stylesheet" href="../css/login.css">
    <script src="../js/O_c_login.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <img src="p1.png" alt="Company Logo" class="logo">
                <h2>Owner Login</h2>
            </div>

            <?php if(!empty($error_message)): ?>
                <div class="alert error">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="card-body">
                <form action="O_c_login.php" method="POST">
                    <div class="form-group">
                        <label for="ousername">Username</label>
                        <input type="text" 
                               name="ousername" 
                               id="ousername" 
                               class="form-control"
                               placeholder="Enter username"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="opassword">Password</label>
                        <input type="password" 
                               name="opassword" 
                               id="opassword" 
                               class="form-control"
                               placeholder="Enter password"
                               required>
                    </div>

                    <button type="submit" class="btn btn-block">Login</button>
                </form>
            </div>

            <div class="card-footer">
                <a href="fpassword.php" class="link">Forgot Password?</a>
                <span class="divider">|</span>
                <a href="register.php" class="link">Create New Account</a>
            </div>
        </div>
    </div>
    <script src="../js/O_c_login.js"></script>
</body>
</html>