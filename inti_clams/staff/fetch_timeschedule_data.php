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

$sql = "
SELECT 
    schedule_date, start_time, end_time, book_avail, modal
FROM 
    staff_timeschedule 
WHERE 
    staff_id = ?";

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
