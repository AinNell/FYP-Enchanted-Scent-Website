<?php
// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ec";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the admin ID from the URL
if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    // Prepare and execute the delete query
    $sql = "DELETE FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id); // Use the admin_id for deletion

    if ($stmt->execute()) {
        $message = "Admin deleted successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    $message = "Invalid request.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Admin</title>
    <link rel="stylesheet" href="delete_admin.css">
</head>
<body>
    <div class="container">
        <h1>Delete Admin Confirmation</h1>
        <p><?php echo $message; ?></p>

        <?php if (isset($admin_id) && $message == "Admin deleted successfully."): ?>
            <p>The admin has been successfully deleted.</p>
            <div class="buttons">
                <a href="view_admin.php" class="confirm-btn">Go to Admin List</a>
            </div>
        <?php else: ?>
            <div class="buttons">
                <a href="view_admin.php" class="cancel-btn">Cancel</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
