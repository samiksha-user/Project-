<?php

$host     = "localhost";   
$username = "root";        
$password = "";            
$database = "lost_and_found";     

$conn = new mysqli($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


?>

