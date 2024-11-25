<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ec";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $search_name = $_POST['search_name'] ?? '';
    $search_brand = $_POST['search_brand'] ?? '';

    $query = "SELECT * FROM perfumes WHERE 1=1";

    if (!empty($search_name)) {
        $search_name = "%" . $search_name . "%";
        $query .= " AND perfume_name LIKE :search_name";
    }
    if (!empty($search_brand)) {
        $query .= " AND brand_name = :search_brand";
    }

    $stmt = $pdo->prepare($query);

    if (!empty($search_name)) {
        $stmt->bindParam(':search_name', $search_name, PDO::PARAM_STR);
    }
    if (!empty($search_brand)) {
        $stmt->bindParam(':search_brand', $search_brand, PDO::PARAM_STR);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Header with Enchanted Scents title
    echo "<div style='background-color: #D9C1A0; padding: 20px; text-align: center; font-family: Arial, sans-serif;'>";
    echo "<h1 style='color: #5A3D2D;'>Enchanted Scents</h1>";
    echo "</div>";

    // Admin Menu Button
    echo "<div style='text-align: center; margin: 20px 0;'>";
    echo "<a href='adminmenu.html' style='background-color: #5A3D2D; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Menu</a>";
    echo "</div>";

    // Table Design
    if ($results) {
        echo "<div style='max-width: 900px; margin: 20px auto;'>";
        echo "<table style='width: 100%; border-collapse: collapse; text-align: left; border: 1px solid #ddd;'>";
        echo "<thead style='background-color: #A67C52; color: white;'>";
        echo "<tr>";
        echo "<th style='padding: 12px; border: 1px solid #ddd;'>Perfume Name</th>";
        echo "<th style='padding: 12px; border: 1px solid #ddd;'>Brand</th>";
        echo "<th style='padding: 12px; border: 1px solid #ddd;'>Description</th>";
        echo "<th style='padding: 12px; border: 1px solid #ddd;'>Notes</th>";  // New Notes column
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($results as $row) {
            echo "<tr style='background-color: #F4E1C1;'>";
            echo "<td style='padding: 12px; border: 1px solid #ddd;'>" . htmlspecialchars($row['perfume_name']) . "</td>";
            echo "<td style='padding: 12px; border: 1px solid #ddd;'>" . htmlspecialchars($row['brand_name']) . "</td>";
            echo "<td style='padding: 12px; border: 1px solid #ddd;'>" . htmlspecialchars($row['description']) . "</td>";
            echo "<td style='padding: 12px; border: 1px solid #ddd;'>" . htmlspecialchars($row['notes']) . "</td>"; // Display Notes
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p style='text-align: center; font-size: 1.2em; color: #ff4d4d;'>No results found.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='text-align: center; color: #ff4d4d; font-weight: bold;'>Error: " . $e->getMessage() . "</p>";
}
?>
