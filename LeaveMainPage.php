<!DOCTYPE html>
<html lang ="en">
<!--Page after ecampus-->

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
<title>Main Page To Login</title>
</head>

<body>
	<header>
	<div class ="text-center">
		<img class ="img-fluid img-thumbnail" src ="images/pic5.png" alt ="INTI Logo"/>
	</div>
	</header>

	<div class ="text-center">
		<img class ="img-fluid img-thumbnail" src ="images/pic1.png" alt ="Filling Leave Application Form" />
	</div>
	<div class="text-center">	
		<a href="LoginForStudents.php">
			<button class="btn btn-primary btn-lg">Student Login</button>
		</a>
		<a href="LoginForStaff.php">
			<button class="btn btn-primary btn-lg">Staff Login</button>
		</a>
	</div>

<?php 
	session_start();

	if (isset($_POST['logout'])) 
	{		//if user already login, destroy the stored session with all log in data.
		session_destroy();
	}
 ?>

</body>
</html>
