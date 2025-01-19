<?php
session_start();


if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    
    
</head>
<body>
    <h1>Welcome Admin!</h1>

    <div class="form-container">
        <form method="POST" action="">
            <div class="radio-group">
                <input type="radio" id="show grocery_item" name="action" value="show grocery_item" required>
                <label for="show grocery_item">Let's see all groceries.</label>
            </div>
            <div class="radio-group">
                <input type="radio" id="monthly_sales" name="action" value="monthly_sales" required>
                <label for="monthly_sales">Let's see monthly sales report.</label>
            </div>
            <div class="radio-group">
                <input type="radio" id="add" name="action" value="add" required>
                <label for="add">Let's add new item.</label>
            </div>
            <div class="radio-group">
                <input type="radio" id="update" name="action" value="update" required>
                <label for="update">Let's update item.</label>
            </div>
            <div class="radio-group">
                <input type="radio" id="delete" name="action" value="delete" required>
                <label for="delete">Let's delete item.</label>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action === 'show grocery_item') {
                header("Location: grocery_item.php");
                exit;
            } elseif ($action === 'add') {
                header("Location: admin_add.php");
                exit;
            } elseif ($action === 'update') {
                header("Location: admin_update.php");
                exit;
            } elseif ($action === 'delete') {
                header("Location: admin_delete.php");
                exit;
            } elseif ($action === 'monthly_sales') {
                header("Location: monthly_sales_report.php");
                exit;
            }
        }
    }
    ?>
</body>
</html>