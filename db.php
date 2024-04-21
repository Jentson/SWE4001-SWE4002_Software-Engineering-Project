<?php

$host = 'localhost'; 
$username = 'user'; 
$password = ''; 
$database = 'INTI'; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
