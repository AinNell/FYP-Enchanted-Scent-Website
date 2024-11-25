<?php
// Enable error reporting to catch any issues
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// Retrieve admin records
$sql = "SELECT * FROM admin";
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Fetch and display the records if any
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Admins</title>
    <link rel="stylesheet" href="view_admin.css">
</head>
<body>
    <div class="container">
        <h1>View Admins</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if the query returned any rows
                if ($result->num_rows > 0) {
                    // Output each row of data
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['admin_id']) . "</td>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>" . htmlspecialchars($row['admin_email']) . "</td>
                                <td><a href='delete_admin.php?id=" . htmlspecialchars($row['admin_id']) . "' class='delete-btn'>Delete</a></td>
                            </tr>";
                    }
                } else {
                    // If no records are found
                    echo "<tr><td colspan='4'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="back-link">
            <a href="adminmenu.html">Back to Menu</a>
        </div>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
