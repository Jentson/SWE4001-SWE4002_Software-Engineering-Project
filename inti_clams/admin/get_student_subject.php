<?php
require_once '../database/db.php';

if(isset($_GET['student_id'])){
    $student_id = $_GET['student_id'];
    
    // Fetch subjects that the student has not already enrolled in
    $query = "SELECT subject_id, subject_name FROM subject WHERE subject_id NOT IN (SELECT subject_id FROM student_subjects WHERE student_id = $student_id)";
    $result = mysqli_query($con, $query);

    // Check if there are any subjects
    if (mysqli_num_rows($result) > 0) {
        // Loop through each subject and create an option element
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['subject_id'] . "'>" . $row['subject_name'] . "</option>";
        }
    } else {
        echo "<option value=''>No subjects available</option>";
    }
}
?>
