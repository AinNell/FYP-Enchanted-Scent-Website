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
$admin_email = $_POST['admin_email'];

// Check if email exists in the admin table
$sql = "SELECT * FROM admin WHERE admin_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Generate a password reset token (using a secure method)
    $token = bin2hex(random_bytes(50));  // 50 bytes -> 100 characters in the token

    // Set the token expiration time (1 hour in this example)
    $expiration = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Store token and expiration time in the database
    $updateSql = "UPDATE admin SET reset_token = ?, reset_token_expiry = ? WHERE admin_email = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sss", $token, $expiration, $admin_email);
    $updateStmt->execute();

    // Send reset password email (In real-life, use PHPMailer or similar libraries to send email)
    $reset_link = "http://yourwebsite.com/reset-password.php?token=$token";  // URL where user can reset password
    $subject = "Password Reset Request";
    $message = "We received a request to reset your password. Click the link below to reset your password:\n\n$reset_link";
    $headers = "From: no-reply@yourwebsite.com";

    // Use PHP's mail function to send the email (or PHPMailer for more advanced features)
    if (mail($admin_email, $subject, $message, $headers)) {
        echo "An email has been sent with instructions to reset your password.";
    } else {
        echo "Error sending email. Please try again later.";
    }

} else {
    echo "No account found with that email address.";
}

// Close the connection
$stmt->close();
$conn->close();

// Redirect back to admin page
header("Location: adminpage.html");
exit();
?>
