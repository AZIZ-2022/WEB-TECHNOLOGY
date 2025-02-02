<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['owner_log_in']) || $_SESSION['owner_log_in'] !== true) {
    header('Location: O_c_login.php');
    exit();
}

// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "jobarul_bs";

$con = new mysqli($server, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Initialize variables
$errors = [];
$success = '';
$customers = [];
$edit_data = null;

// Handle Add Customer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_customer'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $membership = trim($_POST['membership']);

    // Validation
    if (empty($name)) $errors[] = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (!preg_match("/^[0-9]{10}$/", $phone)) $errors[] = "Invalid phone number";
    if (!in_array($membership, ['basic', 'premium', 'vip'])) $errors[] = "Invalid membership level";

    if (empty($errors)) {
        $stmt = $con->prepare("INSERT INTO customers (name, email, phone, address, membership) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $address, $membership);
        
        if ($stmt->execute()) {
            $success = "Customer added successfully!";
        } else {
            $errors[] = "Failed to add customer: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle Edit Customer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_customer'])) {
    $id = $_POST['customer_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $membership = trim($_POST['membership']);

    // Validation
    if (empty($name)) $errors[] = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (!preg_match("/^[0-9]{10}$/", $phone)) $errors[] = "Invalid phone number";
    if (!in_array($membership, ['basic', 'premium', 'vip'])) $errors[] = "Invalid membership level";

    if (empty($errors)) {
        $stmt = $con->prepare("UPDATE customers SET name=?, email=?, phone=?, membership=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $membership, $id);
        
        if ($stmt->execute()) {
            $success = "Customer updated successfully!";
        } else {
            $errors[] = "Failed to update customer: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle Delete Customer
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        $password = $_POST['admin_password'];

        // Verify admin password (example)
        if ($password !== 'your_admin_password') {
            $errors[] = "Invalid admin password";
        } else {
            $stmt = $con->prepare("DELETE FROM customers WHERE id=?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $success = "Customer deleted successfully!";
            } else {
                $errors[] = "Failed to delete customer: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        // Show delete confirmation
        $delete_id = $id;
    }
}

// Handle Edit Request
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $con->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_data = $result->fetch_assoc();
    $stmt->close();
}

// Fetch all customers
$result = $con->query("SELECT * FROM customers");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customers</title>
</head>
<body>
    <h1>Manage Customers</h1>

    <?php if (!empty($errors)): ?>
        <div>
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div >
            <p><?php echo htmlspecialchars($success); ?></p>
        </div>
    <?php endif; ?>

    <!-- Add/Edit Customer Form -->
    <h2><?php echo isset($edit_data) ? 'Edit Customer' : 'Add New Customer'; ?></h2>
    <form method="POST">
        <?php if (isset($edit_data)): ?>
            <input type="hidden" name="customer_id" value="<?php echo $edit_data['id']; ?>">
        <?php endif; ?>
        
        <div>
            <label>Full Name:</label>
            <input type="text" name="name" required maxlength="50"
                   value="<?php echo htmlspecialchars($edit_data['name'] ?? ''); ?>">
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required
                   value="<?php echo htmlspecialchars($edit_data['email'] ?? ''); ?>">
        </div>
        <div>
            <label>Phone:</label>
            <input type="tel" name="phone" pattern="[0-9]{10}"
                   value="<?php echo htmlspecialchars($edit_data['phone'] ?? ''); ?>">
        </div>
        <div>
            <label>Address:</label>
            <input type="text" name="address"
                   value="<?php echo htmlspecialchars($edit_data['address'] ?? ''); ?>">
        </div>
        <div>
            <label>Membership Level:</label>
            <select name="membership">
                <option value="basic" <?php echo ($edit_data['membership'] ?? '') === 'basic' ? 'selected' : ''; ?>>Basic</option>
                <option value="premium" <?php echo ($edit_data['membership'] ?? '') === 'premium' ? 'selected' : ''; ?>>Premium</option>
                <option value="vip" <?php echo ($edit_data['membership'] ?? '') === 'vip' ? 'selected' : ''; ?>>VIP</option>
            </select>
        </div>
        <button type="submit" name="<?php echo isset($edit_data) ? 'edit_customer' : 'add_customer'; ?>">
            <?php echo isset($edit_data) ? 'Update Customer' : 'Add Customer'; ?>
        </button>
        <?php if (isset($edit_data)): ?>
            <a href="mCustomer.php">Cancel Edit</a>
        <?php endif; ?>
    </form>

    <!-- Customer List -->
    <h2>Customer List</h2>
    <table>
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Membership</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?php echo htmlspecialchars($customer['id']); ?></td>
                <td><?php echo htmlspecialchars($customer['name']); ?></td>
                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                <td><?php echo htmlspecialchars($customer['membership']); ?></td>
                <td>
                    <a href="mCustomer.php?edit=<?php echo $customer['id']; ?>">Edit</a>
                    <a href="mCustomer.php?delete=<?php echo $customer['id']; ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Delete Confirmation -->
    <?php if (isset($delete_id)): ?>
    <div>
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this customer?</p>
        <form method="POST">
            <label>Admin Password:</label>
            <input type="password" name="admin_password" required>
            <button type="submit" name="confirm_delete">Confirm Delete</button>
            <a href="mCustomer.php">Cancel</a>
        </form>
    </div>
    <?php endif; ?>

    <!-- Navigation -->
    <div>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>