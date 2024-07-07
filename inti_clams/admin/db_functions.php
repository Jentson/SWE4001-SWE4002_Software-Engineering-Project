<?php
// db_functions.php

function connectDB() {
    $conn = new mysqli('localhost', 'root', '', 'intiservice');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Increase timeout settings for the connection
    $conn->query("SET SESSION wait_timeout = 28800");
    $conn->query("SET SESSION interactive_timeout = 28800");
    return $conn;
}
?>
