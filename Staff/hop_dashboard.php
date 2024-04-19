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

$sql = mysqli_query($conn, "SELECT * FROM leave_application JOIN hop_approval 
ON leave_application.id = hop_approval.leave_id 
WHERE leave_application.status = 1 AND hop_approval.process = 0");

echo "<h1>";
echo "Welcome " . $staffInfo['staff_name'];
echo "</h1>";
echo "<div class='col-md-12 bg-light text-right'>";
echo '<form action="LoginForStaff.html" method="post">';
echo '<input type="submit" class="btn btn-outline-warning" name="logout" value="logout">';
echo '</form>';
echo "</div>";

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
<th scope='col'>Subject Names</th>
<th scope='col'>Reason</th>" ;


mysqli_data_seek($sql, 0);
while ($row = mysqli_fetch_assoc($sql)) {

    if ($row['hop_approval'] == 'Pending') {
        echo "<tr scope='row' class = 'bg-warning'>";
    } elseif ($row['hop_approval'] == 'Approved') {
        echo "<tr scope='row' class ='bg-success'>";
    } elseif ($row['hop_approval'] == 'Rejected') {
        echo "<tr scope='row' class ='bg-danger'>";
    } else {
        echo "<tr scope='row'>";
    }

    echo "<td>" . $row['leave_id'] . "</td>";
    echo "<td>" . $row['stud_id'] . "</td>";
    echo "<td>" . $row['stud_name'] . "</td>";
    echo "<td>" . $row['startDate'] . "</td>";
    echo "<td>" . $row['endDate'] . "</td>";
    echo "<td><a href='../file/" . $row['documents'] . "' target='_blank'>View Supporting Documents</a></td>";
    echo "<td>" . $row['subj_code'] . "</td>";
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
        $rejected = "UPDATE leave_application SET hop_approval = 'Rejected' WHERE id = '$leaveId'";
        if (mysqli_query($conn, $rejected)) {
            echo '<script>alert("Status changed to Rejected!");</script>';
        } else {
            echo "Error: " . $rejected . "<br>" . mysqli_error($conn);
        }

    } else if (isset($_POST['approve'])) {
        $leaveId = $_POST['leaveId']; // Assuming you have an input field with name "leaveId" in the form
        $approved = "UPDATE leave_application SET hop_approval = 'Approved' WHERE id = '$leaveId'";
        if (mysqli_query($conn, $approved)) {
            echo '<script>alert("Status changed to Approved!");</script>';
        } else {
            echo "Error: " . $approved . "<br>" . mysqli_error($conn);
        }
    }
    // Redirect after performing action
    echo '<script>window.location.href = "hop_dashboard.php";</script>';
}
?>
