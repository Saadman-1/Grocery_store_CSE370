<?php
    include("db_connect.php");
?>
<?php
$sql = "SELECT id, password FROM customer";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $plain_password = $row['password']; 
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    
    $update_sql = "UPDATE customer SET password = '$hashed_password' WHERE id = {$row['id']}";
    mysqli_query($conn, $update_sql);
}
?>