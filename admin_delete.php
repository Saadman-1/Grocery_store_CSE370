<?php
session_start();

if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit;
}

include("db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && (!empty($_POST['id']) || !empty($_POST['name']))) {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';

        $query = "DELETE FROM grocery_item WHERE 1=1";
        $params = [];
        $types = '';

        if (!empty($id)) {
            $query .= " AND id = ?";
            $params[] = $id;
            $types .= 'i';
        }

        if (!empty($name)) {
            $query .= " AND name LIKE ?";
            $params[] = "%$name%";
            $types .= 's';
        }

        $stmt = $conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<h2>Item(s) deleted successfully.</h2>";
        } else {
            echo "<h2>No matching item(s) found to delete.</h2>";
        }

        $stmt->close();
    }

    if (isset($_POST['action']) && $_POST['action'] === 'logout') {
        session_destroy();
        header("Location: adminlogin.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Grocery Items</title>
    <link rel="stylesheet" href="styles2.css">
    
</head>
<body>
    <h1>Delete Grocery Items</h1>

    <form method="POST" action="">
        <label for="id">Delete by ID:</label>
        <input type="number" id="id" name="id" placeholder="Enter ID">

        <label for="name">Delete by Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter Name">

        <button type="submit" name="action" value="delete">Delete</button>
    </form>

    <form method="POST" action="">
        <button type="submit" name="action" value="logout">Logout</button>
    </form>
</body>
</html>