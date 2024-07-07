<?php
include '../staff/staffInfo.php';
include '../database/db.php';

$staffResult = getStaffInfo($con, $_SESSION['Staff_id']);

// Fetch unread bookings for the logged-in staff member
$query_staff = "SELECT DISTINCT schedule_date, status FROM student_bookings WHERE staff_id = '{$_SESSION['Staff_id']}' AND isRead = 0";
$result_staff = mysqli_query($con, $query_staff);
$unread_count = mysqli_num_rows($result_staff);

// Initialize the leave notifications query
$leavenotifications_query = "";

// Adjust the leave notifications query to include both lecturer and HOP roles for position_id 1
if ($staffResult['position_id'] == 1) { // HOP with lecturer access
    $leavenotifications_query = "
        SELECT la.leave_id, la.start_date, la.end_date, la.reason, la.student_id, 
               la.lecturer_approval AS approval_status, s.subject_name, 'lecturer' AS role
        FROM leave_application la 
        JOIN subject s ON la.subject_id = s.subject_id 
        JOIN lecturer_approval l ON la.leave_id = l.leave_id
        WHERE l.lecturer_id = '{$_SESSION['Staff_id']}' 
        AND l.staffIsRead = 0 
        AND l.process = 0 
        UNION
        SELECT la.leave_id, la.start_date, la.end_date, la.reason, la.student_id, 
               la.hop_approval AS approval_status, s.subject_name, 'hop' AS role
        FROM leave_application la 
        JOIN subject s ON la.subject_id = s.subject_id 
        JOIN hop_approval h ON la.leave_id = h.leave_id
        WHERE h.hop_id='{$_SESSION['Staff_id']}' 
        AND h.staffIsRead = 0 
        AND h.process = 0 
    ";
} elseif ($staffResult['position_id'] == 2) { // Lecturer
    $leavenotifications_query = "
        SELECT la.leave_id, la.start_date, la.end_date, la.reason, la.student_id, 
               la.lecturer_approval AS approval_status, s.subject_name, 'lecturer' AS role
        FROM leave_application la 
        JOIN subject s ON la.subject_id = s.subject_id 
        JOIN lecturer_approval l ON la.leave_id = l.leave_id
        WHERE l.lecturer_id = '{$_SESSION['Staff_id']}' 
        AND l.staffIsRead = 0 
        AND l.process = 0 
    ";
} elseif ($staffResult['position_id'] == 3) { // IOAV
    $leavenotifications_query = "
        SELECT la.leave_id, la.start_date, la.end_date, la.reason, la.student_id, 
               la.ioav_approval AS approval_status, s.subject_name, 'ioav' AS role
        FROM leave_application la 
        JOIN subject s ON la.subject_id = s.subject_id 
        JOIN ioav_approval ioav ON la.leave_id = ioav.leave_id
        WHERE ioav.ioav_id= '{$_SESSION['Staff_id']}' 
        AND ioav.StaffisRead = 0 
        AND ioav.process = 0 
    ";
}

$leavenotifications_result = mysqli_query($con, $leavenotifications_query);
$leave_unread_count = mysqli_num_rows($leavenotifications_result);

$total_unread_count_staff = $unread_count + $leave_unread_count;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Navbar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
</head>
<body>

<!-- Images -->
<div class="row justify-content-center">
    <div class="col-12">
    <a href="../staff/StaffMain.php">
        <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
    </a>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <!-- Navbar brand -->
        <a class="navbar-brand" href="../staff/StaffMain.php">
            <img src="../images/iics_clams_logo.png" alt="INTI" width="50" height="50">
        </a>

        <!-- Navbar toggler button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Navbar collapse content -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <!-- Navbar links -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../staff/StaffMain.php">Dashboard</a>
                </li>
                <!-- Leave dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">Leave</a>
                    <ul class="dropdown-menu">
                        <?php if ($staffResult['position_id'] == 1): // HOP with lecturer access ?>
                            <li><a class="dropdown-item" href="../leave/lecturer_dashboard.php">Lecturer Dashboard</a></li>
                            <li><a class="dropdown-item" href="../leave/hop_dashboard.php">HOP Dashboard</a></li>
                            <li><a class="dropdown-item" href="../leave/hop_view_approval_history.php">View Approval History </a></li>
                        <?php elseif ($staffResult['position_id'] == 2): ?>
                            <li><a class="dropdown-item" href="../leave/lecturer_dashboard.php">Lecturer Dashboard</a></li>
                            <li><a class="dropdown-item" href="../leave/lecturer_view_approval_history.php">View Approval History</a></li>
                        <?php elseif ($staffResult['position_id'] == 3): ?>
                            <li><a class="dropdown-item" href="../leave/ioav_dashboard.php">IOAV Dashboard</a></li>
                            <li><a class="dropdown-item" href="../leave/ioav_view_approval_history.php">View Approval History </a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <!-- Consultation dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown3" role="button" data-bs-toggle="dropdown" aria-expanded="false">Consultation</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../consultation/staff_addtime.php">Add Time Schedule</a></li>
                        <li><a class="dropdown-item" href="../consultation/staff_view_timeschedule.php">View Added Time Schedule</a></li>
                        <li><a class="dropdown-item" href="../consultation/staff_cancel_student_record.php">Student Status Bookings</a></li>
                        <li><a class="dropdown-item" href="../consultation/staff_history_appointment.php">Appointment History</a></li>
                        <li><a class="dropdown-item" href="../consultation/staff_history_cancel_record.php">Cancel Student Bookings History</a></li>
                    </ul>
                </li>
            </ul>
            <!-- Profile & Logout buttons -->
            <div class="d-flex">
            <button id="notificationButton" type="button" class="btn btn-sm <?php echo ($total_unread_count_staff > 0) ? 'btn-danger' : 'btn-info'; ?> 
            me-2 dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-bell"></i> (<?php echo $total_unread_count_staff; ?>)
            </button>

                <ul class="dropdown-menu dropdown-menu-end">
                <?php if ($total_unread_count_staff > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result_staff)): ?>
                        <li>
                            <a class="dropdown-item" href="../consultation/staff_history_appointment.php">
                                <?php echo $row['schedule_date']; ?>
                                <?php if ($row['status'] == 'booked'): ?>
                                    has been booked
                                <?php elseif ($row['status'] == 'cancelled'): ?>
                                    has been cancelled
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                    <?php while ($row = mysqli_fetch_assoc($leavenotifications_result)): ?>
                        <li>
                            <?php
                            $dashboard_url = "";
                            if ($row['role'] == 'lecturer') {
                                $dashboard_url = "../leave/lecturer_dashboard.php";
                            } elseif ($row['role'] == 'hop') {
                                $dashboard_url = "../leave/hop_dashboard.php";
                            }
                            elseif ($row['role'] == 'ioav') {
                                $dashboard_url = "../leave/IOAV_dashboard.php";
                            }
                            ?>
                            <a class="dropdown-item" href="<?php echo $dashboard_url; ?>">
                                Student <?php echo $row['student_id']; ?> - From <?php echo $row['start_date']; ?> to <?php echo $row['end_date']; ?>
                                <?php if ($row['approval_status'] == 'Pending'): ?>
                                    request take a leave.
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li><a class="dropdown-item" href="#">No notifications</a></li>
                <?php endif; ?>
                </ul>
                <a href="../staff/StaffProfile.php" class="btn btn-sm btn-primary me-2">
                    <i class="fas fa-user"></i>
                </a>
                <a href="../validation/logout.php" class="btn btn-sm btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>
</div>
</body>
</html>

<!-- Script to handle notification button click -->
<script>
    document.getElementById("notificationButton").addEventListener("click", function() {
        // Send AJAX request to mark bookings as read
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../consultation/staff_mark_bookings_as_read(noti).php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Change button color back to original
                var button = document.getElementById("notificationButton");
                button.classList.remove("btn-danger");
                button.classList.add("btn-info");
                // Update unread count to 0 and restore icon
                button.innerHTML = '<i class="fas fa-bell"></i> (0)';
            }
        };
        xhr.send();
    });
</script>

<script>
    document.getElementById("notificationButton").addEventListener("click", function() {
        // Send AJAX request to mark lecturer approvals as read
        var xhr2 = new XMLHttpRequest();
        xhr2.open("POST", "../leave/staff_mark_lecturer_as_read(noti).php", true);
        xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr2.send();
    });
</script>

<script>
    document.getElementById("notificationButton").addEventListener("click", function() {
        // Send AJAX request to mark HOP approvals as read
        var xhr3 = new XMLHttpRequest();
        xhr3.open("POST", "../leave/staff_mark_hop_as_read(noti).php", true);
        xhr3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr3.send();
    });
</script>

<script>
    document.getElementById("notificationButton").addEventListener("click", function() {
        // Send AJAX request to mark HOP approvals as read
        var xhr4 = new XMLHttpRequest();
        xhr4.open("POST", "../leave/staff_mark_ioav_as_read(noti).php", true);
        xhr4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr4.send();
    });
</script>