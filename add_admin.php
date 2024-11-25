<?php
// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ec";

// Create a new database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$username = $_POST['username']; // New username field
$admin_email = $_POST['admin_email'];
$admin_password = $_POST['admin_password'];
$confirm_admin_password = $_POST['confirm_admin_password'];

// Initialize a message variable
$message = "";

// Check if passwords match
if ($admin_password !== $confirm_admin_password) {
    $message = "Error: Passwords do not match.";
} else {
    // Hash the password for security
    $hashedPassword = password_hash($admin_password, PASSWORD_BCRYPT);

    // Prepare and bind statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO admin (username, admin_email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $admin_email, $hashedPassword);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "New admin added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="delete_admin.css">
</head>
<body>
    <div class="container">
        <h1>Add Admin Confirmation</h1>
        <p><?php echo $message; ?></p>

        <?php if ($message == "New admin added successfully!"): ?>
            <div class="buttons">
                <a href="adminmenu.html" class="confirm-btn">Go to Admin Menu</a>
            </div>
        <?php else: ?>
            <div class="buttons">
                <a href="add_admin.html" class="cancel-btn">Try Again</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
