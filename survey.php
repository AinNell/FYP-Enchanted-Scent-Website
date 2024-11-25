<?php
// Database connection details
$servername = "localhost"; // Host
$dbusername = "root";        
$dbpassword = "";            
$dbname = "ec";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    // Display detailed error message
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$student_id = $_POST['student_id'];
$student_name = $_POST['student_name'];
$email = $_POST['email'];
$gender = $_POST['gender'];
$scents = implode(", ", $_POST['scents']);
$localBrands = implode(", ", $_POST['localBrands']);
$additionalBrands = $_POST['additionalBrands'];

// Insert data into survey table
$sql = "INSERT INTO survey (student_id, student_name, email, gender, scents, localBrands, additionalBrands, submission_date) 
        VALUES ('$student_id', '$student_name', '$email', '$gender', '$scents', '$localBrands', '$additionalBrands', NOW())";

// Execute the query
if ($conn->query($sql) === TRUE) {
    // Redirect on success
    header("Location: ecmenu.html?status=success");
    exit();
} else {
    // Show error message if the query fails
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
