<?php
require_once "../database/db.php";


$studentId = $_SESSION['student_id'];

// Fetch student state
$queryState = "SELECT state FROM student WHERE student_id = '$studentId'";
$resultState = mysqli_query($con, $queryState);
$studentState = mysqli_fetch_assoc($resultState)['state'];

// Fetch unread bookings for the logged-in student
$queryy = "SELECT DISTINCT schedule_date, status 
           FROM cancel_stafftime 
           WHERE student_id = '$studentId' 
             AND isRead = 0";
$resultt = mysqli_query($con, $queryy);
$unread_count = mysqli_num_rows($resultt);

// Fetch unread lecturer approvals/rejections for the logged-in student
$query2 = "SELECT la.leave_id, laa.start_date, laa.end_date, laa.student_name, laa.reason, laa.lecturer_approval
           FROM lecturer_approval AS la
           JOIN leave_application AS laa ON la.leave_id = laa.leave_id
           WHERE laa.student_id = '$studentId'
             AND la.process = 1
             AND la.studentIsRead = 0";
$result2 = mysqli_query($con, $query2);
$unread_count2 = mysqli_num_rows($result2);

// Fetch unread HOP approvals/rejections for the logged-in student
$query3 = "SELECT ha.leave_id, laa.start_date, laa.end_date, laa.student_name, laa.reason, laa.hop_approval
           FROM hop_approval AS ha
           JOIN leave_application AS laa ON ha.leave_id = laa.leave_id
           WHERE laa.student_id = '$studentId'
             AND ha.process = 1
             AND ha.studentIsRead = 0";
$result3 = mysqli_query($con, $query3);
$unread_count3 = mysqli_num_rows($result3);

// Fetch unread IOAV approvals/rejections for international students
$unread_count4 = 0;
if ($studentState == 'International') {
    $query4 = "SELECT ia.leave_id, laa.start_date, laa.end_date, laa.student_name, laa.reason, laa.ioav_approval
               FROM ioav_approval AS ia
               JOIN leave_application AS laa ON ia.leave_id = laa.leave_id
               WHERE laa.student_id = '$studentId'
                 AND ia.process = 1
                 AND ia.studentIsRead = 0";
    $result4 = mysqli_query($con, $query4);
    $unread_count4 = mysqli_num_rows($result4);
}

$total_unread_count = $unread_count + $unread_count2 + $unread_count3 + $unread_count4;

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
    <a href="../student/StudentMain.php">
            <img src="../images/Inti_X_IICS-CLAMS.png"class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
    </a>
    </div>
</div>

<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <!-- Navbar brand -->
        <a class="navbar-brand" href="../student/StudentMain.php">
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
                    <a class="nav-link active" aria-current="page" href="../student/StudentMain.php">Dashboard</a>
                </li>
                <!-- Leave dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Leave</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../leave/LeaveApplication.php">Apply Leave</a></li>
                        <li><a class="dropdown-item" href="../leave/CancelLeave.php">Cancel Leave</a></li>
                        <li><a class="dropdown-item" href="../leave/LeaveHistory.php">Leave History</a></li>
                    </ul>
                </li>
                <!-- Consultation dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Consultation</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../consultation/student_Make_Appointment.php">Make Appointment</a></li>
                        <li><a class="dropdown-item" href="../consultation/student_view_cancel_appointment.php">Cancel Appointment</a></li>
                        <li><a class="dropdown-item" href="../consultation/student_history_appointment.php">Appointment History</a></li>
                    </ul>
                </li>
            </ul>
            <!-- Profile & Logout buttons -->
            <div class="d-flex">
                <button id="notificationButton" type="button" class="btn btn-sm <?php echo ($total_unread_count > 0) ? 'btn-danger' : 'btn-info'; ?> 
            me-2 dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-bell"></i> (<?php echo $total_unread_count; ?>)
            </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <?php if ($total_unread_count > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($resultt)): ?>
                            <li>
                                <a class="dropdown-item" href="../consultation/student_history_appointment.php">
                                    <?php echo $row['schedule_date']; ?>
                                    <?php if ($row['status'] == 'cancel_by_staff'): ?>
                                        has been cancelled
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                        <?php while ($row = mysqli_fetch_assoc($result2)): ?>
                            <li>
                                <a class="dropdown-item" href="../leave/LeaveHistory.php">
                                    <!-- Leave ID: <?php echo $row['leave_id']; ?>  -->
                                    From <?php echo $row['start_date']; ?> to <?php echo $row['end_date']; ?>
                                    <?php if ($row['lecturer_approval'] == 'Approved'): ?>
                                        has been approved by Lecturer
                                    <?php elseif ($row['lecturer_approval'] == 'Rejected'): ?>
                                        has been rejected by Lecturer
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                        <?php while ($row = mysqli_fetch_assoc($result3)): ?>
                            <li>
                                <a class="dropdown-item" href="../leave/LeaveHistory.php">
                                    <!-- Leave ID: <?php echo $row['leave_id']; ?>  -->
                                    From <?php echo $row['start_date']; ?> to <?php echo $row['end_date']; ?>
                                    <?php if ($row['hop_approval'] == 'Approved'): ?>
                                        has been approved by HOP
                                    <?php elseif ($row['hop_approval'] == 'Rejected'): ?>
                                        has been rejected by HOP
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                        <?php if ($studentState == 'International'): ?>
                            <?php while ($row = mysqli_fetch_assoc($result4)): ?>
                                <li>
                                    <a class="dropdown-item" href="../leave/LeaveHistory.php">
                                        <!-- Leave ID: <?php echo $row['leave_id']; ?>  -->
                                        From <?php echo $row['start_date']; ?> to <?php echo $row['end_date']; ?>
                                        <?php if ($row['ioav_approval'] == 'Approved'): ?>
                                            has been approved by IOAV
                                        <?php elseif ($row['ioav_approval'] == 'Rejected'): ?>
                                            has been rejected by IOAV
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="#">No notifications</a></li>
                    <?php endif; ?>
                </ul>
                <a href="../student/StudentInfo.php" class="btn btn-sm btn-primary me-2">
                    <i class="fas fa-user"></i>
                </a>
                <a href="../validation/logout.php" class="btn btn-sm btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>
                    </body>
                    </html>
<script>
    document.getElementById("notificationButton").addEventListener("click", function() {
        // Send AJAX request to mark bookings as read
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../consultation/student_mark_cancel_as_read(noti).php", true);
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
        xhr2.open("POST", "../leave/student_mark_leave_as_read(noti).php", true);
        xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr2.send();
    });

    document.getElementById("notificationButton").addEventListener("click", function() {
        // Send AJAX request to mark HOP approvals as read
        var xhr3 = new XMLHttpRequest();
        xhr3.open("POST", "../leave/student_mark_hop_as_read(noti).php", true);
        xhr3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr3.send();
    });

    <?php if ($studentState == 'International'): ?>
    document.getElementById("notificationButton").addEventListener("click", function() {
        // Send AJAX request to mark IOAV approvals as read
        var xhr4 = new XMLHttpRequest();
        xhr4.open("POST", "../leave/student_mark_ioav_as_read(noti).php", true);
        xhr4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr4.send();
    });
    <?php endif; ?>
</script>
