<?php
session_start();

header('Content-Type: application/json');

$staff_id = isset($_GET['Staff_id']) ? $_GET['Staff_id'] : null;

if (!$staff_id) {
    echo json_encode(["error" => "No staff ID provided"]);
    exit;
}

$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "intiservice";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch all students who have taken subjects taught by the staff along with their leave counts
$sql = "
SELECT 
    ss.student_id,
    COUNT(CASE WHEN la.lecturer_approval = 'approved' AND la.hop_approval = 'approved' THEN 1 ELSE NULL END) AS approved_count,
    COUNT(CASE WHEN (la.lecturer_approval = 'pending' AND la.hop_approval <> 'rejected') 
    OR (la.hop_approval = 'pending' AND la.lecturer_approval <> 'rejected') THEN 1 ELSE NULL END) AS pending_count,
    COUNT(CASE WHEN la.lecturer_approval = 'rejected' OR la.hop_approval = 'rejected' THEN 1 ELSE NULL END) AS rejected_count
FROM 
    student_subjects ss
LEFT JOIN 
    leave_application la ON ss.student_id = la.student_id AND ss.subject_id = la.subject_id
JOIN 
    subject s ON ss.subject_id = s.subject_id
WHERE 
    s.staff_id = ?
GROUP BY 
    ss.student_id
ORDER BY 
    approved_count DESC, pending_count DESC, rejected_count DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "SQL preparation failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("s", $staff_id);
$stmt->execute();

$result = $stmt->get_result();

if (!$result) {
    echo json_encode(["error" => "Query execution failed: " . $stmt->error]);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();

echo json_encode($data);
?>
