<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="styles.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
 <h2>Welcome to our webpage</h2>
    <form action="index.php" method="post">
        <label>Are you a new user?</label><br>
        <input type="radio" id="yes" name="new_user" value="yes">
        <label for="yes">Yes</label><br>
        <input type="radio" id="no" name="new_user" value="no">
        <label for="no">No</label><br><br><br><br><br>
        <button type="submit">Submit</button>
    </form>

    <h2>Are you an Admin?</h2>
    <form method="post" action="index.php">
        <input type="radio" id="yes" name="is_admin" value="yes">
        <label for="yes">Yes</label><br>
        <input type="radio" id="no" name="is_admin" value="no">
        <label for="no">No</label><br><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
<?php
if (isset($_POST['new_user'])) {
    $new_user = $_POST['new_user'];

    if ($new_user === 'yes') {
        header('Location: signup.php');
        exit();
    } elseif ($new_user === 'no') {
        header('Location: login.php');
        exit();
    }
} 

if (isset($_POST['is_admin']) && $_POST['is_admin'] === 'yes') {
    header('Location: adminlogin.php');
    exit(); }

?>
