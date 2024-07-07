<?php
require_once "../database/db.php";
include_once "../validation/session.php";

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    header("Location: ../validation/login.php");
    exit;
}

$studentId = $_SESSION['student_id'];

$studentInfo = mysqli_query($con,"SELECT * FROM student WHERE student_id = '$studentId'");
$studentResult = mysqli_fetch_assoc($studentInfo);

// Get the student_id from the session
$student_id = $_SESSION['student_id'];

// Check if start_date and end_date are provided
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

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

// Validate date range
if ($start_date && $end_date && $end_date < $start_date) {
    // Display error message or take appropriate action
    echo '<script>alert("End date must be after start date");</script>';
    echo '<script>window.location.href = "LeaveHistory.php";</script>';
    exit();
}

$query = "SELECT * FROM leave_application WHERE student_id =? 
AND (
    hop_approval!= 'Pending' AND end_date < CURDATE()
    OR (is_deleted = 1)
)";

// Append date range conditions if both dates are provided
if ($start_date && $end_date) {
    $query .= " AND start_date >= ? AND end_date <= ?";
}

// Prepare the statement
$stmt = $con->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $con->error);
}

// Bind parameters based on the conditions
if ($start_date && $end_date) {
    $stmt->bind_param("sss", $student_id, $start_date, $end_date);
} else {
    $stmt->bind_param("s", $student_id);
}

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch all rows as an associative array
$leaveApplications = $result->fetch_all(MYSQLI_ASSOC);

// Free result and close the statement
$result->free();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Leave History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
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

 <div id="page-content-wrapper">
            <div class="container-fluid">
                <h4 class ="text-center">Leave History</h4>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" action="">
                            <div class="input-group">
                                <input type="date" class="form-control" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" placeholder="Start Date">
                                <input type="date" class="form-control" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" placeholder="End Date" max="<?php echo date('Y-m-d'); ?>">
                                <button class="btn btn-outline-secondary" type="submit">Search</button>
                                <a href="LeaveHistory.php" class="btn btn-outline-danger">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Subject Code</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">File/Evidence</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Lecturer Status</th>
                                <th scope="col">Lecturer Remarks</th>
                                <?php if ($studentResult['state'] == "International"): ?>
                                    <th scope="col">IOAV Approval</th>
                                    <th scope="col">IOAV Remarks</th>
                                <?php endif; ?>
                                <th scope="col">HOP Status</th>
                                <th scope="col">HOP Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($leaveApplications)): ?>
                                <?php foreach ($leaveApplications as $application): ?>
                                    <?php
                                        // Background colors for approval statuses
                                        $bgColors = array(
                                            'lecturer' => getApprovalBgColor($application['lecturer_approval']),
                                            'ioav' => getApprovalBgColor($application['ioav_approval']),
                                            'hop' => getApprovalBgColor($application['hop_approval'])
                                        );
                                        ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($application['subject_id']); ?></td>
                                        <td><?php echo htmlspecialchars($application['start_date']); ?></td>
                                        <td><?php echo htmlspecialchars($application['end_date']); ?></td>
                                        <td>
                                                <?php
                                                if (empty($application['documents'])) {
                                                    echo '-';
                                                } else {
                                                    echo '<a href="../file/' . htmlspecialchars($application['documents']) . '" target="_blank">Supporting Documents</a>';
                                                }
                                                ?>
                                            </td>
                                        <td><?php echo htmlspecialchars($application['reason']); ?></td>
                                        
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
                                        
                                <?php if ($studentResult['state'] == "International"): ?>
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
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10">No leave applications found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
