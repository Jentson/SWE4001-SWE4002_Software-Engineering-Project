<?php
	require_once "../db.php";

	if (isset($_POST['Login'])) {
		$staff_id = $_POST['Staffid'];
		$staff_pass = $_POST['Staffpass'];
	
		$input = mysqli_query($conn, "SELECT * FROM staff WHERE staff_id = '$staff_id'");
		$row = mysqli_fetch_array($input);

		if ($row && password_verify($staff_pass, $row['staff_pass'])) {
			// Login successful, store session variables
			echo "Login successful";
			session_start();
			$_SESSION['Staff_id'] = $staff_id;
			header("Location: StaffMain.php");
			exit();
		} else {
			// Login failed, show error message
			echo '<script>alert("Invalid Login Credentials"); history.go(-1) </script>';
			exit();
		}
	} else {
		echo '<script>alert("Please login");</script>';
		echo '<script>window.location.href = "LoginForStaff.html";</script>';
		exit();
	}
	mysqli_close($conn);
?>


