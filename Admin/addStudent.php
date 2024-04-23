<?php
//Db connection
require_once '../db.php';

// Get the form data
if (isset($_POST['Add'])) {
    $stud_id = $_POST['stud_id'];
    $stud_name = $_POST['stud_name'];
    $stud_email = $_POST['stud_email'];
    $password = $_POST['stud_pass'];
    $session = $_POST['session'];
    $programme = $_POST['programme'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $semester = $_POST['semester'];
    $major= $_POST['major'];
    $department = $_POST['department'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an insert statement
    $query = "INSERT INTO students (stud_id, stud_name, stud_email, stud_pass, dept_ID, session, programme, address, phone, semester, major) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssisssiis", $stud_id, $stud_name, $stud_email, $hashed_password, $department, $session, $programme, 
    $address, $phone, $semester, $major);

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
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <script src= "../formValidation.js"></script>
</head>
<body>
	<header>
		<img class ="b" src ="../images/INTI.jpg" alt ="INTI Logo" />
		<h1>Adding Student</h1>
	</header>

    <form name ="addStudent" method="POST" action="" onsubmit="return validateAddStudent()"> 
    <p>
        <label for="staff_id">ID:  </label>
        <input type="text"  placeholder="1111"  name="stud_id" required/>	
    </p>

    <p>
        <label for="staff_name">Name:  </label>
        <input type="text"  placeholder="Bob" name="stud_name" required/>
    </p>

    <p>
        <label for="staff_email">Email:  </label>
        <input type="email"  placeholder="JXXXXXXX@student.newinti.edu.my" name="stud_email" required/>
    </p>

    <p>
        <label for="staff_pass">Password:  </label>
        <input type="password"  placeholder="Enter password" name="stud_pass" required/>
    </p>

    <p>
        <label for="address">Address:  </label>
        <input type="text"  placeholder="SS15/6A" name="address" required/>
    </p>

    <p>
        <label for="phone">Phone:  </label>
        <input type="text"  placeholder="60172608213" name="phone" required/>
    </p>

    <p>
        <label for="session">Session:  </label>
        <input type="text"  placeholder="FEB2024"  name="session" required/>	
    </p>

    <p>
        <label for="department">Department:</label>
        <select name="department" required>
            <option value="">Select Department</option>
            <option value="1">Swinburne University of Technology</option>
            <option value="2">University of Hertfordshire</option>
            <option value="3">Southern New Hampshire University</option>
            <option value="4">Center of Art and Design</option>
        </select>
    </p>

    <p>
        <label for="programme">Programme:  </label>
        <input type="text"  placeholder="BCSSUT" name="programme" required/>
    </p>

    <p>
        <label for="major">Major:  </label>
        <input type="text"  placeholder="CYBERSECURITY" name="major" required/>
    </p>

    <p>
        <label for="semester">Semester:  </label>
        <input type="text"  placeholder="1" name="semester" required/>
    </p>

    <p>	
        <input type ="submit" name="Add"/>
    </p>
    </form>
    </body>
</html>

