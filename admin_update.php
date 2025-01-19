<?php
session_start();

if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit;
}

include("db_connect.php");

$search_results = [];
$search_query = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search']) && (!empty($_POST['id']) || !empty($_POST['name']))) {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';

        $query = "SELECT * FROM grocery_item WHERE 1=1";
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
        $result = $stmt->get_result();
        $search_results = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    if (isset($_POST['action']) && $_POST['action'] === 'update' && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $quantity = $_POST['quantity'] ?? 0;

        $stmt = $conn->prepare("UPDATE grocery_item SET name = ?, price = ?, quantity = ? WHERE id = ?");
        $stmt->bind_param("sdii", $name, $price, $quantity, $id);
        $stmt->execute();
        $stmt->close();

        echo "<h2>Item with ID $id updated successfully.</h2>";
    }

    if (isset($_POST['logout'])) {
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
    <title>Search Grocery Items</title>
    <link rel="stylesheet" href="styles2.css">
    
</head>
<body>
    <h1>Search Grocery Items</h1>

    <form method="POST" action="">
        <label for="id">Search by ID:</label>
        <input type="number" id="id" name="id" placeholder="Enter ID">

        <label for="name">Search by Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter Name">

        <button type="submit" name="search">Search</button>
    </form>

    <?php if (!empty($search_results)) : ?>
        <h2>Search Results</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php foreach ($search_results as $item) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['price']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <input type="text" name="name" placeholder="New Name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                            <input type="number" step="0.01" name="price" placeholder="New Price" value="<?php echo htmlspecialchars($item['price']); ?>" required>
                            <input type="number" name="quantity" placeholder="New Quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
                            <button type="submit" name="action" value="update">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($search_results)) : ?>
        
    <?php endif; ?>

    <form method="POST" action="">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>