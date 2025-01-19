<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
</head>
<body>
    
</body>
</html>
<?php
    include("db_connect.php");
    session_start(); 

if (isset($_SESSION['customerid'])) {
    $customerid = $_SESSION['customerid'];

    $sql = "SELECT order_no, total_amount, date_time  FROM sales_history WHERE c_id = '$customerid'";
    $result = mysqli_query($conn, $sql);


    echo "<h2>Here is your shopping history</h2>";

    if ($result && mysqli_num_rows($result) > 0) {
        
        echo "<table border='1'>";
        echo "<tr><th>Order Number</th><th>Total Amount</th><th>Date & Time</th></tr>";

        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['order_no'] . "</td>";
            echo "<td>" . $row['total_amount'] . "</td>";
            echo "<td>" . $row['date_time'] . "</td>";
            echo "</tr>";
        }

        
        echo "</table>";
    } else {
        
        echo "<p>You have no shopping history.</p>";
    }
} else {
    
    echo "<h3>Please log in to view your shopping history.</h3>";
}
