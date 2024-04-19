<?php
require_once "../db.php";
require "../Staff/StaffInfo.php";

session_start();

if (!isset($_SESSION['Staff_id'])) 
{
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "LoginForStaff.html";</script>';
	session_destroy();
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
		echo '<form action="LoginForStaff.html" method="post">';
        echo '<input type="submit" class="btn btn-outline-warning" id ="logout" name="logout" value="logout">';
        echo '</form>';
		echo "</div>";
        echo "<br>Course that you are teaching:<br>";
		echo "<ul class ='list-group'>";
        echo "<ul class ='list-group'>";
        foreach ($subject_codes as $code => $name) {
            echo "<li class ='list-group-item list-group-item-info'> $name</li>";
        }
        echo '</ul>';
		
    
        // Retrieve leave applications for the lecturer's subject
        $leave_applications = array();
        foreach($subject_codes as $subject_code){
            $sql = "SELECT * FROM leave_application WHERE subj_code = '$subject_code' AND status = 0"; 
			$result = mysqli_query($conn, $sql);
			$leave_applications[$subject_code] = mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

        // Count the number of pending, approved, and rejected leave applications
        $pending_count = 0;
        foreach ($leave_applications as $subject_code => $applications) {
        foreach ($applications as $application) {
        $status = $application['lecturer_approval_status'];
        if ($status == 'Pending') {
            $pending_count++;
        } 
      }
    }

    echo "<br><br>Leave Application Summary:";
    echo "<br>Pending: " . $pending_count;
    //Check whether there is leave application that need to be approve/reject
    if(mysqli_num_rows($result) > 0){
    foreach ($leave_applications as $subject_code => $applications) {
        echo "<br><br>Subject: $subject_code";
		
		echo "<div class ='table-responsive'>";
		
        echo "<table class ='table'>";
        echo "<tr><th scope='col'>Leave ID</th>
        <th scope='col'>Student Name</th>
        <th scope='col'>Student Name</th>
        <th scope='col'>Start Date</th>
        <th scope='col'>End Date</th>
        <th scope='col'>Files</th>
        <th scope='col'>Reason</th>
        <th scope='col'>Status</th></tr>";
        foreach ($applications as $application) {
			
			if($application['lecturer_approval_status'] == 'Pending')
			{
				echo "<tr scope='row' class = 'bg-warning'>";
			}
			elseif($application['lecturer_approval_status'] == 'Approved')
			{
				echo "<tr scope='row' class ='bg-success'>";
			}
			elseif($application['lecturer_approval_status'] == 'Rejected')
			{
				echo "<tr scope='row' class ='bg-danger'>";
			}
			else
			{
				echo "<tr scope='row'>";
			}
            
            echo "<td>" . $application['id'] . "</td>";
            echo "<td>" . $application['stud_id'] . "</td>";
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
        echo "</table>";
		echo "</div>";
      }
       } else{
        echo "<br><br>No leave application available for now";
      }

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['reject'])) {
            $leaveId = $_POST['leaveId']; // Assuming you have an input field with name "leaveId" in the form
            // Update leave_application table
            $rejected_leave = "UPDATE leave_application 
                   JOIN lecturer_approval ON leave_application.id = lecturer_approval.leave_id
                   SET leave_application.lecturer_approval_status = 'Rejected', 
                       leave_application.status = 1,
                       lecturer_approval.lect_status = 1
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
                            leave_application.status = 1,
                            lecturer_approval.lect_status = 1
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