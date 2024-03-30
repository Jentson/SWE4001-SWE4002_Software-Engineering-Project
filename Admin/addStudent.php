<!DOCTYPE html>
<html>

<head>
    <title>Add Student</title>
    <script src= "../formValidation.js"></script>
</head>
<body>
	<header>
		<img class ="b" src ="images/pic5.png" alt ="INTI Logo" />
		<h1>Adding Student</h1>
	</header>

    <form name ="addStudent" method="POST" action="" onsubmit="return validateAddStudent()"> 
    <p>
        <label for="staff_id">Student ID:  </label>
        <input type="text"  placeholder="1111"  name="stud_id" required/>	
    </p>

    <p>
        <label for="staff_name">Student Name:  </label>
        <input type="text"  placeholder="Bob" name="stud_name"/>
    </p>

    <p>
        <label for="staff_email">Student Email:  </label>
        <input type="email"  placeholder="abcd@student.newinti.edu.my" name="stud_email"/>
    </p>

    <p>
        <label for="staff_pass">Student Password:  </label>
        <input type="password"  placeholder="Enter password" name="stud_pass" required/>
    </p>

    <p>	
        <input type ="submit" name="Add"/>
    </p>
    </form>

<?php
//Db connection
require_once '../db.php';

// Get the form data
if (isset($_POST['Add'])) {
    $stud_id = $_POST['stud_id'];
    $stud_name = $_POST['stud_name'];
    $stud_email = $_POST['stud_email'];
    $password = $_POST['stud_pass'];

    echo $password;
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an insert statement
    $query = "INSERT INTO students (stud_id, stud_name, stud_email, stud_pass, dept_ID) VALUES (?, ?, ?, ?, '1')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $stud_id, $stud_name, $stud_email, $hashed_password);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo '<script>alert("Record inserted ' . $password. '"); window.location.href="addStudent.php";</script>';
    } else {
        echo '<script>alert("Unable to insert data: ' . $stmt->error . '"); window.history.go(-1);</script>';
    }

    // Close statement
    $stmt->close();
} else {
   echo '<script>alert("No Data inserted") window.history.go(-1);</script>'; 
}
?>
</body>
</html>