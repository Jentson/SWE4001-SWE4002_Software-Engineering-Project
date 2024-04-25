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
<title>Login For Student</title>
</head>

<script>
    function validateForm() {
      var student_id = document.forms["StudentLogin"]["sid"].value;
      var student_pass = document.forms["StudentLogin"]["spass"].value;
      var errors = [];

    // Validation for Staff ID
      if (student_id == "") {
        errors.push("Please enter your Staff ID.");
      }else{
        if (!/^\d{6}/.test(student_id)) {
            errors.push("Please enter a valid Staff ID (6 digits).");
        }
      }

    //Validation for Password
    if (student_pass == ""){
        errors.push("Please enter your Password");
     } else {
        // At least one lowercase letter
        if (/[!@#$%^&*(),.?":{}|<>]/.test(student_pass)){
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
					<h2 class="p-3">Student Login</h2>
				  </div>
				  <div class="card-body">
					<form name="StaffLogin"  method ="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="needs-validation">
					  <div class="mb-4 form-group was-validated">
						<label class="form-label" for="sid">Student ID</label>
						<input class="form-control" type="text" name="sid" id="sid" required>
						<div class="invalid-feedback">
							Please enter your Student ID
						</div>
					  <div class="mb-4">
						<label class="form-label" for="Lpass">Password</label>
						<input class="form-control" type="password" name="spass" id="spass" required>
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
$conn = new mysqli('localhost', 'root', '', 'intiservice');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['Login'])) {
    $student_id = $_POST['sid'];
    $student_pass = $_POST['spass'];

    // Retrieve hashed password from the database
    $result = mysqli_query($conn, "SELECT * FROM student WHERE student_id = '$student_id'");
    $row = mysqli_fetch_array($result);

    if ($row && password_verify($student_pass, $row['student_pass'])) {
        // Login successful, store session variables
        echo "Login successful";
        $_SESSION['student_id'] = $student_id;
        $_SESSION['student_name'] = $row['student_name'];
        header("Location: ../student/student_dashboard.php");
        exit(); // Ensure script stops here to prevent further execution
    } else {
        // Login failed, show error message
        echo '<script>alert("Invalid Login Credentials");</script>';
    }
}

mysqli_close($conn);
?>

</body>
</html>
