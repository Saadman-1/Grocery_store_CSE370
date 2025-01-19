<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>
<body>
    
</body>
</html>
<?php
include("db_connect.php");
session_start();

if (isset($_POST['items'], $_POST['quantities']) && isset($_SESSION['customerid'])) {
    $selected_items = $_POST['items'];
    $quantities = $_POST['quantities']; 
    $customerid = $_SESSION['customerid'];

    echo "<h2>Your Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Item</th><th>Quantity</th><th>Price</th><th>Total Price</th></tr>";

    $total_bill = 0;

    foreach ($selected_items as $item) {
        $quantity = (int)$quantities[$item];

        $sql = "SELECT price FROM grocery_item WHERE name = '$item'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
            $total_price = $quantity * $price;

            $total_bill += $total_price;

            echo "<tr>";
            echo "<td>" . htmlspecialchars($item) . "</td>";
            echo "<td>" . htmlspecialchars($quantity) . "</td>";
            echo "<td>" . htmlspecialchars($price) . "</td>";
            echo "<td>" . htmlspecialchars($total_price) . "</td>";
            echo "</tr>";
        }
    }

    echo "<tr>";
    echo "<td colspan='3'><strong>Total Bill</strong></td>";
    echo "<td><strong>" . htmlspecialchars($total_bill) . "</strong></td>";
    echo "</tr>";
    echo "</table>";

    
    $sql2 = "INSERT INTO sales_history (total_amount, c_id) VALUES ('$total_bill', '$customerid')";
    $result2 = mysqli_query($conn, $sql2);

    echo "<h2>Thank you for your order!</h2>";
} else {
    echo "<h2>No items selected or session expired.</h2>";
}
?>
