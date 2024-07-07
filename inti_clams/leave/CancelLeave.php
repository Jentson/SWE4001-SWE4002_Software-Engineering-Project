<?php
require_once "../database/db.php";
require "../Staff/StaffInfo.php";
require "log_actions.php";
include '../validation/session.php'; 

ini_set('log_errors', 1);
ini_set('error_log', '../error.log');

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    header("Location: ../validation/login.php");
    exit;
}

$studentId =  $_SESSION['student_id'];

$studentInfo = mysqli_query($con,"SELECT *, student.student_address AS address FROM student 
JOIN program ON student.program_id = program.program_id 
JOIN department ON department.department_id = student.department_id
WHERE student.student_id = '$studentId'");
$studentResult = mysqli_fetch_assoc($studentInfo);

$isInternationalStudent = $studentResult['state'] == 'International';

$query = "SELECT * FROM leave_application
JOIN subject ON leave_application.subject_id = subject.subject_id
JOIN staff ON subject.staff_id = staff.staff_id
WHERE leave_application.student_id = ? AND leave_application.is_deleted = 0 
AND (
    leave_application.end_date >= CURDATE() 
    OR 
    (leave_application.start_date <= CURDATE() AND leave_application.end_date <= CURDATE() AND leave_application.hop_approval = 'Pending')
)
ORDER BY leave_application.leave_id ASC";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $studentId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$leaveApplications = mysqli_fetch_all($result, MYSQLI_ASSOC);

function getApprovalBgColor($status) {
    switch ($status) {
        case 'Rejected':
            return 'bg-danger'; // Set background color to red
        case 'Approved':
            return 'bg-success'; // Set background color to green
        case 'Pending':
        default:
            return 'bg-warning'; // Set background color to yellow
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $limit = 2; // Limit action 
    $interval = 30; // Every 60 minutes
    
    if (isset($_POST['cancel'])) {
        if (canPerformAction($con, $studentId, 'delete', $limit, $interval)) {
            $leaveId = $_POST['leave_id']; 
            // Delete leave application
            $cancel_leave = "UPDATE leave_application SET is_deleted = 1 WHERE leave_id = '$leaveId'";
            if (mysqli_query($con, $cancel_leave)) {
                // Log the delete action
                logAction($con, $studentId, 'delete', $leaveId);
                echo '<script>alert("Leave has been deleted!");</script>';
            } else {
                echo "Error updating leave_application table: " . mysqli_error($con);
            }
        } else {
            echo '<script>alert("You have reached the maximum number of leave deletions allowed within the time limit.");</script>';
        }
    } elseif (isset($_POST['edit_reason'])) {
        $leaveId = $_POST['leave_id'];
        $newReason = $_POST['new_reason'];
        // Update leave reason
        $update_reason = "UPDATE `leave_application` SET reason = ? WHERE leave_id = ?";
        $stmt = mysqli_prepare($con, $update_reason);
        mysqli_stmt_bind_param($stmt, "si", $newReason, $leaveId);
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Leave reason has been updated!");</script>';
        } else {
            echo "Error updating leave reason: " . mysqli_error($con);
        }
    }
    // Redirect after performing action
    echo '<script>window.location.href = "../leave/CancelLeave.php";</script>';
}

// Close the database connection

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cancel Leave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    .approval-status {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        color: white;
        font-weight: bold;
    }
    .approval-status.Approved {
        background-color: #28a745; 
    }
    .approval-status.Pending {
        background-color: #ffc107; 
    }
    .approval-status.Rejected {
        background-color: #dc3545; 
    }
    .approval-status.not-required {
        background-color: #6c757d;
    } 
    .remarks-box {
        background-color: #f2f2f2;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .no-remarks {
        color: #6c757d; 
    }
    </style>

  </head>

  
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
 <!-- Navbar -->
 <?php include '../index/student_navbar.php'; ?>
 <br>
 <div class="card">
    <h4 class="card-header text-center">Current Leave</h4>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>File/Evidence</th>
                        <th>Reason</th>
                        <th>Lecturer Name</th>
                        <th>Lecturer Status</th>
                        <th>Lecturer Remarks</th>
                        <?php if ($isInternationalStudent): ?>
                            <th>IOAV Approval</th>
                            <th>IOAV Remarks</th>
                        <?php endif; ?>
                        <th>HOP Status</th>
                        <th>HOP Remarks</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaveApplications as $application): ?>
                        <?php
                        // Check if any approvals are not pending for the current leave application
                        $approvalsNotPending = (
                            $application['lecturer_approval'] !== 'Pending' || 
                            $application['ioav_approval'] !== 'Pending' || 
                            $application['hop_approval'] !== 'Pending'
                        );

                        // Background colors for approval statuses
                        $bgColors = array(
                            'lecturer' => getApprovalBgColor($application['lecturer_approval']),
                            'ioav' => getApprovalBgColor($application['ioav_approval']),
                            'hop' => getApprovalBgColor($application['hop_approval'])
                        );
                        ?>

                        <tr scope="row">
                            <td><?php echo $application['subject_id']; ?></td>
                            <td><?php echo $application['start_date']; ?></td>
                            <td><?php echo $application['end_date']; ?></td>
                            <td>
                                <?php if (empty($application['documents'])): ?>
                                    -
                                <?php else: ?>
                                    <a href="../file/<?php echo htmlspecialchars($application['documents']); ?>" target="_blank">Supporting Documents</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo $application['reason']; ?>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="d-inline">
                                    <input type="hidden" name="leave_id" value="<?php echo $application['leave_id']; ?>">
                                    <input type="text" name="new_reason" class="form-control" required>
                                    <button type="submit" name="edit_reason" class="btn btn-sm btn-primary">Edit</button>
                                </form>
                                <td>
                                    <?php echo $application['staff_name']; ?>
                                </td>

                                <td>
                                    <div class="approval-status <?php echo $bgColors['lecturer']; ?>"><?php echo $application['lecturer_approval']; ?></div>
                                </td>
                                <td>
                                    <?php if (!empty($application['lecturer_remarks'])): ?>
                                        <div class="remarks-box"><?php echo $application['lecturer_approval']; ?></div>
                                    <?php else: ?>
                                        <div class="no-remarks">No Remarks</div>
                                    <?php endif; ?>
                                </td>
                                
            
                                <?php if ($isInternationalStudent): ?>
                                <td>
                                    <?php if ($application['ioav_approval'] === 'Not Required'): ?>
                                        <div class="approval-status not-required"><?php echo $application['ioav_approval']; ?></div>
                                    <?php elseif (!empty($application['ioav_approval'])): ?>
                                        <div class="approval-status <?php echo $bgColors['ioav']; ?>"><?php echo $application['ioav_approval']; ?></div>
                                    <?php else: ?>
                                        <div class="no-remarks">No Remarks</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($application['ioav_remarks'] === 'Not Required'): ?>
                                        <div class="remarks-box"><?php echo $application['ioav_remarks']; ?></div>
                                    <?php elseif (!empty($application['ioav_remarks'])): ?>
                                        <div class="remarks-box"><?php echo $application['ioav_remarks']; ?></div>
                                    <?php else: ?>
                                        <div class="no-remarks">No Remarks</div>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            <td>
                                <?php if ($application['hop_approval'] === 'Not Required'): ?>
                                    <div class="approval-status not-required"><?php echo $application['hop_approval']; ?></div>
                                <?php elseif (!empty($application['hop_approval'])): ?>
                                    <div class="approval-status <?php echo $bgColors['hop']; ?>"><?php echo $application['hop_approval']; ?></div>
                                <?php else: ?>
                                    <div class="no-remarks">No Remarks</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($application['hop_remarks'] === 'Not Required'): ?>
                                    <div class="remarks-box"><?php echo $application['hop_remarks']; ?></div>
                                <?php elseif (!empty($application['hop_remarks'])): ?>
                                    <div class="remarks-box"><?php echo $application['hop_remarks']; ?></div>
                                <?php else: ?>
                                    <div class="no-remarks">No Remarks</div>
                                <?php endif; ?>
                            </td>

                            <td>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="d-inline">
                                    <input type="hidden" name="leave_id" value="<?php echo $application['leave_id']; ?>">
                                    <button type="submit" name="cancel" class="btn btn-danger">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
