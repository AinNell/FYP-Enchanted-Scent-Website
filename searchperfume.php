<?php
// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ec";

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get search input from the form
    $search_name = isset($_POST['search_name']) ? trim($_POST['search_name']) : '';
    $search_brand = isset($_POST['search_brand']) ? trim($_POST['search_brand']) : '';

    // Base query
    $query = "SELECT * FROM perfumes WHERE 1=1";

    // Add conditions based on user input
    if (!empty($search_name)) {
        $search_name = "%" . $search_name . "%";
        $query .= " AND perfume_name LIKE :search_name";
    }

    if (!empty($search_brand)) {
        $query .= " AND brand_name = :search_brand";
    }

    // Prepare the statement
    $stmt = $pdo->prepare($query);

    // Bind parameters if provided
    if (!empty($search_name)) {
        $stmt->bindParam(':search_name', $search_name, PDO::PARAM_STR);
    }
    if (!empty($search_brand)) {
        $stmt->bindParam(':search_brand', $search_brand, PDO::PARAM_STR);
    }

    // Execute the query
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display results in a styled table
    if (!empty($results)) {
        echo "<div style='max-width: 800px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>";
        echo "<table style='width: 100%; border-collapse: collapse;'>";
        echo "<thead style='background-color: #4CAF50; color: white;'>";
        echo "<tr>";
        echo "<th style='padding: 12px; text-align: left; border: 1px solid #ddd;'>Perfume Name</th>";
        echo "<th style='padding: 12px; text-align: left; border: 1px solid #ddd;'>Brand</th>";
        echo "<th style='padding: 12px; text-align: left; border: 1px solid #ddd;'>Description</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($results as $row) {
            echo "<tr style='background-color: #f9f9f9;'>";
            echo "<td style='padding: 12px; text-align: left; border: 1px solid #ddd;'>" . htmlspecialchars($row['perfume_name']) . "</td>";
            echo "<td style='padding: 12px; text-align: left; border: 1px solid #ddd;'>" . htmlspecialchars($row['brand_name']) . "</td>";
            echo "<td style='padding: 12px; text-align: left; border: 1px solid #ddd;'>" . htmlspecialchars($row['description']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p style='text-align: center; color: #ff4d4d; font-size: 1.2em;'>No perfumes found matching your criteria.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='text-align: center; color: #ff4d4d; font-weight: bold;'>Error: " . $e->getMessage() . "</p>";
}
?>
