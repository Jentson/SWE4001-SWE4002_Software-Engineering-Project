<?php
require_once "../database/dbconnect.php";

// Initialize the search term
$searchTerm = "";

// Check if a search term is provided
if (isset($_GET['searchTerm'])) {
    $searchTerm = mysqli_real_escape_string($con, $_GET['searchTerm']);
}

// Modify the query to include the search term
$query = "SELECT student_subjects.student_subject_id, student.student_id, student.student_name, subject.subject_id, subject.subject_name, program.program_id, program.program_name, student_subjects.session, student_subjects.semester, student_subjects.section_id 
          FROM student_subjects 
          INNER JOIN student ON student_subjects.student_id = student.student_id 
          INNER JOIN subject ON student_subjects.subject_id = subject.subject_id 
          INNER JOIN program ON student_subjects.program_id = program.program_id";

if ($searchTerm !== "") {
    $query .= " WHERE student.student_id LIKE '%$searchTerm%' OR 
                student.student_name LIKE '%$searchTerm%' OR 
                subject.subject_id LIKE '%$searchTerm%' OR 
                subject.subject_name LIKE '%$searchTerm%' OR 
                student_subjects.session LIKE '%$searchTerm%' OR 
                student_subjects.semester LIKE '%$searchTerm%' OR 
                student_subjects.section_id LIKE '%$searchTerm%' OR
                program.program_id LIKE '%$searchTerm' OR
                program.program_name LIKE '%$searchTerm';";
}

$result = $con->query($query);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "Error fetching data: " . $con->error;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
