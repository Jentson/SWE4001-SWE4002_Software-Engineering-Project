<?php
require_once "../database/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = json_decode($_POST['ids'], true);

    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $con->prepare("DELETE FROM student_subjects WHERE student_subject_id IN ($placeholders)");
        $types = str_repeat('i', count($ids));
        $stmt->bind_param($types, ...$ids);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "No IDs provided for deletion.";
    }
}
?>
