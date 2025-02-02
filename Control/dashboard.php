<?php
session_start();

if (!isset($_SESSION['owner_log_in']) || $_SESSION['owner_log_in'] !== true) {
    header('Location: O_c_login.php');
    exit();
}

$server = "localhost";
$username = "root";
$password = "";
$database = "jobarul_bs";

// Connection
$con = new mysqli($server, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Employee count
$employee_count_query = "SELECT COUNT(*) AS count FROM employees";
$employee_count_result = $con->query($employee_count_query);
$employee_count = ($employee_count_result->num_rows > 0) ? $employee_count_result->fetch_assoc()['count'] : 0;

// Customer count
$customer_count_query = "SELECT COUNT(*) AS count FROM customers";
$customer_count_result = $con->query($customer_count_query);
$customer_count = ($customer_count_result->num_rows > 0) ? $customer_count_result->fetch_assoc()['count'] : 0;

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <h1>Welcome to the Owner Dashboard</h1>
    
    <section>
        <h2>System Overview</h2>
        <ul>
            <li>Total Employees: <span id="employee-count"><?php echo htmlspecialchars($employee_count); ?></span></li>
            <li>Total Customers: <span id="customer-count"><?php echo htmlspecialchars($customer_count); ?></span></li>
        </ul>
    </section>

    <section>
        <h2>Owner Controls</h2>
        <ul>
            <li><button onclick="window.location.href='profile.php'">Profile Settings</button></li>
            <li><button onclick="window.location.href='info.php'">System Information</button></li>
            <li><button onclick="window.location.href='mCustomer.php'">Manage Customers</button></li>
        </ul>
    </section>
    <script src="../js/dashboard.js"></script>
</body>
</html>