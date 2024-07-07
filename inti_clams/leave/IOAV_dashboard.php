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

$staff_id = $_SESSION['Staff_id'];
$staffInfo = getStaffInfo($con, $staff_id);
$staff_name = $staffInfo['staff_name'];

$sql = mysqli_query($con, "SELECT *, leave_application.leave_id AS leave_id
    FROM leave_application 
    JOIN student ON leave_application.student_id = student.student_id
    JOIN lecturer_approval ON leave_application.leave_id = lecturer_approval.leave_id
    JOIN ioav_approval ON ioav_approval.leave_id = lecturer_approval.leave_id
    WHERE lecturer_approval.process = 1 
    AND leave_application.is_deleted = 0
    AND leave_application.lecturer_approval != 'Rejected' 
    AND ioav_approval.process = 0 
    AND student.state = 'International'"
);

$num_rows = mysqli_num_rows($sql);

    echo "<div class='table-responsive'>";
    echo "<table class='table'>";
    echo "<thead>";
    echo "<tr>

            <th scope='col'>Student ID</th>
            <th scope='col'>Student Name</th>
            <th scope='col'>Subject Code</th>
            <th scope='col'>Start Date</th>
            <th scope='col'>End Date</th>
            <th scope='col'>Files</th>
            <th scope='col'>Lecturer Approval</th>
            <th scope='col'>Reason</th>
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
        echo "<td>" . $row['reason'] . "</td>";
        echo "<td>";
        echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
        echo "<input type='hidden' name='leaveId' value='" . $row['leave_id'] . "'>";
        echo "<input type='hidden' name='department' value='" . $row['department'] . "'>";

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
    if (isset($_POST['reject'])) {
        $leaveId = $_POST['leaveId'];
        $remarks = $_POST['remarks'];
        $department = $_POST['department'];
    
        $approved_leave = "UPDATE leave_application 
        JOIN ioav_approval ON leave_application.leave_id = ioav_approval.leave_id
        SET leave_application.ioav_approval = 'Approved', 
            ioav_approval.process = 1,
            leave_application.ioav_remarks = ?
        WHERE leave_application.leave_id = ?";
        $stmt = mysqli_prepare($con, $approved_leave);
        mysqli_stmt_bind_param($stmt, 'si', $remarks, $leaveId);

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
            
            $stmt_select = mysqli_prepare($con, $select_query);
            mysqli_stmt_bind_param($stmt_select, 'i', $leaveId);
            mysqli_stmt_execute($stmt_select);
            $result = mysqli_stmt_get_result($stmt_select);
    
            $student = mysqli_fetch_assoc($result);
    
            if ($student) {
                // Initialize mailer
                $mail = initializeMailer();
    
                // Set email details
                $mail->addAddress($student['email'], $student['name']);
                $mail->Subject = 'Leave Application Notification';
                $mail->Body = "
                    <p><strong>Leave Application Notification</strong></p>
                    <p>Dear {$student['name']},</p>
                    <p><strong>{$staff_name}</strong> has <strong>rejected</strong> your leave application.</p>
                    <p><strong>Details:</strong></p>
                    <ul>
                        <li><strong>Subject:</strong> {$student['subject_id']}</li>
                        <li><strong>Start Date:</strong> {$student['start_date']}</li>
                        <li><strong>End Date:</strong> {$student['end_date']}</li>
                        <li><strong>Remarks:</strong> {$remarks}</li>
                    </ul>
                    <p>Thank you.</p>
                    <p>INTI International College Subang Consultation System</p>
                ";
    
                // Send email
                if (!$mail->send()) {
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo '<script>alert("Leave rejected! Email sent to the student.");</script>';
                }
            } else {
                echo "Error: Student information not found";
            }
        } else {
            echo "Error updating leave application and ioav_approval tables: " . mysqli_stmt_error($stmt_update);
        }    

    } else if (isset($_POST['approve'])) {
        $leaveId = $_POST['leaveId'];
        $remarks = $_POST['remarks'];
        $department = $_POST['department'];
        
        // Update leave_application and ioav_approval tables to mark as approved
        $approve_leave = "UPDATE leave_application 
                         JOIN ioav_approval ON leave_application.leave_id = ioav_approval.leave_id
                         SET leave_application.ioav_approval = 'Approved', 
                             ioav_approval.process = 1,
                             leave_application.ioav_remarks = ?
                         WHERE leave_application.leave_id = ?";
    
        $stmt_approve = mysqli_prepare($con, $approve_leave);
        mysqli_stmt_bind_param($stmt_approve, 'si', $remarks, $leaveId);
    
        if (mysqli_stmt_execute($stmt_approve)) {
            // Fetch HOP details
            $hop_id_query = "SELECT * FROM staff WHERE position_id = 1 AND department_id = ?";
            $stmt_hop = mysqli_prepare($con, $hop_id_query);
            mysqli_stmt_bind_param($stmt_hop, 'i', $department);
            mysqli_stmt_execute($stmt_hop);
            $hop_result = mysqli_stmt_get_result($stmt_hop);
            $hop_row = mysqli_fetch_assoc($hop_result);
    
            if ($hop_row) {
                $hop_id = $hop_row['staff_id'];
                $hop_name = $hop_row['staff_name'];
                $hop_email = $hop_row['staff_email'];
            } else {
                echo "Error: HOP details not found for department.";
                exit; // Stop further execution if HOP details are not found
            }
    
            // Insert into hop_approval table
            $hop_application = "INSERT INTO hop_approval (leave_id, hop_id, process)
                                VALUES (?, ?, 'Pending')";
            $stmt_hop_approval = mysqli_prepare($con, $hop_application);
            mysqli_stmt_bind_param($stmt_hop_approval, 'ii', $leaveId, $hop_id);
            $hop_insert_result = mysqli_stmt_execute($stmt_hop_approval);
    
            if ($hop_insert_result) {
                // Fetch student details based on leave ID
                $select_query = "SELECT student.student_email AS email,
                                        student.student_name AS name,
                                        leave_application.subject_id AS subject_id,
                                        leave_application.start_date AS start_date,
                                        leave_application.end_date AS end_date,
                                        leave_application.reason AS reason
                                 FROM student
                                 JOIN leave_application ON student.student_id = leave_application.student_id
                                 WHERE leave_application.leave_id = ?";
                
                $stmt_select = mysqli_prepare($con, $select_query);
                mysqli_stmt_bind_param($stmt_select, 'i', $leaveId);
                mysqli_stmt_execute($stmt_select);
                $result = mysqli_stmt_get_result($stmt_select);
                $student = mysqli_fetch_assoc($result);
    
                if ($student) {
                    // Initialize mailer
                    $mail = initializeMailer();
    
                    // Set email details
                    $mail->addAddress($hop_email, $hop_name);
                    $mail->Subject = 'Leave Application Notification';
                    $mail->Body = "
                        <p><strong>Leave Application Notification</strong></p>
                        <p>Dear {$hop_name},</p>
                        <p>Student <strong>{$student['name']}</strong> has applied for leave.</p>
                        <p><strong>Details:</strong></p>
                        <ul>
                            <li><strong>Subject ID:</strong> {$student['subject_id']}</li>
                            <li><strong>Start Date:</strong> {$student['start_date']}</li>
                            <li><strong>End Date:</strong> {$student['end_date']}</li>
                            <li><strong>Reason:</strong> {$student['reason']}</li>
                        </ul>
                        <p>Thank you.</p>
                        <p>INTI International College Subang Leave System</p>
                    ";
    
                    // Send email
                    if (!$mail->send()) {
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                    } else {
                        echo '<script>alert("Leave Approved! Email sent to HOP.");</script>';
                    }
                } else {
                    echo "Error: Student information not found for leave ID.";
                }
            } else {
                echo "Error inserting into hop_approval table: " . mysqli_error($con);
            }
    
            // Close statement handlers
            mysqli_stmt_close($stmt_hop);
            mysqli_stmt_close($stmt_hop_approval);
            mysqli_stmt_close($stmt_select);
        } else {
            echo "Error updating leave_application and ioav_approval tables: " . mysqli_stmt_error($stmt_approve);
        }
    
        // Close statement handler
        mysqli_stmt_close($stmt_approve);
    }
    
        // Redirect after performing action
        echo '<script>window.location.href = "ioav_dashboard.php";</script>';
        exit();
}
?>