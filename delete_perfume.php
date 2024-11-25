<?php
// Enable error reporting to catch any issues
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// Handle search query if set
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']); // Sanitize the search input
}

// Build the query with search functionality
$query = "SELECT * FROM perfumes WHERE perfume_name LIKE '%$search_query%'";
$result = $conn->query($query);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Delete perfume if a POST request is made
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $perfume_id = $_POST["perfume_id"];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM perfumes WHERE perfume_id = ?");
    $stmt->bind_param("i", $perfume_id);
    $stmt->execute();
    $stmt->close();

    // Redirect after deletion
    header("Location: delete_perfume.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - View and Delete Perfumes</title>
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

        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar {
            padding: 8px;
            font-size: 16px;
            width: 50%;
            border-radius: 5px;
            border: 1px solid #ccc;
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

        .delete-btn {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete-btn:hover {
            background-color: #c0392b;
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

    <h2>Perfume Records</h2>

    <!-- Search Bar -->
    <div class="search-container">
        <form method="GET" action="delete_perfume.php">
            <input type="text" name="search" class="search-bar" placeholder="Search for a perfume..." value="<?php echo htmlspecialchars($search_query); ?>">
        </form>
    </div>

    <!-- Table to display perfume records -->
    <table>
        <thead>
            <tr>
                <th>Perfume ID</th>
                <th>Perfume Name</th>
                <th>Brand</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are results
            if ($result->num_rows > 0) {
                // Loop through the rows and display the data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['perfume_id']) . "</td>
                            <td>" . htmlspecialchars($row['perfume_name']) . "</td>
                            <td>" . htmlspecialchars($row['brand_id']) . "</td>
                            <td>" . htmlspecialchars($row['description']) . "</td>
                            <td>
                                <form action='' method='POST'>
                                    <input type='hidden' name='perfume_id' value='" . htmlspecialchars($row['perfume_id']) . "'>
                                    <button type='submit' class='delete-btn'>Delete</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                // If no records are found
                echo "<tr><td colspan='5'>No perfumes found</td></tr>";
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
