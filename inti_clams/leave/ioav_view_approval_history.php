<?php
require_once "../database/db.php";
include_once "../validation/session.php";

ini_set('log_errors', 1);
ini_set('error_log', '../error.log');

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../validation/login.php";</script>';
    exit();
}

// Get staff informations
$staffId = $_SESSION['Staff_id'];

// Get the leave applications
$studentId = isset($_GET['studentId']) ? $_GET['studentId'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

$query = "SELECT * FROM leave_application 
JOIN ioav_approval ON leave_application.leave_id = ioav_approval.leave_id
WHERE ioav_approval.ioav_id = ? AND ioav_approval.process = 1";

// Check if student ID is provided and add it to the query
if (!empty($studentId)) {
    $query .= " AND leave_application.student_id = ?";
}

// Check if both start and end dates are provided and add them to the query
if ($start_date && $end_date) {
    $query .= " AND leave_application.start_date >= ? AND leave_application.end_date <= ?";
}

// Validate date range
if ($start_date && $end_date && $end_date < $start_date) {
    // Display error message or take appropriate action
    echo '<script>alert("End date must be after start date");</script>';
    echo '<script>window.location.href = "ioav_view_approval_history.php";</script>';
    exit();
}

$stmt = mysqli_prepare($con, $query);

// Bind parameters based on the conditions
if (!empty($studentId) && $start_date && $end_date) {
    mysqli_stmt_bind_param($stmt, "ssss", $staffId, $studentId, $start_date, $end_date);
} elseif (!empty($studentId)) {
    mysqli_stmt_bind_param($stmt, "ss", $staffId, $studentId);
} elseif ($start_date && $end_date) {
    mysqli_stmt_bind_param($stmt, "sss", $staffId, $start_date, $end_date);
} else {
    mysqli_stmt_bind_param($stmt, "s", $staffId);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$leaveApplications = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
mysqli_stmt_close($stmt);

// Close the database conection
mysqli_close($con);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOP Approval History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">
<?php include '../index/staff_navbar.php'; ?>
    <div class="d-flex" id="wrapper">

        <!-- Page content-->

            <div class="container-fluid">
                <h4 class="mt-4 text-center">IOAV Approval History</h4>
                <hr>
                <div class="row mb-3">
                <div class="col-md-8">
                    <form method="GET" action="">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Search based on Student ID" name="studentId" value="<?php echo isset($_GET['studentId']) ? $_GET['studentId'] : ''; ?>">
                            <button class="btn btn-sm btn-outline-secondary" type="submit">Search</button>
                            <a href="../leave/hop_view_approval_history.php" class="btn btn-outline-danger">Reset</a>
                        </div>

                        <div class="input-group mb-2">
                            <input type="date" class="form-control" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" placeholder="Start Date">
                            <input type="date" class="form-control" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" placeholder="End Date">
                            <button class="btn btn-sm btn-outline-secondary" type="submit">Search</button>
                            <a href="../leave/hop_view_approval_history.php" class="btn btn-outline-danger">Reset</a>
                        </div>
                    </form>
                </div>
                
            </div>
            <form method="POST" action="">
                <div class="table-responsive">
                    <table class="table table-bordered text-center table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Student ID</th>
                                <th scope="col">Student Name</th>
                                <th scope="col">Subject Code</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">File/Evidence</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Lecturer Status</th>
                                <th scope="col">IOAV Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($leaveApplications)): ?>
                                <?php foreach ($leaveApplications as $application): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($application['leave_id']); ?></td>
                                        <td><?php echo htmlspecialchars($application['student_id']); ?></td>
                                        <td><?php echo htmlspecialchars($application['student_name']); ?></td>
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
                                        <td><?php echo htmlspecialchars($application['lecturer_approval']); ?></td>
                                        <td><?php echo htmlspecialchars($application['ioav_approval']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9">No leave applications found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-outline-primary" type="submit" name="export_selected_csv">Export Selected to CSV</button>
            </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.getElementsByName('selected_rows[]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
    </script>

</body>
</html>
