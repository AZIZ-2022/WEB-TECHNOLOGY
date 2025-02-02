<?php
session_start();

$server = "localhost";
$username = "root";
$password = "";
$database = "jobarul_bs";

// Create connection
$con = new mysqli($server, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate name
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!preg_match('/^[a-zA-Z ]{2,50}$/', $name)) {
        $errors[] = "Name must be 2-50 characters containing only letters and spaces.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

 
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "Phone number must be 10 digits.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters with one uppercase, one lowercase, one number, and one special character.";
    }


    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

 
    if (empty($errors)) {
        $stmt = $con->prepare("SELECT id FROM owner WHERE ousername = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "Email already registered.";
        }
        $stmt->close();
    }

  
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $con->prepare("INSERT INTO owner (name, ousername, phone, opassword) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['registration_success'] = true;
            header("Location: O_c_login.php");
            exit();
        } else {
            $errors[] = "Registration failed: " . $stmt->error;
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
    <title>Owner Registration</title>
    
</head>
<body>
    <h1>Owner Registration</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php">
      
        <div>
            <label>Full Name:</label>
            <input type="text" name="name" required 
                   placeholder="Enter full name" 
                   minlength="2" maxlength="50"
                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        </div>

        <div>
            <label>Email:</label>
            <input type="email" name="email" required
                   placeholder="Enter valid email"
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>

        <div>
            <label>Contact Number:</label>
            <input type="tel" name="phone" required
                   placeholder="Enter contact number"
                   pattern="[0-9]{10}"
                   value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
        </div>

        <div>
            <label>Password:</label>
            <input type="password" name="password" required
                   placeholder="Create password (min 8 characters)"
                   minlength="8">
        </div>

        <div>
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required
                   placeholder="Re-enter password">
        </div>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="O_c_login.php">Login here</a></p>
</body>
</html>