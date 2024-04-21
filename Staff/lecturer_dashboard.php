<?php
require_once "../db.php";
require "../Staff/StaffInfo.php";

session_start();

if (!isset($_SESSION['Staff_id'])) 
{
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "LoginForStaff.html";</script>';
    exit();
}

$staff_id = $_SESSION['Staff_id'];
$staffInfo = getStaffInfo($conn, $staff_id);

// Retrieve the subject code of the logged-in lecturer
$sql = "SELECT * FROM subject WHERE staff_id = '$staff_id'";
$result = mysqli_query($conn, $sql);
$subject_codes = array();
while ($row = mysqli_fetch_assoc($result)) {
    $subject_codes[] = $row['subj_code'];
}


echo "<h1>";
echo "Welcome " .$staffInfo['staff_name'];
echo "</h1>";
echo "<div class='col-md-12 bg-light text-right'>";
echo '<form action="" method="post">';
echo '<input type="submit" class="btn btn-outline-warning" id ="logout" name="logout" value="logout">';
echo '</form>';
echo "</div>";
echo "<br>Course(s) that you are teaching:<br>";
echo "<ul class ='list-group'>";
echo "<ul class ='list-group'>";
foreach ($subject_codes as $code => $name) {
        echo "<li class ='list-group-item list-group-item-info'> $name </li><br>";
    }
echo '</ul>';
		
// Check if the logout button has been pressed
if (isset($_POST['logout'])) {
    // Destroy the session
    session_unset();     // unset $_SESSION variable for the runtime
    session_destroy();   // destroy session data in storage
    header("Location: LoginForStaff.html");  // Redirect to login page
    exit();
}

// Retrieve leave applications for the lecturer's subjects
$leave_applications = array();
foreach ($subject_codes as $subject_code) {
    $sql = "SELECT leave_application.*, leave_application.lecturer_approval_status 
    AS lecturer_approval_status, leave_application.subj_code AS subject_code
            FROM leave_application
            JOIN lecturer_approval ON leave_application.id = lecturer_approval.leave_id
            WHERE leave_application.subj_code = ? AND lecturer_approval.status = 0";
    $stmt = mysqli_prepare($conn, $sql);
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
if (!empty($leave_applications)) {
    echo "<div class='table-responsive'>";
    echo "<table class='table'>";
    echo "<tr><th scope='col'>Leave ID</th>
            <th scope='col'>Subject Code</th>
            <th scope='col'>Student Name</th>
            <th scope='col'>Start Date</th>
            <th scope='col'>End Date</th>
            <th scope='col'>Files</th>
            <th scope='col'>Reason</th>
            <th scope='col'>Status</th></tr>";
    foreach ($leave_applications as $subject_code => $applications) {
        foreach ($applications as $application) {
            echo "<tr>";
            echo "<td>" . $application['id'] . "</td>";
            echo "<td>" . $application['subject_code'] . "</td>"; // Display subject code
            echo "<td>" . $application['stud_name'] . "</td>";
            echo "<td>" . $application['startDate'] . "</td>";
            echo "<td>" . $application['endDate'] . "</td>";
            echo "<td><a href='../file/" . $application['documents'] . "' target='_blank'>View Supporting Documents</a></td>";
            echo "<td>" . $application['reason'] . "</td>";
            echo "<td>" . $application['lecturer_approval_status'] . "</td>";
            echo "<td>";
            echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
            echo "<input type='hidden' name='leaveId' value='" . $application['id'] . "'>";
            echo "<input type='submit' class='btn btn-outline-success' name='approve' value='Approve'>";
            echo "<input type='submit' class='btn btn-outline-danger' name='reject' value='Reject'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "No leave application for now";
}

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['reject'])) {
            $leaveId = $_POST['leaveId']; // Assuming you have an input field with name "leaveId" in the form
            // Update leave_application table
            $rejected_leave = "UPDATE leave_application 
                   JOIN lecturer_approval ON leave_application.id = lecturer_approval.leave_id
                   SET leave_application.lecturer_approval_status = 'Rejected', 
                       lecturer_approval.status = 1
                   WHERE leave_application.id = '$leaveId'";
 
            if (mysqli_query($conn, $rejected_leave)) {
                echo '<script>alert("Status changed to Rejected!");</script>';
            } else {
                echo "Error updating leave_application table: " . mysqli_error($conn);
            }

        } 
        else if (isset($_POST['approve'])) {
            $leaveId = $_POST['leaveId']; // Assuming you have an input field with name "leaveId" in the form
            // Update leave_application table
            $approved_leave = "UPDATE leave_application 
                    JOIN lecturer_approval ON leave_application.id = lecturer_approval.leave_id
                     SET leave_application.lecturer_approval_status = 'Approved', 
                            lecturer_approval.status = 1
                        WHERE leave_application.id = '$leaveId'";
     
            if (mysqli_query($conn, $approved_leave)) {
                echo '<script>alert("Status changed to Approved!");</script>';
            } else {
                echo "Error updating leave_application table: " . mysqli_error($conn);
            }
        }
        // Redirect after performing action
        echo '<script>window.location.href = "lecturer_dashboard.php";</script>';
    }
    
?>