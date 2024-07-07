<?php
require_once '../database/db.php';

if (!isset($_GET['student_id'])) {
    die('Student ID not provided');
}

$student_id = $_GET['student_id'];

// Fetch subjects that the student is already enrolled in
$query = "SELECT subject_id FROM student_subjects WHERE student_id = ?";
$stmt = $con->prepare($query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($con->error));
}
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$enrolledSubjects = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get all subjects
$query = "SELECT subject_id, subject_name FROM subject";
$result = mysqli_query($con, $query);
if ($result === false) {
    die('Error fetching subjects: ' . mysqli_error($con));
}
$subjects = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Filter subjects
$enrolledSubjectIds = array_column($enrolledSubjects, 'subject_id');
$filteredSubjects = array_filter($subjects, function($subject) use ($enrolledSubjectIds) {
    return !in_array($subject['subject_id'], $enrolledSubjectIds);
});

// Generate options for the subject dropdown
$options = '<option value="">Select a Subject</option>';
foreach ($filteredSubjects as $subject) {
    $options .= '<option value="' . htmlspecialchars($subject['subject_id']) . '">' . htmlspecialchars($subject['subject_name'] . ' - ' . $subject['subject_id']) . '</option>';
}

echo $options;
?>
