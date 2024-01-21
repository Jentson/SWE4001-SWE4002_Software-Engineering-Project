<!DOCTYPE html>
<html lang ="en">
<!--Login page for lecturer-->

<head>
<meta charset="utf-8" />
<meta name ="description" content ="SWE20004" />
<meta name ="keywords" content ="HTML,CSS,Javascript" />
<meta name ="author" content ="Eazy 4 Leave" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link href ="styles/style.css" rel ="stylesheet" >
<link href ="styles/responsive.css" rel ="stylesheet" >
<link href ="styles/LoginForStaff.css" rel="stylesheet" >
<title>Login For Staff</title>
</head>

<script>
    function validateForm() {
      var staff_id = document.forms["StaffLogin"]["Lid"].value;
      var password = document.forms["StaffLogin"]["Lpass"].value;
      var errors = [];

    // Validation for Staff ID
      if (staff_id == "") {
        errors.push("Please enter your Staff ID.");
      }else{
        if (!/^\d{6}/.test(staff_id)) {
            errors.push("Please enter a valid Staff ID (6 digits).");
        }
      }

    //Validation for Password
    if (password == ""){
        errors.push("Please enter your Password");
     } else {
        // At least one lowercase letter
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)){
            errors.push("Special Characters are not allowed");
        }
    }
    // If no errors when filling the data, form can be submitted 
      if (errors.length > 0)
      {
        alert (errors.join("\n"));
        return false;
      }
      return true;
    }
  </script>

<body>
	<section class ="page2">
	<header>
	<div class ="text-center">
		<img class ="img-fluid img-thumbnail" src ="images/pic5.png" alt ="INTI Logo"/>
	</div>
	</header>
			
		<div class="container">
			<div class="row justify-content-center mt-5">
			  <div class="col-lg-4 col-md-6 col-sm-6">
				<div class="card shadow">
				  <div class="card-title text-center border-bottom">
					<h2 class="p-3">Staff Login</h2>
				  </div>
				  <div class="card-body">
					<form name="StaffLogin"  method ="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="needs-validation">
					  <div class="mb-4 form-group was-validated">
						<label class="form-label" for="Lid">Staff ID</label>
						<input class="form-control" type="text" name="Lid" id="Lid" required>
						<div class="invalid-feedback">
							Please enter your Staff ID
						</div>
					  <div class="mb-4">
						<label class="form-label" for="Lpass">Password</label>
						<input class="form-control" type="password" name="Lpass" id="Lpass" required>
						<div class="invalid-feedback">
							Please enter your password
						</div>
					  <div class="d-grid">
						<button type="submit" class="btn btn-primary" name="Login">Login</button>
					  </div>
					</form>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
	</section>

	<?php
	session_start();
	$conn = new mysqli('localhost', 'root', '', 'ez4leave');
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	if (isset($_POST['Login'])) {
		$staff_id = $_POST['Lid'];
		$staff_pass = $_POST['Lpass'];
	
		$input = mysqli_query($conn, "SELECT * FROM lecturer WHERE Lect_ID = '$staff_id' and Lect_pass = '$staff_pass'");
		$row = mysqli_fetch_array($input);
	
		if ($row && password_verify($staff_pass, $row['Lect_pass'])) {
			// Login successful, store session variables
			echo "Login successful";
			session_start();
			$_SESSION['lect_id'] = $staff_id;
			$_SESSION['lect_pwd'] = $row['Lect_name'];
			header("Location: StaffMain.php");
		} else {
			// Login failed, show error message
			echo '<script>alert("Invalid Login Credentials");</script>';
		}
	}
	mysqli_close($conn);
	?>

</body>
</html>
