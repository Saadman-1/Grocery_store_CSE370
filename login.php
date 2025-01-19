<?php
    include("db_connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<h2>Please enter the necessary information to log in</h2>
    <form action="" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 

    
    $sql = "SELECT * FROM customer WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password']; 

        
        if (password_verify($password, $hashed_password)) {
            session_start();
            $customerid = $row['id'];
            $_SESSION['customerid'] = $customerid;

            echo "Login successful! Welcome, " . htmlspecialchars($email);
            header('Location: user.php');
            exit();
        } else {
            echo "<p>Invalid email or password. Please try again.</p>";
        }
    } else {
        echo "<p>Invalid email or password. Please try again.</p>";
    }
}
?>
