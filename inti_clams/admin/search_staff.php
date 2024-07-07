<?php
require_once "../database/dbconnect.php";

// Initialize the search term
$searchTerm = "";

// Check if a search term is provided
if (isset($_GET['searchTerm'])) {
    $searchTerm = mysqli_real_escape_string($con, $_GET['searchTerm']);
}

// Modify the query to include the search term
$query = "SELECT staff.*, position.position_name, department.department_name 
          FROM staff 
          INNER JOIN position ON staff.position_id = position.position_id
          INNER JOIN department ON staff.department_id = department.department_id";

if ($searchTerm !== "") {
    $query .= " WHERE staff.staff_name LIKE '%$searchTerm%' OR staff.staff_email LIKE '%$searchTerm%' OR department.department_name LIKE '%$searchTerm%' OR
        staff.staff_identity_number LIKE '%$searchTerm%' OR
        staff.staff_address LIKE '%$searchTerm%' OR
        staff.phone_number LIKE '%$searchTerm%' OR
        position.position_name LIKE '%$searchTerm' OR
        department.department_name LIKE '%$searchTerm' OR
        staff.gender LIKE '%$searchTerm';";
}

$result = mysqli_query($con, $query);

// Check if any rows are returned
if (mysqli_num_rows($result) > 0) {
    // Fetch all rows as an associative array
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $data = []; // Initialize an empty array if no data is found
}

// Return the data as a JSON response
echo json_encode($data);

// Close the database connection
mysqli_close($con);
?>
