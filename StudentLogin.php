<?php

require_once "../sanitizeInput.php";
require_once "../db.php";

if (isset($_POST['Login'])) {
    $student_id = sanitizeInput($_POST['Studentid']);
    $student_pass = sanitizeInput($_POST['Studentpass']);

    $input = mysqli_query($conn, "SELECT * FROM student WHERE student_id = '$student_id'");
    $row = mysqli_fetch_array($input);

    if ($row && password_verify($student_pass, $row['student_pass'])) {
        // Login successful redirect to apply leave page
        echo "Login successful";
        session_start();
        $_SESSION['stud_id'] = $student_id;
        header("Location: StudentMain.php");
    } else {
        // Login failed, show error message
        echo '<script>alert("Invalid Login Credentials"); window.location.href="LoginForStudent.html"</script>';
        exit();
    }
}
?>