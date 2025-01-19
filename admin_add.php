<?php
session_start();

if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit;
}

include("db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;

    if (!empty($name) && $price > 0 && $quantity > 0) {
        $stmt = $conn->prepare("INSERT INTO grocery_item (name, price, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $name, $price, $quantity);
        $stmt->execute();
        $stmt->close();

        echo "<h2>Item added successfully.</h2>";
    } else {
        echo "<h2>Please provide valid input for all fields.</h2>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    session_destroy();
    header("Location: adminlogin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Grocery Item</title>
    <link rel="stylesheet" href="styles2.css">
    </head>
<body>
    <h1>Add Grocery Item</h1>

    <form method="POST" action="">
        <input type="hidden" name="action" value="add">
        <label for="name">Item Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter item name" required>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" placeholder="Enter price" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" required>

        <button type="submit">Add Item</button>
    </form>

    <form method="POST" action="">
        <input type="hidden" name="action" value="logout">
        <button type="submit">Logout</button>
    </form>
</body>
</html>