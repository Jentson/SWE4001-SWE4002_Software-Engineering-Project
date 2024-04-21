<?php
require_once "../db.php";
require "../Staff/StaffInfo.php";


session_start();

if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "LoginForStaff.html";</script>';
    session_destroy();
    exit();
}

$staff_id = $_SESSION['Staff_id'];
$staffInfo = getStaffInfo($conn, $staff_id);

echo "<h1>";
echo "Welcome " . $staffInfo['staff_name'];
echo "</h1>";
echo "<div class='col-md-12 bg-light text-right'>";
echo '<form action="" method="post">';
echo '<input type="submit" class="btn btn-outline-warning" name="logout" value="logout">';
echo '</form>';
echo "</div>";

// Check if the logout button has been pressed
if (isset($_POST['logout'])) {
    // Destroy the session
    session_unset();     // unset $_SESSION variable for the runtime
    session_destroy();   // destroy session data in storage
    header("Location: LoginForStaff.html");  // Redirect to login page
    exit();
}

$sql = mysqli_query($conn, "SELECT * FROM 
leave_application JOIN students ON leave_application.stud_id = students.stud_id
JOIN lecturer_approval ON leave_application.id = lecturer_approval.leave_id
JOIN ioav_approval ON ioav_approval.leave_id = lecturer_approval.leave_id
WHERE lecturer_approval.status = 1 AND ioav_approval.process = 0 AND students.state = 'International'");

// Retrieve leave applications count
$pending_count = 0;


// Iterate over the result set and fetch each row
while ($row = mysqli_fetch_assoc($sql)) {
    // Count leave application status
    $status = $row['hop_approval'];
    if ($status == 'Pending') {
        $pending_count++;
    } 
}

// Display leave application summary
echo "<br><br>Leave Application Summary:";
echo "<br>Pending: " . $pending_count;


echo "<div class ='table-responsive'>";
echo "<table class='table'>";
echo "<tr><th scope='col'>Leave ID</th>
<th scope='col'>Student ID</th>
<th scope='col'>Student Name</th>
<th scope='col'>Start Date</th>
<th scope='col'>End Date</th>
<th scope='col'>Files</th>
<th scope='col'>Subject Code</th>
<th scope='col'>Lecturer Approval</th>
<th scope='col'>Reason</th>" ;

mysqli_data_seek($sql, 0);
while ($row = mysqli_fetch_assoc($sql)) {
    echo "<tr>";
    echo "<td>" . $row['leave_id'] . "</td>";
    echo "<td>" . $row['stud_id'] . "</td>";
    echo "<td>" . $row['stud_name'] . "</td>";
    echo "<td>" . $row['startDate'] . "</td>";
    echo "<td>" . $row['endDate'] . "</td>";
    echo "<td><a href='../file/" . $row['documents'] . "' target='_blank'>View Supporting Documents</a></td>";
    echo "<td>" . $row['subj_code'] . "</td>";
    echo "<td>" . $row['lecturer_approval_status'] . "</td>";
    echo "<td>" . $row['reason'] . "</td>";
    echo "<td>";
    echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
    echo "<input type='hidden' name='leaveId' value='" . $row['leave_id'] . "'>";
    echo "<input type='submit' class='btn btn-outline-success' name='approve' value='Approve'>";
    echo "<input type='submit' class='btn btn-outline-danger' name='reject' value='Reject'>";
    echo "</form>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['reject'])) {
        $leaveId = $_POST['leaveId']; // Assuming you have an input field with name "leaveId" in the form
        $rejected_leave = "UPDATE leave_application 
        JOIN ioav_approval ON leave_application.id = ioav_approval.leave_id
        SET leave_application.ioav_approval = 'Rejected', 
            ioav_approval.process = 1
        WHERE leave_application.id = '$leaveId'";

        if (mysqli_query($conn, $rejected_leave)) {
            echo '<script>alert("Status changed to Rejected!");</script>';
        } else {
            echo "Error: " . $rejected . "<br>" . mysqli_error($conn);
        }

    } else if (isset($_POST['approve'])) {
        $leaveId = $_POST['leaveId']; // Assuming you have an input field with name "leaveId" in the form
        $approved_leave = "UPDATE leave_application 
        JOIN ioav_approval ON leave_application.id = ioav_approval.leave_id
        SET leave_application.ioav_approval = 'Approved', 
           ioav_approval.process = 1
        WHERE leave_application.id = '$leaveId'";

        if (mysqli_query($conn, $approved_leave)) {
            echo '<script>alert("Status changed to Approved!");</script>';
        } else {
            echo "Error: " . $approved . "<br>" . mysqli_error($conn);
        }
    }
    // Redirect after performing action
    echo '<script>window.location.href = "ioav_dashboard.php";</script>';
}
?>