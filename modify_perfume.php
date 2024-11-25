<?php
// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "ec";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the perfume name and the new description from the POST data
    $perfume_name = $_POST['perfume_name']; // Use 'perfume_name' as per your form field
    $new_description = $_POST['new_description'];

    // Check if both perfume_name and new_description are set and not empty
    if (empty($perfume_name) || empty($new_description)) {
        echo "<script>alert('Please provide both perfume name and description.'); window.location.href = 'modify_perfume.php';</script>";
        exit;
    }

    // Prepare the update query
    $stmt = $conn->prepare("UPDATE perfumes SET description = ? WHERE perfume_name = ?");
    $stmt->bind_param("ss", $new_description, $perfume_name);  // "ss" means string types

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Perfume description updated successfully!'); window.location.href = 'adminmenu.html';</script>";
    } else {
        echo "<script>alert('Error updating perfume description. Please try again.'); window.location.href = 'modify_perfume.php';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Perfume Description</title>
    <style>
        /* Styling for the form page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #d8c6b8;
            padding: 15px 30px;
            text-align: center;
            color: #4f3b2f;
        }

        h1 {
            font-size: 28px;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
        }

        input, textarea {
            padding: 10px;
            font-size: 16px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #9e7b5b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #7a5c42;
        }

        .back-btn {
            display: inline-block;
            background-color: #9e7b5b;
            color: white;
            padding: 10px 20px;
            margin: 20px 0;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #7a5c42;
        }
    </style>
</head>
<body>

<header>
    <h1>Modify Perfume Description</h1>
</header>

<div class="container">
    <form method="POST" action="modify_perfume.php">
        <label for="perfume_name">Perfume Name:</label>
        <input type="text" name="perfume_name" required>

        <label for="new_description">New Description:</label>
        <textarea name="new_description" rows="4" required></textarea>

        <button type="submit">Update Description</button>
    </form>

    <a href="adminmenu.html" class="back-btn">Back to Admin Menu</a>
</div>

</body>
</html>
