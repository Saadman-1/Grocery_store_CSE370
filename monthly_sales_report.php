<?php
session_start();
if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit;
}

include("db_connect.php");

$sales_history = [];
$monthly_sales_report = null;
$search_month = null;
$search_year = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search'])) {
        $search_month = $_POST['month'] ?? '';
        $search_year = $_POST['year'] ?? '';

        if (!empty($search_month) && !empty($search_year)) {
            $stmt = $conn->prepare("SELECT SUM(total_amount) AS monthly_total FROM sales_history WHERE MONTH(date_time) = ? AND YEAR(date_time) = ?");
            $stmt->bind_param("ii", $search_month, $search_year);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $monthly_sales_report = $row['monthly_total'];
            }

            $stmt->close();
        }
    }
}

$result = $conn->query("SELECT * FROM sales_history");
$sales_history = $result->fetch_all(MYSQLI_ASSOC);
$result->free();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales History and Monthly Report</title>
    <link rel="stylesheet" href="styles2.css">
    
</head>
<body>
    <h1>Sales History</h1>

    <h2>Monthly Sales Report</h2>
    <form method="POST" action="">
        <label for="month">Month (1-12):</label>
        <input type="number" id="month" name="month" min="1" max="12" placeholder="Enter month" required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" placeholder="Enter year" required>

        <button type="submit" name="search">Search</button>
    </form>

    <?php if (!is_null($monthly_sales_report)) : ?>
        <h3>Sales Report for <?php echo htmlspecialchars($search_month) . "/" . htmlspecialchars($search_year); ?>:</h3>
        <h2>Total Sales Amount: TK <?php echo number_format($monthly_sales_report, 2); ?></h2>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
        <h2>No sales data found for the given month and year.</h2>
    <?php endif; ?>

    <h2>All Sales History</h2>
    <table>
        <tr>
            <th>Order Number</th>
            <th>Total Amount</th>
            <th>Date & Time</th>
            <th>Customer ID</th>
        </tr>
        <?php foreach ($sales_history as $row) : ?>
            <tr>
                <td><?php echo htmlspecialchars($row['order_no']); ?></td>
                <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                <td><?php echo htmlspecialchars($row['date_time']); ?></td>
                <td><?php echo htmlspecialchars($row['c_id']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <form method="POST" action="logout.php">
        <button type="submit">Logout</button>
    </form>
</body>
</html>