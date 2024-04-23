<?php
//Db connection
require_once '../db.php';

// Get the form data
if (isset($_POST['Add'])) {
    $subj_code = $_POST['subj_code'];
    $subj_name = $_POST['subj_name'];
    $lect_id = $_POST['lect_id'];
    $Sess_ID = $_POST['Sess_ID'];
    $LDay = $_POST['LDay'];
    $TDays = $_POST['TDays'];

    // Prepare an insert statement
    $query = "INSERT INTO subject (subj_code, subj_name, lect_id, Sess_ID, LDay, TDays) 
    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiiss", $subj_code, $subj_name, $lect_id, $Sess_ID, $LDay, $TDays);

    // Execute the prepared statement
    if ($stmt->execute()) {
    echo '<script>alert("Record inserted"); window.location.href="addSubject.php";</script>';
    } 
    else {
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
    <title>Add Subject</title>
    <script src= "../formValidation.js"></script>
</head>
<body>
	<header>
		<img class ="b" src ="../images/INTI.jpg" alt ="INTI Logo" />
		<h1>Adding Subject</h1>
	</header>

    <form name ="addSubject" method="POST" action="" onsubmit="return validateAddSubject()">
    <p>
        <label for="subj_code">Subject ID:  </label>
        <input type="text"  placeholder="abc12345"  name="subj_code" required/>	
    </p>

    <p>
        <label for="subj_name">Subject Name:  </label>
        <input type="text"  placeholder="Intro to programming" name="subj_name" required/>
    </p>

    <p>
        <label for="lect_id">Lecturer ID:  </label>
        <input type="text"  placeholder="1234567" name="lect_id" required/>
    </p>

    <p>
        <label for="Sess_ID">Session:  </label>
        <select name="Sess_ID" required>
            <option value="1">C1</option>
            <option value="2">C2</option>
            <option value="3">C3</option>
            <option value="4">S1</option>
            <option value="5">S2</option>
        </select>
    </p>

    <p>
        <label for="LDay">Lecture Day:</label>
        <select name="LDay" required>
            <option value="monday">Monday</option>
            <option value="tuesday">Tuesday</option>
            <option value="wednesday">Wednesday</option>
            <option value="thursday">Thursday</option>
            <option value="friday">Friday</option>
            <option value="saturday">Saturday</option>
            <option value="sunday">Sunday</option>
        </select>
    </p>

    <p>
        <label for="TDays">Tutorial Day:</label>
        <select name="TDays" required>
            <option value="monday">Monday</option>
            <option value="tuesday">Tuesday</option>
            <option value="wednesday">Wednesday</option>
            <option value="thursday">Thursday</option>
            <option value="friday">Friday</option>
            <option value="saturday">Saturday</option>
            <option value="sunday">Sunday</option>
        </select>
    </p>

    <p>	
        <input type ="submit" name="Add"/>
    </p>
    </form>
    </body>
</html>

