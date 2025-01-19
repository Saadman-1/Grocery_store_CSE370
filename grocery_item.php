<?php
session_start();

if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit;
}

include("db_connect.php");
$search_results = [];
$search_query = '';
$highlight_id = null;
$highlight_name = null;

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
            $highlight_id = $id;
        }

        if (!empty($name)) {
            $query .= " AND name LIKE ?";
            $params[] = "%$name%";
            $types .= 's';
            $highlight_name = $name;
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
}

$result = $conn->query("SELECT * FROM grocery_item");
$all_items = $result->fetch_all(MYSQLI_ASSOC);
$result->free();
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
            </tr>
            <?php foreach ($search_results as $item) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['price']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
        <p>No matching items found.</p>
    <?php endif; ?>

    <h2>All Grocery Items</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
        <?php foreach ($all_items as $item) : ?>
            <tr class="<?php 
                echo ($highlight_id == $item['id'] || 
                      (!empty($highlight_name) && stripos($item['name'], $highlight_name) !== false)) 
                      ? 'highlight' : ''; 
            ?>">
                <td><?php echo htmlspecialchars($item['id']); ?></td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['price']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <form method="POST" action="logout.php">
        <button type="submit">Logout</button>
    </form>
</body>
</html>