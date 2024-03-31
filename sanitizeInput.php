<?php
function sanitizeInput($data) {
    $data = trim($data); // Remove unnecessary characters (extra space, tab, newline)
    $data = stripslashes($data); // Remove backslashes (\)
    $data = htmlspecialchars($data); // Convert special characters to HTML entities
    return $data;
}
?>