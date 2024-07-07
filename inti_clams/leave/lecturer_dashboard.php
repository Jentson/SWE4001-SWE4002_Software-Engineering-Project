<?php
        include_once "../validation/session.php";
?>

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
include '../index/staff_navbar.php'; 

require_once "../database/db.php";
require "mail_config.php";

ini_set('log_errors', 1);
ini_set('error_log', '../error.log');

// Retrieve the subject code of the logged-in lecturer
$sql = "SELECT * FROM subject WHERE subject.staff_id = '" . $_SESSION['Staff_id'] . "' ";
$result = mysqli_query($con, $sql);
$subject_codes = array();
while ($row = mysqli_fetch_assoc($result)) {
    $subject_codes[] = $row['subject_id'];
}

// Retrieve leave applications for the lecturer's subjects
$leave_applications = array();
foreach ($subject_codes as $subject_code) {
    $sql = "SELECT leave_application.*, student.*,
    leave_application.state AS state,
    leave_application.leave_id AS leave_id,
    leave_application.lecturer_approval AS lecturer_approval, 
    leave_application.subject_id AS subject_code
    FROM leave_application
    JOIN student ON leave_application.student_id = student.student_id
    JOIN lecturer_approval ON leave_application.leave_id = lecturer_approval.leave_id
    WHERE leave_application.subject_id = ? 
    AND lecturer_approval.process = 0 
    AND leave_application.is_deleted = 0";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $subject_code);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        $leave_applications[$subject_code] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        echo "Error retrieving leave applications: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
}

// Display leave applications for each subject
    echo'
    <div class="container-fluid">
    <h4 class="text-center">Lecturer Pending Applications</h4><hr>';

    echo "<div class='table-responsive'>";
    echo "<table class='table text-center table-sm'>";
    echo "<thead class ='table-dark'>";
    echo "<tr>
            <th scope='col'>Subject Code</th>
            <th scope='col'>Student Name</th>
            <th scope='col'>Start Date</th>
            <th scope='col'>End Date</th>
            <th scope='col'>Files</th>
            <th scope='col'>Reason</th>
            <th scope='col'>Status</th>
            <th scope='col'></th>  
        </tr>";
    echo "</thead>";
    echo "<tbody class = table-group-divider>";

if (!empty($leave_applications)) {
        foreach ($leave_applications as $subject_code => $applications) {
                foreach ($applications as $application) {
                    echo "<tr>";
                    // echo "<td>" . $application['leave_id'] . "</td>";
                    echo "<td>" . $application['subject_id'] . "</td>"; 
                    echo "<td>" . $application['student_name'] . "</td>";
                    echo "<td>" . $application['start_date'] . "</td>";
                    echo "<td>" . $application['end_date'] . "</td>";
                    echo '<td>';
                    if (empty($application['documents'])) {
                        echo '-';
                    } else {
                        echo '<a href="../file/' . htmlspecialchars($application['documents']) . '" target="_blank">Supporting Documents</a>';
                    }
                    echo '</td>';                    
                    echo "<td>" . $application['reason'] . "</td>";
                    echo "<td>" . $application['lecturer_approval'] . "</td>";
                    echo "<td>";
                    echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
                    echo "<input type='hidden' name='leaveId' value='" . $application['leave_id'] . "'>";
                    echo "<input type='hidden' name='isInternational' value='" . $application['state'] . "'>";
                    echo "<input type='hidden' name='department' value='" . $application['department'] . "'>";

                    echo "<div class='form-group d-flex'>";
                    echo "<input type='text' id='remarks' name='remarks' class='form-control mr-2' placeholder='Remarks' oninput='validateDescription()' style='width:300px;'>";
                    echo "</div>";  
                                   
                    echo "<div class='d-flex'>";
                    echo "<input type='submit' id='approve_button' class='btn btn-sm btn-outline-success me-4 mt-2' name='approve' value='Approve'>";
                    echo "<input type='submit' id='reject_button' class='btn btn-sm btn-outline-danger me-1 mt-2' name='reject' value='Reject'>";
                    echo "</div>";
                    
                    echo "</div>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";    
                }
            }
        } else {
            echo "<tr><td colspan='9' class='text-center'>No leave applications for now</td></tr>";
        }

        echo "</tbody>"; 
        echo "</table>";
        echo "</div>";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leaveId = $_POST['leaveId']; 
    $remarks = $_POST['remarks'];
    $isInternational = $_POST['isInternational'];
    $department = $_POST['department'];

    if (isset($_POST['reject'])) {
        // Update leave_application table
        $rejected_leave = "UPDATE leave_application 
                           JOIN lecturer_approval ON leave_application.leave_id = lecturer_approval.leave_id
                           SET leave_application.lecturer_approval = 'Rejected', 
                                leave_application.hop_approval = 'Not Required',
                                leave_application.ioav_approval = 'Not Required',
                                lecturer_approval.process = 1, 
                                leave_application.lecturer_remarks = ?                      
                           WHERE leave_application.leave_id = ?";

        $stmt = mysqli_prepare($con, $rejected_leave);
        mysqli_stmt_bind_param($stmt, 'si', $remarks, $leaveId);
    
        if (mysqli_stmt_execute($stmt)) {

            // Assuming you have executed your SQL query and fetched the staff name into $row
            $sql = "SELECT * FROM staff 
            WHERE staff.staff_id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $_SESSION['Staff_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc(); 

            // Get an initialized mailer instance
            $mail = initializeMailer();
                    
            // Add recipient and set email content
            $mail->addAddress($application['student_email'], $application['student_name']);

            // Set email subject and content
            $mail->Subject = 'Leave Application Notification';
            $mail->Body = "
            <p><strong>Leave Application Notification</strong></p>
            <p>Dear {$application['student_name']},</p>
            <p><strong>{$row['staff_name']}</strong> has <strong>rejected</strong> your leave application.</p>
            <p><strong>Details:</strong></p>
            <ul>
                <li><strong>Subject:</strong> {$application['subject_id']}</li>
                <li><strong>Start Time:</strong> {$application['start_date']}</li>
                <li><strong>End Time:</strong> {$application['end_date']}</li>
                <li><strong>Remarks:</strong> {$application['lecturer_remarks']}</li>
            </ul>
            <p>Thank you.</p>
            <p>INTI International College Subang Consultation System</p>
            ";
           
            
            // Send the email
            if (!$mail->send()) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo '<script>alert("Leave Rejected and sent to the student!");</script>';
            }
            // echo '<script>alert("Sent to Student!");</script>';
            echo '<script>window.location.href = "lecturer_dashboard.php";</script>';
        } else {
            echo "Error updating leave_application table: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);  
          
    } else if (isset($_POST['approve'])) {
        // Update leave_application table
        $approved_leave = "UPDATE leave_application 
                           JOIN lecturer_approval ON leave_application.leave_id = lecturer_approval.leave_id
                           SET leave_application.lecturer_approval = 'Approved', 
                               lecturer_approval.process = 1, 
                               leave_application.lecturer_remarks = ?
                           WHERE leave_application.leave_id = ?";
        $stmt = mysqli_prepare($con, $approved_leave);
        mysqli_stmt_bind_param($stmt, 'si', $remarks, $leaveId);
        if (mysqli_stmt_execute($stmt)) 
        {
                    if ($isInternational == "International") {
                        // Send to International Staff
                        $int_staff_query = "SELECT * FROM staff WHERE position_id = 3"; 
                        $int_staff_result = mysqli_query($con, $int_staff_query);
                        $int_staff_row = mysqli_fetch_assoc($int_staff_result);
                        $int_staff_id = $int_staff_row['staff_id'];
                        $int_staff_name = $int_staff_row['staff_name'];
                        $int_staff_email = $int_staff_row['staff_email'];
                        
                        //Insert into IOAV_table
                        $ioav_application = "INSERT INTO ioav_approval (leave_id, ioav_id, process)
                        VALUES ('$leaveId', '$int_staff_id', 'Pending')";
                        $ioav_result = mysqli_query($con, $ioav_application);
                        
                        // Get an initialized mailer instance
                        $mail = initializeMailer();
                        
                         // Add recipient and set email content
                        $mail->addAddress($int_staff_email, $int_staff_name);
                        $mail->Subject = 'Leave Application Notification';

                        // Start with an initial message body
                        $mail->Body = "
                        <p><strong>Leave Application Notification</strong></p>
                        <p>Dear $int_staff_name,</p>
                        <p>Student <strong>{$application['student_name']}</strong> has applied for leave.</p>
                        <p><strong>Details:</strong></p>
                        <ul>
                            <li><strong>Subject ID:</strong> {$application['subject_id']}</li>
                            <li><strong>Start Date:</strong> {$application['start_date']}</li>
                            <li><strong>End Date:</strong> {$application['end_date']}</li>
                            <li><strong>Reason:</strong> {$application['reason']}</li>
                        </ul>
                        <p>Thank you.</p>
                        <p>INTI International College Subang Leave System</p>
                    ";
                
                                
                        // Send the email
                        if (!$mail->send()) {
                            echo 'Mailer Error: ' . $mail->ErrorInfo;
                        } else {
                            echo '<script>alert("Leave Approved and sent to International Student Staff!");</script>';
                        }
                        // echo '<script>alert("Sent to IOAV!");</script>';              
                    }
                    else {

                        $hop_id_query = "SELECT * FROM staff WHERE position_id = 1 AND department_id = $department";
                        $hop_id_result = mysqli_query($con, $hop_id_query);
                        $hop_id_row = mysqli_fetch_assoc($hop_id_result);
                        $hop_id = $hop_id_row['staff_id'];
                        $hop_name = $hop_id_row['staff_name'];
                        $hop_email = $hop_id_row['staff_email'];

                        //Insert into HOP_table
                        $hop_application = "INSERT INTO hop_approval (leave_id, hop_id, process)
                        VALUES ('$leaveId', '$hop_id', 'Pending')";
                        $hop_result = mysqli_query($con, $hop_application);
                        
                        // Get an initialized mailer instance
                        $mail = initializeMailer();
                        
                        // Add recipient and set email content
                        $mail->addAddress($hop_email, $hop_name);

                         // Start with an initial message body
                         $mail->Body = "
                         <p><strong>Leave Application Notification</strong></p>
                         <p>Dear $hop_name,</p>
                         <p>Student <strong>{$application['student_name']}</strong> has applied for leave.</p>
                         <p><strong>Details:</strong></p>
                         <ul>
                             <li><strong>Subject ID:</strong> {$application['subject_id']}</li>
                             <li><strong>Start Date:</strong> {$application['start_date']}</li>
                             <li><strong>End Date:</strong> {$application['end_date']}</li>
                             <li><strong>Reason:</strong> {$application['reason']}</li>
                         </ul>
                         <p>Thank you.</p>
                         <p>INTI International College Subang Leave System</p>
                     ";
                 
                        // Send the email
                        if (!$mail->send()) {
                            echo 'Mailer Error: ' . $mail->ErrorInfo;
                        } else {
                            echo '<script>alert("Leave Approved and sent to HOP!");</script>';
                        }
                        // echo '<script>alert("Sent to HOP!");</script>';
                    }
        } else {
            echo "Error updating leave_application table: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
    // Redirect after performing action
    echo '<script>window.location.href = "lecturer_dashboard.php";</script>';
    exit();
}
?>

<script>
    // Description validation
    function validateDescription() {
        var description = document.getElementById("remarks").value;
        var alphaNumRegex = /^[a-z\d\-_\s]+$/i;
        var descriptionError = document.getElementById('descriptionError');
        var approveButton = document.getElementById('approve_button');
        var rejectButton = document.getElementById('reject_button');

        if (!descriptionError) {
            descriptionError = document.createElement('div');
            descriptionError.id = 'descriptionError';
            descriptionError.style.color = 'red';
            var formGroup = document.querySelector('.form-group');
            formGroup.parentNode.insertBefore(descriptionError, formGroup.nextSibling);
        }

        if (description === "") {
            descriptionError.style.display = 'none';
            approveButton.disabled = false;
            rejectButton.disabled = false;
            return;
        }

        if (!alphaNumRegex.test(description)) {
            descriptionError.textContent = 'Description must be alphanumeric and should not contain any special characters.';
            approveButton.disabled = true;
            rejectButton.disabled = true;
            descriptionError.style.display = 'block';
            return false;
        } else {
            descriptionError.textContent = ''; // Clear the error message
            approveButton.disabled = false;
            rejectButton.disabled = false;
            descriptionError.style.display = 'none';
            return true;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var remarks = document.getElementById("remarks");
        if (remarks) {
            remarks.oninput = validateDescription;
        }
        // Initial call to disable the confirm button if textarea is empty
        validateDescription();
    });
</script>