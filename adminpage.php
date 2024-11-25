<?php
session_start(); // Start the session to store session variables

// Database connection details
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ec"; 

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect to admin menu if already logged in
if (isset($_SESSION['admin_password'])) {
    header("Location: adminmenu.html");
    exit();
}

// Process the login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from the form and sanitize to prevent security risks
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    // Debugging output: Check the fetched hashed password from the database
    echo "Fetched Hashed Password: " . $hashed_password . "<br>";
    echo "Entered Password: " . $password . "<br>";

    // Check if the username exists and the password is correct
    if ($stmt->num_rows > 0) {
        // Username exists, now check the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['admin_username'] = $username; // Store username in session
            $stmt->close(); // Close the statement
            header("Location: adminmenu.html"); // Redirect to the admin menu
            exit();
        } else {
            // Invalid password, display an error message
            $stmt->close(); // Close the statement
            echo "Password is incorrect.<br>";
            header("Location: adminpage.html ?error=Invalid password.");
            exit();
        }
    } else {
        // Invalid username, display an error message
        $stmt->close(); // Close the statement
        echo "Username not found.<br>";
        header("Location: adminpage.html?error=Invalid username.");
        exit();
    }
}

// Close the connection
$conn->close();
?>
