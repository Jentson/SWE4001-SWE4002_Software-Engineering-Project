<?php
require_once "../database/dbconnect.php";

$searchTerm = isset($_GET['searchTerm']) ? $con->real_escape_string($_GET['searchTerm']) : '';

// Prepare the query with placeholders
$query = "SELECT leave_application.leave_id, leave_application.student_id, leave_application.student_name, 
                 leave_application.state, subject.subject_id, leave_application.start_date, leave_application.end_date, 
                 leave_application.reason, leave_application.documents, leave_application.lecturer_approval, 
                 leave_application.lecturer_remarks, leave_application.ioav_approval, leave_application.ioav_remarks, 
                 leave_application.hop_approval, leave_application.hop_remarks
          FROM leave_application
          JOIN subject ON leave_application.subject_id = subject.subject_id
          WHERE leave_application.is_deleted = 0";

// Add search term conditions if provided
if ($searchTerm !== "") {
    $query .= " AND (subject.subject_id LIKE ? OR 
                    leave_application.reason LIKE ? OR 
                    leave_application.student_name LIKE ? OR
                    leave_application.lecturer_remarks LIKE ? OR
                    leave_application.ioav_remarks LIKE ? OR
                    leave_application.hop_remarks LIKE ?)";
}

// Prepare and execute the statement
$stmt = $con->prepare($query);
if ($searchTerm !== "") {
    $searchTermLike = '%' . $searchTerm . '%';
    $stmt->bind_param('ssssss', $searchTermLike, $searchTermLike, $searchTermLike, $searchTermLike, $searchTermLike, $searchTermLike);
}
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo json_encode(["error" => "Error fetching data: " . $con->error]);
    exit();
}

header('Content-Type: application/json');
echo json_encode($data);
?>
