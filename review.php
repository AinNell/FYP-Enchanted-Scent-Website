<?php
// review.php

// Database connection (update with your credentials)
$servername = "localhost";
$dbusername = "root";  // Your database username
$dbpassword = "";      // Your database password
$dbname = "ec";      // Your database name

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $brand_name = mysqli_real_escape_string($conn, $_POST['brand_name']);
    $perfume_name = mysqli_real_escape_string($conn, $_POST['perfume_name']);
    $rating = (int)$_POST['rating'];  // Ensure rating is an integer
    $review = mysqli_real_escape_string($conn, $_POST['review']);

    // Validate input (for example, ensuring all fields are filled)
    if (empty($student_name) || empty($brand_name) || empty($perfume_name) || empty($rating) || empty($review)) {
        echo "Please fill in all fields.";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO reviews (student_name, brand_name, perfume_name, rating, review) 
                                VALUES (?, ?, ?, ?, ?)");

        // Bind parameters to the prepared statement
        $stmt->bind_param("sssis", $student_name, $brand_name, $perfume_name, $rating, $review);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to ecmenu.html after successful submission
            header("Location: ecmenu.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
