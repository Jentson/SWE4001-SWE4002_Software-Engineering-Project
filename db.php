<?php

$host = 'localhost'; 
$username = 'user'; 
$password = ''; 
$database = 'ez4leave'; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
