<?php
require_once "../database/dbconnect.php";

// Check if the search term is set
if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];

    // Query to search for students by name, email, or department
    $query = "SELECT student.*, department.department_name, program.program_name, status.status_name
              FROM student 
              INNER JOIN department ON student.department_id = department.department_id
              INNER JOIN program ON student.program_id = program.program_id
              INNER JOIN status ON student.status_id = status.status_id
              WHERE student.student_name LIKE '%$searchTerm%'
              OR student.student_email LIKE '%$searchTerm%'
              OR department.department_name LIKE '%$searchTerm%';";

    $result = mysqli_query($con, $query);

    if ($result) {
        // Fetch all rows as an associative array
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        // Send JSON response
        echo json_encode($data);
    } else {
        echo "Error: " . mysqli_error($con);
    }

    // Close the database connection
    mysqli_close($con);
} else {
    // If the search term is not set, return an empty response
    echo json_encode([]);
}
?>
