<?php
// Connect to your database
require_once "../db.php";
$subjectCode = $_GET['subject_code'];

// Query the database to fetch the lecturer name based on the subject code
$query = "SELECT s.staff_name
          FROM Subject sub
          INNER JOIN Staff s ON sub.staff_id = s.staff_id
          WHERE sub.subj_code = '$subjectCode'";

$result = $conn->query($query);

if ($result === false) {
    // Handle the query error
    echo "Error executing the query: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['staff_name'];
    } else {
        echo "";
    }
}

// Close the database connection
$conn->close();