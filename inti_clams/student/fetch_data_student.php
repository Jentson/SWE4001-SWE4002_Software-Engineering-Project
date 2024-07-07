<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', 'consultationsystem/php_error.log'); // Set the path to your PHP error log file

header('Content-Type: application/json');

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "intiservice";

// Get student ID from the URL parameter
$student_id = $_GET['student_id'];

if (!$student_id) {
    echo json_encode(["error" => "No student_id provided"]);
    exit;
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch subject data
$sql_subjects = "SELECT 
                    ss.subject_id,
                    COUNT(la.leave_id) AS total_leaves,
                    SUM(CASE WHEN la.lecturer_approval = 'approved' AND la.hop_approval = 'approved' THEN 1 ELSE 0 END) AS approved_leaves,
                    SUM(CASE WHEN (la.lecturer_approval = 'pending' AND la.hop_approval <> 'rejected') 
                              OR (la.hop_approval = 'pending' AND la.lecturer_approval <> 'rejected') THEN 1 ELSE 0 END) AS pending_leaves,
                    SUM(CASE WHEN la.lecturer_approval = 'rejected' OR la.hop_approval = 'rejected' THEN 1 ELSE 0 END) AS rejected_leaves
                FROM 
                    student_subjects ss
                LEFT JOIN 
                    leave_application la 
                ON 
                    ss.student_id = la.student_id AND ss.subject_id = la.subject_id
                WHERE 
                    ss.student_id = ?
                GROUP BY 
                    ss.subject_id";

$stmt_subjects = $conn->prepare($sql_subjects);
if (!$stmt_subjects) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt_subjects->bind_param("s", $student_id);
$stmt_subjects->execute();
$result_subjects = $stmt_subjects->get_result();

$subjects = array();
while ($row = $result_subjects->fetch_assoc()) {
    $subjects[] = $row;
}
$stmt_subjects->close();

// Fetch leave data
$sql_leaves = "SELECT 
                  start_date, end_date, lecturer_approval, hop_approval, subject_id
               FROM 
                  leave_application 
               WHERE 
                  student_id = ?";

$stmt_leaves = $conn->prepare($sql_leaves);
if (!$stmt_leaves) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt_leaves->bind_param("s", $student_id);
$stmt_leaves->execute();
$result_leaves = $stmt_leaves->get_result();

$leaves = array();
while ($row = $result_leaves->fetch_assoc()) {
    $leaves[] = $row;
}
$stmt_leaves->close();

// Close connection
$conn->close();

// Return data as JSON
echo json_encode(array('subjects' => $subjects, 'leaves' => $leaves));
?>
