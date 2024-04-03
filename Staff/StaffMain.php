<!DOCTYPE html>
<html lang ="en">
<!--View Leave Submitted-->

<head>
<meta charset="utf-8" />
<meta name ="description" content ="SWE20004" />
<meta name ="keywords" content ="HTML,CSS,Javascript" />
<meta name ="author" content ="Eazy 4 Leave" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link href ="styles/style.css" rel ="stylesheet" >
<title>Lecturer View Of Leave Submitted</title>

</head>

<body>
<header>
	<div class ="text-center">
		<img class ="img-fluid img-thumbnail" src ="images/pic5.png" alt ="INTI Logo"/>
	</div>
	</header>
<?php

require_once "db.php";

session_start();

if (!isset($_SESSION['Staff_id'])) 
{
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "LoginForStaff.html";</script>';
	session_destroy();
    exit();
}

$staff_id = $_SESSION['Staff_id'];
$query = "SELECT Staff_name FROM Staff WHERE Staff_id = '$staff_id'";
$name = mysqli_query($conn, $query);


$sql = "SELECT * FROM Staff S INNER JOIN Roles R ON S.Role_id = R.Role_id WHERE S.Lect_id = '$staff_id'";
$result = mysqli_query($conn, $sql);
    
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $role = $row['Role_name'];
	$Lectname = $row['Staff_name'];

// Use the role for further processing or validation
    if ($role == "Lecturer") {
        // Retrieve the subject code of the logged-in lecturer
        $sql = "SELECT Subj_ID FROM subject WHERE lect_id = '$staff_id'";
        $result = mysqli_query($conn, $sql);
        $subject_codes = array();
        while ($row = mysqli_fetch_assoc($result)) {
              $subject_codes[] = $row['Subj_ID'];
        }

        echo "<h1>";
		echo "Welcome " .$Lectname;
		echo "</h1>";
		echo "<div class='col-md-12 bg-light text-right'>";
		echo '<form action="LeaveMainPage.php" method="post">';
        echo '<input type="submit" class="btn btn-outline-warning" id ="logout" name="logout" value="logout">';
        echo '</form>';
		echo "</div>";
        echo "<br>Course that you are teaching:<br>";
		echo "<ul class ='list-group'>";
		foreach ($subject_codes as $code) {
			echo "<li class ='list-group-item list-group-item-info'>" . $code . "</li>";
		}
		echo '</ul>';
		
        // Retrieve leave applications for the lecturer's subject
        $leave_applications = array();
        foreach($subject_codes as $subject_code){
			$sql = "SELECT * FROM leave_application WHERE subj_code = '$subject_code'";   
			$result = mysqli_query($conn, $sql);
			$leave_applications[$subject_code] = mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

        // Count the number of pending, approved, and rejected leave applications
        $pending_count = 0;
        $approved_count = 0;
        $rejected_count = 0;
    foreach ($leave_applications as $subject_code => $applications) {
        foreach ($applications as $application) {
        $status = $application['status'];
        if ($status == 'Pending') {
            $pending_count++;
        } elseif ($status == 'Approved') {
            $approved_count++;
        } elseif ($status == 'Rejected') {
            $rejected_count++;
        }
      }
    }

    echo "<br><br>Leave Application Summary:";
    echo "<br>Pending: " . $pending_count;
    echo "<br>Approved: " . $approved_count;
    echo "<br>Rejected: " . $rejected_count;
    //Check whether there is leave application that need to be approve/reject
    if(mysqli_num_rows($result) > 0){
    foreach ($leave_applications as $subject_code => $applications) {
        echo "<br><br>Subject: $subject_code";
		
		echo "<div class ='table-responsive'>";
		
        echo "<table class ='table'>";
        echo "<tr><th scope='col'>Leave ID</th><th scope='col'>Leave Ref</th><th scope='col'>Student ID</th><th scope='col'>Student Name</th><th scope='col'>Start Date</th><th scope='col'>End Date</th><th scope='col'>Files</th><th scope='col'>Reason</th><th scope='col'>Status</th></tr>";
        foreach ($applications as $application) {
			
			if($application['status'] == 'Pending')
			{
				echo "<tr scope='row' class = 'bg-warning'>";
			}
			elseif($application['status'] == 'Approved')
			{
				echo "<tr scope='row' class ='bg-success'>";
			}
			elseif($application['status'] == 'Rejected')
			{
				echo "<tr scope='row' class ='bg-danger'>";
			}
			else
			{
				echo "<tr scope='row'>";
			}
            
            echo "<td>" . $application['id'] . "</td>";
            echo "<td>" . $application['leave_ref'] . "</td>";
            echo "<td>" . $application['stud_id'] . "</td>";
            echo "<td>" . $application['stud_name'] . "</td>";
            echo "<td>" . $application['startDate'] . "</td>";
            echo "<td>" . $application['endDate'] . "</td>";
	    echo "<td><embed type='application/pdf' src='file/" . $application['files'] . "' width='500' height='300'></td>";
            echo "<td>" . $application['reason'] . "</td>";
            echo "<td>" . $application['status'] . "</td>";
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

    } elseif ($role == "Dean") {
        $sql = mysqli_query($conn, "SELECT * FROM leave_application");

        echo "<h1>";
		echo "Welcome " .$Lectname;
		echo "</h1>";
		echo "<h3>";
		echo "<br>You are Dean";
		echo "</h3>";
		echo "<div class='col-md-12 bg-light text-right'>";
		echo '<form action="LeaveMainPage.php" method="post">';
        echo '<input type="submit" class="btn btn-outline-warning" name="logout" value="logout">';
        echo '</form>';
		echo "</div>";

        // Retrieve leave applications count
        $pending_count = 0;
        $approved_count = 0;
        $rejected_count = 0;
        
        // Iterate over the result set and fetch each row
        while ($row = mysqli_fetch_assoc($sql)) {
            // Count leave application status
            $status = $row['status'];
            if ($status == 'Pending') {
                $pending_count++;
            } elseif ($status == 'Approved') {
                $approved_count++;
            } elseif ($status == 'Rejected') {
                $rejected_count++;
            }
        }
        
        // Display leave application summary
        echo "<br><br>Leave Application Summary:";
        echo "<br>Pending: " . $pending_count;
        echo "<br>Approved: " . $approved_count;
        echo "<br>Rejected: " . $rejected_count;
        
		echo "<div class ='table-responsive'>";
        echo "<table class='table'>";
        echo "<tr><th scope='col'>Leave ID</th><th scope='col'>Leave Ref</th><th scope='col'>Student ID</th><th scope='col'>Student Name</th><th scope='col'>Start Date</th><th scope='col'>End Date</th><th scope='col'>Files</th><th scope='col'>Reason</th><th scope='col'>Status</th>";
        // Reset the result set to display the table
        mysqli_data_seek($sql, 0);
        while ($row = mysqli_fetch_assoc($sql)) {
			
            if($row['status'] == 'Pending')
			{
				echo "<tr scope='row' class = 'bg-warning'>";
			}
			elseif($row['status'] == 'Approved')
			{
				echo "<tr scope='row' class ='bg-success'>";
			}
			elseif($row['status'] == 'Rejected')
			{
				echo "<tr scope='row' class ='bg-danger'>";
			}
			else
			{
				echo "<tr scope='row'>";
			}
			
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['leave_ref'] . "</td>";
            echo "<td>" . $row['stud_id'] . "</td>";
            echo "<td>" . $row['stud_name'] . "</td>";
            echo "<td>" . $row['startDate'] . "</td>";
            echo "<td>" . $row['endDate'] . "</td>";
	    echo "<td><embed type='application/pdf' src='file/" . $row['files'] . "' width='500' height='300'></td>";
            echo "<td>" . $row['reason'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
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

    } else {
       echo "You have no role";
    }
} else {
    echo "Error";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['reject'])) {
		$leaveId = $_POST['leaveId']; // Assuming you have an input field with name "leaveId" in the form
		$rejected = "UPDATE leave_application SET status = 'Rejected' WHERE id = '$leaveId'";
		if (mysqli_query($conn, $rejected)) {
			echo '<script>alert("Status changed to Rejected!");</script>';
		} else {
			echo "Error: " . $rejected . "<br>" . mysqli_error($conn);
		}
	} else if (isset($_POST['approve'])) {
		$leaveId = $_POST['leaveId']; // Assuming you have an input field with name "leaveId" in the form
		$approved = "UPDATE leave_application SET status = 'Approved' WHERE id = '$leaveId'";
		if (mysqli_query($conn, $approved)) {
			echo '<script>alert("Status changed to Approved!");</script>';
		} else {
			echo "Error: " . $approved . "<br>" . mysqli_error($conn);
		}
	}
}

?>

</body>
</html>
