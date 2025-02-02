<?php
session_start();

if (!isset($_SESSION['owner_log_in']) || $_SESSION['owner_log_in'] !==true)
    {
        header('Location: O_c_login.php');
        exit();
    }
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "jobarul_bs";//database name
    
    //conection
    $con = new mysqli($server,$username,$password,$database);
    
    // check
     if ($con->connect_error){
        die ("Connection failed: ". $con->connect_error);
     }
     
     $employee_query = "SELECT * FROM employees";
     $employee_result = $con->query($employee_query);
     
     $con->close();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information Desk</title>
</head>
<body>
    <h1>Employee Information</h1>

    <section>
        <h2>Employee List</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Position</th>
                    <th>Work Time</th>
                    <th>Joining Date</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($employee_result->num_rows > 0) {
                    while ($row = $employee_result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $row['id'] . "</td>
                            <td>" . $row['name'] . "</td>
                            <td>" . $row['age'] . "</td>
                            <td>" . $row['gender'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['phone'] . "</td>
                            <td>" . $row['address'] . "</td>
                            <td>" . $row['position'] . "</td>
                            <td>" . $row['work_time'] . "</td>
                            <td>" . $row['joining_date'] . "</td>
                            <td>" . $row['created_at'] . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No employee data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
    <section>
        <button onclick="window.location.href='dashboard.php'">Dashboard</button>
        <button onclick="window.location.reload();">Refresh</button>
    </section>
</body>
</html>