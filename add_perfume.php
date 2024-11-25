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

// Initialize message variable
$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $perfume_name = $_POST["perfume_name"];
    $brand_name = $_POST["brand_name"];
    $notes = $_POST["perfume_notes"]; // Get the perfume notes from the form
    $description = $_POST["description"];

    // Query to get the brand_id based on the brand_name
    $brand_stmt = $conn->prepare("SELECT brand_id FROM brands WHERE brand_name = ?");
    if (!$brand_stmt) {
        die("Error preparing brand query: " . $conn->error);
    }

    $brand_stmt->bind_param("s", $brand_name);
    $brand_stmt->execute();
    $brand_stmt->bind_result($brand_id);

    // Fetch the result
    if ($brand_stmt->fetch()) {
        // Close the brand statement before executing the next query
        $brand_stmt->close();

        // Prepare and bind statement to insert the perfume data into the perfumes table
        $stmt = $conn->prepare("INSERT INTO perfumes (perfume_name, brand_id, brand_name, description, notes) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Error preparing insert query: " . $conn->error);
        }

        $stmt->bind_param("sisss", $perfume_name, $brand_id, $brand_name, $description, $notes);

        // Execute the insertion
        if ($stmt->execute()) {
            $message = "Perfume added successfully!";
        } else {
            $message = "Error inserting perfume: " . $stmt->error; // Display error if insertion fails
        }

        $stmt->close(); // Close the insert statement
    } else {
        // Handle case where brand is not found in the database
        $message = "Error: Brand not found. Please check the brand name and try again.";
        $brand_stmt->close(); // Ensure the statement is closed in all cases
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Perfume</title>
    <link rel="stylesheet" href="delete_admin.css">
</head>
<body>
    <div class="container">
        <h1>Add Perfume Confirmation</h1>
        <p><?php echo $message; ?></p>

        <?php if ($message == "Perfume added successfully!"): ?>
            <div class="buttons">
                <a href="adminmenu.html" class="confirm-btn">Go to Admin Menu</a>
            </div>
        <?php else: ?>
            <div class="buttons">
                <a href="add_perfume.html" class="cancel-btn">Try Again</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
