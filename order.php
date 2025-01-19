<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function filterItems() {
            const searchValue = document.getElementById('searchBar').value.toLowerCase();
            const items = document.querySelectorAll('.item');

            items.forEach(item => {
                const itemName = item.querySelector('label').textContent.toLowerCase();
                if (itemName.includes(searchValue)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</head>
<body>
    <?php
    include("db_connect.php");
    session_start();

    if (isset($_SESSION['customerid'])) {
        $customerid = $_SESSION['customerid'];

        $sql = "SELECT name, price FROM grocery_item";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<h3>Menu</h3>";
            echo "<table border='1'>";
            echo "<tr><th>Name</th><th>Price</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            mysqli_data_seek($result, 0);

            
            echo "<h3>Select Item</h3>";
            echo "<input type='text' id='searchBar' placeholder='Search items...' onkeyup='filterItems()'><br><br>";

            echo "<form method='post' action='cart.php' id='orderForm'>"; 
            echo "<div id='itemList'>";

            while ($row = mysqli_fetch_assoc($result)) {
                $item_name = htmlspecialchars($row['name']);
                $item_price = htmlspecialchars($row['price']);
                echo "<div class='item'>";
                echo "<input type='checkbox' id='$item_name' name='items[]' value='$item_name'>";
                echo "<label for='$item_name'>$item_name</label>";
                echo "<br>Quantity for $item_name: <input type='number' name='quantities[$item_name]' min='1' max='10' value='1'>";
                echo "</div><br>";
            }

            echo "</div>"; 
            echo "<button type='submit'>Submit Order</button>";
            echo "</form>";
        }
    } else {
        echo "<p>Please log in to view the menu.</p>";
    }
    ?>
</body>
</html>



 