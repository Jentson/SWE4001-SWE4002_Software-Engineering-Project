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
<title>Student View Of Leave Submitted</title>
</head>

<body>
	<header>
	<div class ="text-center">
		<img class ="img-fluid img-thumbnail" src ="images/pic5.png" alt ="INTI Logo"/>
	</div>
	</header>
<?php
	$conn = new mysqli('localhost', 'root' , '', 'ez4leave');
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	session_start();
	if (!isset($_SESSION['stud_id'])) 
	{
		echo '<script>alert("You need to login first!");</script>';
		echo '<script>window.location.href = "LoginForStudents.php";</script>';
		session_destroy();
		exit();
	}
	$studentId = $_SESSION['stud_id'];
	$stat = "SELECT stud_name FROM students WHERE stud_id = '$studentId'";
	$query = mysqli_query($conn, $stat);
	$name = mysqli_fetch_assoc($query);
	$Studname = $name['stud_name'];
	if($Studname) {
		
		echo "<h1>";
		echo "Welcome " .$Studname;
		echo "</h1>";
		
		echo "<div class='col-md-12 bg-light text-right'>";
		echo '<form action="LeaveMainPage.php" method="post">';
		echo '<input type="submit" class="btn btn-outline-warning" name="logout" value="logout">';
		echo '</form>';
		echo "<pre>";
		echo "</pre>";
		echo '<a href="LeaveFormStudent.php">';
		echo '<button class="btn btn-primary btn-xs">New</button>';
		echo '</a>';
		echo "</div>";
	
	echo "<div class ='table-responsive'>";
	echo "<table class ='table'>";
	$sql = "SELECT * FROM leave_application WHERE stud_id = '$studentId'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		echo "<tr>";
		echo "<th scope='col'>Leave Reference</th>";
		echo "<th scope='col'>Subject Code</th>";
		echo "<th scope='col'>Start Date</th>";
		echo "<th scope='col'>End Date</th>";
		echo "<th scope='col'>File/Evidence</th>";
		echo "<th scope='col'>Reason</th>";
		echo "<th scope='col'>Status</th>";
		echo "</tr>";
		while ($row = mysqli_fetch_assoc($result)) {
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
			echo "<td>" . $row['leave_ref'] . "</td>";
			echo "<td>" . $row['subj_code'] . "</td>";
			echo "<td>" . $row['startDate'] . "</td>";
			echo "<td>" . $row['endDate'] . "</td>";
			echo "<td><embed type='application/pdf' src='file/" . $row['files'] . "' width='350' height='300'></td>";
			echo "<td>" . $row['reason'] . "</td>";
			echo "<td>" . $row['status'] . "</td>";
			echo "</tr>";
		}
	}
	echo "</table>";
	echo "</div>";
	}
	mysqli_close($conn);
?>

</body>
</html>
