<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "localhost";
$dbusername = "root"; // Your MySQL username
$dbpassword = ""; // Your MySQL password
$dbname = "ec"; // Your database name

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connected to the database successfully!<br>";  // Debug the connection
}

// Initialize search term
$searchTerm = isset($_GET['search'])?$_GET['search'] : ''; // Get the search term from URL query string
// echo "Search term: " . htmlspecialchars($searchTerm) . "<br>";  // Debug the search term
// Prepare the SQL query to search for records based on name or email
$sql = "SELECT * FROM survey WHERE student_name LIKE ? OR email LIKE ?";
$stmt = $conn->prepare($sql);

// Bind parameters to the SQL query
$searchTermWildcard = "%" . $searchTerm . "%"; // Add wildcards for LIKE search
$stmt->bind_param("ss", $searchTermWildcard, $searchTermWildcard);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Start HTML output
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Records</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f1e1d3; /* Light brown background */
            color: #4f3b2f; /* Dark brown text */
        }

        header {
            text-align: center;
            background-color: #9e7b5b; /* Dark brown header */
            color: white;
            padding: 15px;
        }

        h1 {
            font-size: 2.5rem;
        }

        /* Styling for the search form */
        .search-container {
            text-align: center;
            margin: 30px 0;
        }

        .search-container input[type="text"] {
            padding: 10px;
            width: 50%;
            max-width: 400px;
            font-size: 1rem;
            border: 2px solid #9e7b5b;
            border-radius: 5px;
            margin-right: 10px;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #9e7b5b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .search-container button:hover {
            background-color: #7a5c42; /* Slightly darker brown */
        }

        /* Table Styling */
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #9e7b5b; /* Dark brown table header */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f2e2; /* Lighter brown for alternating rows */
        }

        tr:hover {
            background-color: #e6d7b9; /* Slightly darker brown on hover */
        }

        .back-btn {
            display: inline-block;
            background-color: #9e7b5b;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px auto;
            display: block;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #7a5c42; /* Slightly darker brown */
        }
    </style>
</head>
<body>

    <header>
        <h1>Survey Records</h1>
    </header>

    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="surveyrecord.php">
            <input type="text" name="search" placeholder="Search by name or email..." value="' . htmlspecialchars($searchTerm) . '">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Display the table -->
    <div style="margin: 20px auto; overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Scents</th>
                    <th>Local Brands</th>
                    <th>Additional Brands</th>
                    <th>Submission Date</th>
                </tr>
            </thead>
            <tbody>';

if ($result->num_rows > 0) {
    // Display each record in a table row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['student_id']) . "</td>
                <td>" . htmlspecialchars($row['student_name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['gender']) . "</td>
                <td>" . htmlspecialchars($row['scents']) . "</td>
                <td>" . htmlspecialchars($row['localBrands']) . "</td>
                <td>" . htmlspecialchars($row['additionalBrands']) . "</td>
                <td>" . htmlspecialchars($row['submission_date']) . "</td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No results found.</td></tr>";
}

echo '
            </tbody>
        </table>
    </div>

    <!-- Back to Menu Button -->
    <div style="text-align:center;">
        <a href="adminmenu.html" class="back-btn">Back to Menu</a>
    </div>

</body>
</html>';

$stmt->close();
$conn->close();
?>
