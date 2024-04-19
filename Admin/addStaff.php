<?php
require_once "../db.php";

// Get the form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$staff_id = $_POST['staff_id'];
$staff_name = $_POST['staff_name'];
$staff_email = $_POST['staff_email'];
$password = $_POST['staff_pass'];

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Add staff details into table
$query = "INSERT INTO staff (staff_id, staff_name, staff_email, staff_pass) VALUES (?,?,?,?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("isss", $staff_id, $staff_name, $staff_email, $hashed_password);

if ($stmt->execute()) {
    echo '<script>alert("Record inserted"); window.location.href="addStaff.php";</script>';
} else {
    echo '<script>alert("Unable to insert data: ' . $stmt->error . '"); window.location.href="addStaff.php";</script>';
}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Staff</title>
<script src="../formValidation.js"></script>
</head>
<body>
	<header>
		<img class ="b" src ="images/pic5.png" alt ="INTI Logo" />
		<h1>Adding Staff</h1>
	</header>

    <form name="addStaff" method="POST" action="" onsubmit="return validateAddStaff()"> 
    <p>
        <label for="staff_id">Staff ID:</label>
        <input type="text" placeholder="1111" name="staff_id" />
    </p>

    <p>
        <label for="staff_name">Staff Name:</label>
        <input type="text" placeholder="Bob" name="staff_name" />
    </p>

    <p>
        <label for="staff_email">Staff Email:</label>
        <input type="email" placeholder="abcd@gmail.com" name="staff_email" />
    </p>

    <p>
        <label for="staff_pass">Staff Password:</label>
        <input type="password" placeholder="Enter password" name="staff_pass" />
    </p>

    <p>	
        <input type="submit" name="Add" value="Add" />
    </p>
    </form>

    <a href="AdminMain.php">Return to Main Page</a>
</body>
</html>

