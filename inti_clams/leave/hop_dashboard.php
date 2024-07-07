<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
<?php

include_once "../validation/session.php";
include '../index/staff_navbar.php';
include_once '../staff/StaffInfo.php';
require_once "../database/db.php";
require "mail_config.php";

ini_set('log_errors', 1);
ini_set('error_log', '../error.log');

$staff_id = $_SESSION['Staff_id'];
$staffInfo = getStaffInfo($con, $staff_id);
$department = $staffInfo['department_id'];
$staff_name = $staffInfo['staff_name'];

$sql = mysqli_query($con, 
    "SELECT *, 
            leave_application.leave_id AS leave_id, 
            student.student_email AS email,
            student.student_name AS name
    FROM leave_application
    JOIN hop_approval ON leave_application.leave_id = hop_approval.leave_id
    JOIN lecturer_approval ON hop_approval.leave_id = lecturer_approval.leave_id
    JOIN student ON leave_application.student_id = student.student_id
    JOIN staff ON staff.staff_id = hop_approval.hop_id
    LEFT JOIN ioav_approval ON leave_application.leave_id = ioav_approval.leave_id
    WHERE lecturer_approval.process = 1 
        AND hop_approval.process = 0
        AND leave_application.is_deleted = 0
        AND leave_application.lecturer_approval != 'Rejected'
        AND leave_application.department = '$department' 
        AND (
                (student.state = 'Local') 
                OR 
                (student.state = 'International' AND (ioav_approval.process = 1 OR ioav_approval.process IS NULL))
            )
");

$num_rows = mysqli_num_rows($sql);

echo'
        <div class="container-fluid">
        <h4 class="text-center">HOP Pending Applications</h4><hr>';

        echo "<div class='table-responsive'>";
        echo "<table class='table text-center table-sm'>";
        echo "<thead class ='table-dark'>";
        echo "<tr>
                <th scope='col'>Student ID</th>
                <th scope='col'>Student Name</th>
                <th scope='col'>Subject Code</th>
                <th scope='col'>Start Date</th>
                <th scope='col'>End Date</th>
                <th scope='col'>Documents</th>
                <th scope='col'>Lecturer Approval</th>
                <th scope='col'>IOAV Approval</th>
                <th scope='col'>Reason</th>  
                <th scope='col'></th>           
            </tr>";
        echo "</thead>";
        echo "<tbody class=table-group-divider>"; 

        if ($num_rows > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                echo "<tr>";
                // echo "<td>" . $row['leave_id'] . "</td>";
                echo "<td>" . $row['student_id'] . "</td>";
                echo "<td>" . $row['student_name'] . "</td>";
                echo "<td>" . $row['subject_id'] . "</td>";
                echo "<td>" . $row['start_date'] . "</td>";
                echo "<td>" . $row['end_date'] . "</td>";
                echo '<td>';
                if (empty($row['documents'])) {
                    echo '-';
                } else {
                    echo '<a href="../file/' . htmlspecialchars($row['documents']) . '" target="_blank">Supporting Documents</a>';
                }
                echo '</td>';    
                echo "<td>" . $row['lecturer_approval'] . "</td>";
                echo "<td>" . $row['ioav_approval'] . "</td>";
                echo "<td>" . $row['reason'] . "</td>";
                echo "<td>";
                echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
                echo "<input type='hidden' name='leaveId' value='" . $row['leave_id'] . "'>";

                echo "<div class='form-group d-flex'>";
                echo "<input type='text' name='remarks' class='form-control mr-2' placeholder='Remarks' style='width: 250px;'>";
                echo "</div>";
                
                echo "<div class='d-flex'>";
                echo "<input type='submit' class='btn btn-sm btn-outline-success me-4 mt-2' name='approve' value='Approve'>";
                echo "<input type='submit' class='btn btn-sm btn-outline-danger me-1 mt-2' name='reject' value='Reject'>";
                echo "</div>";

                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10' class='text-center'>No leave applications for now</td></tr>";
        }

        echo "</tbody>"; 
        echo "</table>";
        echo "</div>";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['reject']) || isset($_POST['approve'])) {
        $leaveId = $_POST['leaveId']; 
        $remarks = $_POST['remarks'];
        
        // Prepare the SQL statement to update leave_application table
        if (isset($_POST['reject'])) {
            $approval_status = 'Rejected';
        } else {
            $approval_status = 'Approved';
        }

        $update_query = "UPDATE leave_application 
                         JOIN hop_approval ON leave_application.leave_id = hop_approval.leave_id
                         SET leave_application.hop_approval = ?, 
                             hop_approval.process = 1,
                             leave_application.hop_remarks = ?
                         WHERE leave_application.leave_id = ?";

        // Prepare and bind parameters for the query
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, 'ssi', $approval_status, $remarks, $leaveId);
        
        // Execute the query
        if (mysqli_stmt_execute($stmt)) {            
            // Fetch student email and name based on leave ID
            $select_query = "SELECT student.student_email AS email,
                                     student.student_name AS name,
                                     leave_application.subject_id AS subject_id,
                                     leave_application.start_date AS start_date,
                                     leave_application.end_date AS end_date,
                                     leave_application.hop_remarks AS hop_remarks
                              FROM student
                              JOIN leave_application ON student.student_id = leave_application.student_id
                              WHERE leave_application.leave_id = ?";
            $stmt = mysqli_prepare($con, $select_query);
            mysqli_stmt_bind_param($stmt, 'i', $leaveId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $student = mysqli_fetch_assoc($result);

            if ($student) {
                // Initialize mailer and send email
                $mail = initializeMailer();

                $mail->addAddress($student['email'], $student['name']);
                // Set email subject and content
                $mail->Subject = 'Leave Application Notification';
                $mail->Body = "
                <p><strong>Leave Application Notification</strong></p>
                <p>Dear {$student['name']},</p>
                <p><strong>$staff_name</strong> has <strong> $approval_status</strong> your leave application.</p>
                <p><strong>Details:</strong></p>
                <ul>
                    <li><strong>Subject:</strong> {$student['subject_id']}</li>
                    <li><strong>Start Time:</strong> {$student['start_date']}</li>
                    <li><strong>End Time:</strong> {$student['end_date']}</li>
                    <li><strong>Remarks:</strong> {$student['hop_remarks']}</li>
                </ul>
                <p>Thank you.</p>
                <p>INTI International College Subang Consultation System</p>
                ";
                
                if (!$mail->send()) {
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo '<script>alert("Leave ' . $approval_status . '! Email sent to the student.");</script>';
                }
            } else {
                echo "Error: Student information not found";
            }
        } else {
            echo "Error updating leave_application table: " . mysqli_error($con);
        }
        // Redirect after performing action
        echo '<script>window.location.href = "hop_dashboard.php";</script>';
        exit();
    }
}


?>