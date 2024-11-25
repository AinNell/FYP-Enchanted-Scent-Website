<?php
// Connect to database
$conn = new mysqli("localhost", "username", "password", "ec"); // Replace with your credentials

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from POST request
$perfume_id = $_POST['perfume_id'];
$perfume_name = $_POST['perfume_name'];
$brand_name = $_POST['brand_name'];
$description = $_POST['description'];

// Prepare the update statement
$stmt = $conn->prepare("UPDATE perfumes SET perfume_name = ?, brand_name = ?, description = ? WHERE perfume_id = ?");
$stmt->bind_param("sssi", $perfume_name, $brand_name, $description, $perfume_id);

// Execute the update
if ($stmt->execute()) {
    echo "Perfume updated successfully!";
} else {
    echo "Error updating perfume: " . $conn->error;
}

// Close connections
$stmt->close();
$conn->close();

// Redirect back to admin page
header("Location: adminmenu.html");
exit();
?>
