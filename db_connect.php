<?php
    $db_server="localhost";
    $db_user="root";
    $db_pass="";
    $db_name="online_grocery";

    try  {
        $conn=mysqli_connect($db_server,$db_user,$db_pass,$db_name);
    }
    catch(mysqli_sql_exception){
        echo "Could not connect to Database <br>";
    }


?>