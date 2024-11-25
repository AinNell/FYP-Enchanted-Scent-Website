<?php
// Database connection (update with your credentials)
$servername = "localhost";
$dbusername = "root";  // Your database username
$dbpassword = "";      // Your database password
$dbname = "ec";        // Your database name

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the review records from the database
$query = "SELECT * FROM reviews"; // Assuming the table is named 'reviews'
$result = $conn->query($query);  // Use the $conn object for the query
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Review Records</title>
    <link rel="stylesheet" href="adminmenu.css">
    <style>
        /* Basic Styling for Admin Record Page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            display: flex;
            justify-content: space-between;
            background-color: #d8c6b8;
            padding: 15px 30px;
            align-items: center;
        }

        h1 {
            font-size: 28px;
            color: #4f3b2f;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            font-weight: 600;
            color: #6b432e;
            margin-top: 40px;
        }

        table {
            width: 90%;
            margin: 40px auto;
            border-collapse: collapse;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #9e7b5b;
            color: white;
        }

        td {
            color: #333;
        }

        .back-btn {
            display: inline-block;
            background-color: #9e7b5b;
            color: white;
            padding: 10px 20px;
            margin: 20px 0;
            text-decoration: none;
            border-radius: 5px;
            align-items: center;
        }

        .back-btn:hover {
            background-color: #7a5c42;
        }
        
    </style>
</head>
<body>

    <header>
        <div class="header-left">
            <h1>Enchanted Scents</h1>
        </div>
    </header>

    <h2>Review Records</h2>

    <!-- Table to display review records -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Brand Name</th>
                <th>Perfume Name</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Submission Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are results
            if ($result->num_rows > 0) {
                // Loop through the rows and display the data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['id'] . "</td>
                            <td>" . $row['student_name'] . "</td>
                            <td>" . $row['brand_name'] . "</td>
                            <td>" . $row['perfume_name'] . "</td>
                            <td>" . $row['rating'] . "</td>
                            <td>" . $row['review'] . "</td>
                            <td>" . $row['submission_date'] . "</td>
                          </tr>";
                }
            } else {
                // If no records are found
                echo "<tr><td colspan='7'>No reviews found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Back to Admin Menu -->
    <a href="adminmenu.html" class="back-btn">Back to Admin Menu</a>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
