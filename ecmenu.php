<?php include 'db_connection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EC Menu - Enchanted Scents</title>
    <link rel="stylesheet" href="ecmenu.css">
</head>
<body>

    <!-- Header Section -->
    <header>
        <h1>Enchanted Scents</h1>
        <div class="search-bar">
            <input type="text" placeholder="Search Fragrance...">
            <button class="filter-btn">Filters</button>
        </div>
    </header>

    <!-- Favorites Section -->
    <section class="favorites-section">
        <h2>❤️ Favourites</h2>
        <div class="filters">
            <button class="filter-option">For Him</button>
            <button class="filter-option">For Her</button>
            <button class="filter-option">Unisex</button>
        </div>
    </section>

    <!-- Menu Content -->
    <div class="menu-content">
        <?php
        // Fetch perfumes from the database
        $sql = "SELECT perfume_name, brand_id, description FROM perfumes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card'>";
                echo "<img src='placeholder.jpg' alt='" . htmlspecialchars($row["perfume_name"]) . "'>";
                echo "<div class='card-info'>";
                echo "<h2>" . htmlspecialchars($row["perfume_name"]) . "</h2>";
                echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                echo "<button class='favorite-btn' title='Add to Favourites'>❤️</button>";
                echo "<div class='quantity-control'>";
                echo "<button class='quantity-btn'>-</button>";
                echo "<span class='quantity-display'>0</span>";
                echo "<button class='quantity-btn'>+</button>";
                echo "</div></div></div>";
            }
        } else {
            echo "<p>No perfumes available at the moment.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>

</body>
</html>
