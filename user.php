<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
</head>
<body>    
</body>
</html>
<?php
    include("db_connect.php");
    session_start(); 

if (isset($_SESSION['customerid'])) {
    $customerid = $_SESSION['customerid'];

    $sql = "SELECT * FROM customer WHERE id = '$customerid'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);
    $name= $row['first_name'];
    
    echo "<h3> Welcome to our Website {$name} </h3>";
    echo "<h3>What would you like to do?</h3>";

    echo "<form method='post' action='user.php'>";

    echo "<input type='radio'id='order' name='is_user' value='order'>";
    echo "<label for='order'>Order</label><br>";

    echo "<input type='radio'id='history' name='is_user' value='history'>";
    echo "<label for='history'>Check Shoping History</label><br>";

    echo "<input type='radio'id='logout' name='is_user' value='logout'>";
    echo "<label for='logout'>Logout</label><br>";

    echo "<button type='submit'>Submit </button>";
    echo "</form>";

    if (isset($_POST['is_user'])) {
        $is_user = $_POST['is_user'];

        if ($is_user === 'order') {
            header('Location: order.php');
            exit();
        }

        elseif ($is_user === 'history') {
            header('Location: history.php');
            exit();
        }


        else if ($is_user === 'logout') {
            session_unset();
            session_destroy();
            header('Location: index.php');
            exit();
        }
    }

}
else {
    echo "<h3>Your session has expired please log in again.</h3>";
}

?>
    
