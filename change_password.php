<?php
// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ec";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Check if the user is logged in, otherwise redirect
if (!isset($_SESSION['admin_id'])) {
    echo "You must be logged in to change your password.";
    exit();
}

$admin_id = $_SESSION['admin_id']; // Get the admin ID from the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Make sure form inputs are set before using them
    $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo "All fields are required.";
    } else {
        // Query to get the current password from the database
        $sql = "SELECT password FROM admin WHERE admin_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verify current password
            if (password_verify($current_password, $row['password'])) {  // Use password_verify for secure comparison
                // Check if new passwords match
                if ($new_password === $confirm_password) {
                    // Hash the new password before saving it
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    // Update the password in the database
                    $updateSql = "UPDATE admin SET password = ? WHERE admin_id = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("si", $hashed_password, $admin_id);
                    if ($updateStmt->execute()) {
                        echo "Password changed successfully.";
                    } else {
                        echo "Error updating password.";
                    }
                } else {
                    echo "New passwords do not match.";
                }
            } else {
                echo "Current password is incorrect.";
            }
        } else {
            echo "Admin not found.";
        }

        $stmt->close();
    }
}

$conn->close();
?>
