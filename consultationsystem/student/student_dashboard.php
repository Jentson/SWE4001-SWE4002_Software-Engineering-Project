<?php
session_start();
include_once '../database/dbconnect.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    header("Location: ../student/LoginForStudent.php");
    exit;
}


// Fetch user details from the database
$usersession = $_SESSION['student_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        .booked {
            color: red;
            font-weight: bold;
        }
    </style>
    <a href="../student/logout_student.php">Logout</a>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['student_name']; ?></h2>

    <form method="GET" id="filterForm">
    <label for="label_selection">Select label:</label><br>
    <input type="radio" id="own_subject_radio" name="label_selection" value="own_subject" checked>
    <label for="own_subject_radio">Choose a lecturer from your program/subject</label><br>
    <input type="radio" id="other_department_radio" name="label_selection" value="other_department">
    <label for="other_department_radio">Choose a lecturer from other departments</label><br><br>

    <div id="own_subject_select" style="display: block;">
    <label for="own_subject">Choose a lecturer from your program/subject:</label><br>
    <select id="own_subject" name="own_subject">
    <?php
        // Fetch subjects of the student
        $query = "SELECT ss.staff_id, s.staff_name FROM student_subjects ss JOIN staff s ON ss.staff_id = s.staff_id WHERE ss.student_id ='$usersession'";
        $result = mysqli_query($con, $query);

        // Display options for selecting lecturer from own program/subject
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['staff_id'] . "'>" . $row['staff_name'] . "</option>";
        }
        ?>
    </select><br><br>
    <input type="hidden" id="selected_own_staff_id" name="selected_own_staff_id" value="">
    </div>
   

    <div id="other_department_select" style="display: none;">
        <label for="other_department">Choose a lecturer from other departments:</label><br>
        <select id="other_department" name="other_department">
        <?php
                // Fetch lecturers from other departments
                $query = "SELECT * FROM staff WHERE program_id NOT IN (SELECT program_id FROM student_subjects WHERE student_id ='$usersession')";
                $result = mysqli_query($con, $query);

                // Display options for selecting lecturer from other departments
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['staff_id'] . "'>" . $row['staff_name'] . "</option>";
                }
        ?>    
         </select><br><br>
         <input type="hidden" id="selected_other_staff_id" name="selected_other_staff_id" value="">
    </div>

    <input type="submit" value="Filter">
</form>

<script>
    function updateSelectedStaffId(radioValue, selectId, hiddenId) {
        var selectedStaffId = '';
        if (document.getElementById(radioValue).checked) {
            selectedStaffId = document.getElementById(selectId).value;
            document.getElementById(hiddenId).value = selectedStaffId;
            document.getElementById('filterForm').action = (radioValue === 'own_subject_radio') ? 'filter_own_subject.php' : 'filter_other_department.php';
        }
    }

    document.querySelectorAll('input[name="label_selection"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            document.getElementById('own_subject_select').style.display = (radio.value === 'own_subject') ? 'block' : 'none';
            document.getElementById('other_department_select').style.display = (radio.value === 'other_department') ? 'block' : 'none';
            document.getElementById('selected_own_staff_id').value = ''; // Reset own staff ID
            document.getElementById('selected_other_staff_id').value = ''; // Reset other staff ID
        });
    });

    document.getElementById('filterForm').addEventListener('submit', (event) => {
        updateSelectedStaffId('own_subject_radio', 'own_subject', 'selected_own_staff_id');
        updateSelectedStaffId('other_department_radio', 'other_department', 'selected_other_staff_id');
    });
</script>



</body>
</html>
