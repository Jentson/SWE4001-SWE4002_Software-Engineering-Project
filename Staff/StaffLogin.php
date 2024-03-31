<?php
	require "db.php";

	if (isset($_POST['Login'])) {
		$staff_id = $_POST['Staffid'];
		$staff_pass = $_POST['Staffpass'];
	
		$input = mysqli_query($conn, "SELECT * FROM staff WHERE Staff_ID = '$staff_id'");
		$row = mysqli_fetch_array($input);
		echo $staff_pass;
		if ($row && password_verify($staff_pass, $row['Staff_pass'])) {
			// Login successful, store session variables
			echo '<script>alert("Login successful");</script>';
			session_start();
			$_SESSION['Staff_id'] = $staff_id;
			$_SESSION['Staff_pwd'] = $row['Staff_name'];
			header("Location: StaffMain.php");
			exit();
		} else {
			// Login failed, show error message
			echo '<script>alert("Invalid Login Credentials"); history.go(-1) </script>';
			exit();
		}
	} else {
		echo '<script>alert("No user found"); history.go(-1)</script>';
		exit();
	}
	mysqli_close($conn);
?>


