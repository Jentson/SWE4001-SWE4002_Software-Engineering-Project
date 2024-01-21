<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'ez4leave');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sanitise_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (!isset($_SESSION['stud_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "StudentLogin.php";</script>';
    exit();
}

$student_id = $_SESSION['stud_id'];

$sql = mysqli_query($conn, "SELECT * FROM Students WHERE stud_id = '$student_id'");
$result = mysqli_fetch_assoc($sql);

$student_name = $result['stud_name'];
$stud_email = $result['stud_email'];

ob_start();

if (isset($_POST['Submit'])) {
    // Retrieve form data
    $startLeave = $_POST['startDate'];
    $endLeave = $_POST['endDate'];

    $file = $_FILES['files']['name'];
    $file_type = $_FILES['files']['type'];
    $file_tmp_loc = $_FILES['files']['tmp_name'];
    $file_store = "file/" . $file;

    move_uploaded_file($file_tmp_loc, $file_store);

    $subjects = isset($_POST['subject_codes']) ? $_POST['subject_codes'] : array();
    $reason = sanitise_input($_POST['inputDescription']);
    $assignment_successful = true;

    // Insert the data into the database
    foreach ($subjects as $key => $value) {
        $subject = $subjects[$key];
        $status = "Pending";

        // Insert leave application into the leave_application table
        $query = "INSERT INTO leave_application (stud_id, stud_name, subj_code, startDate, endDate, files, reason, status)
                VALUES ('$student_id', '$student_name', '$subject', '$startLeave', '$endLeave', '$file','$reason', '$status')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $leave_id = mysqli_insert_id($conn); // Get the last inserted leave_id

            // Get the lecturer ID based on the subject code
            $lecturer_id_query = "SELECT lect_id FROM subject WHERE subj_id = '$subject'";
            $lecturer_id_result = mysqli_query($conn, $lecturer_id_query);
            $lecturer_id_row = mysqli_fetch_assoc($lecturer_id_result);
            $lecturer_id = $lecturer_id_row['lect_id'];

            // Assign the leave application to the lecturer in the leave_application_assignment table
            $assignment_query = "INSERT INTO leave_application_assignment (leave_application_id, lecturer_id, status)
                               VALUES ('$leave_id', '$lecturer_id', '$status')";
            $assignment_result = mysqli_query($conn, $assignment_query);

            if (!$assignment_result) {
                $assignment_successful = false;
                echo "Error assigning leave application for subject $subject: " . mysqli_error($conn);
            }
        } else {
            $assignment_successful = false;
            echo "Error inserting leave application for subject $subject: " . mysqli_error($conn);
        }
    }

    if ($assignment_successful) {
        ob_end_clean(); // Clear the output buffer
        header("Location: StudentMain.php");
        exit();
    } else {
        echo "Error submitting leave applications.";
    }
}

mysqli_close($conn);
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="studentStyle.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="styles/LeaveStud.css" rel="stylesheet">

    <title>Student Form Page</title>
  </head>
  
  <body>  
  <section class="container my-2 bg-dark w-50 text-light p-2">
        <header class="text-center">
            <h1 class="display-6">Student Page</h1>
        </header>
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
        <div class="col-12">
          <label for="staticEmail" class="form-label">Email</label>
          <input type="text" readonly class="form-control" name="staticEmail" id="staticEmail" value="<?php echo $stud_email; ?>">
        </div>
        <div class="col-md-6">
          <label for="inputStudentId" class="form-label">Student ID</label>
          <input type="text" readonly class="form-control" name="inputStudentId" id="inputStudentId" value="<?php echo $student_id; ?>">
        </div>
        <div class="col-md-6">
          <label for="inputStudentName" class="form-label">Student Name</label>
          <input type="text" readonly class="form-control" name="inputStudentName" id="inputStudentName" value ="<?php echo $student_name;?>">
        </div>
          <div class="dropdown col-md-6">
            <label for="inputSubCode" class="form-label">Subject Code</label>
            <div class="dropdown-toggle" data-bs-toggle="dropdown">
              Choose Your Subject
            </div>
            <div class="dropdown-menu dropdown-content col-sm-12" align="center">
              <label class="checkbox-item">
                <input type="checkbox" name="subject_codes[]" value="SWE20001"> SWE20001
              </label>
              <label class="checkbox-item">
                <input type="checkbox" name="subject_codes[]" value="TNE20002"> TNE20002
              </label>
              <label class="checkbox-item">
                <input type="checkbox" name="subject_codes[]" value="COS30019"> COS30019
              </label>
              <label class="checkbox-item">
                <input type="checkbox" name="subject_codes[]" value="ICT30001"> ICT30001
              </label>
			  <label class="checkbox-item">
                <input type="checkbox" name="subject_codes[]" value="COS10009"> COS10009
              </label>
            </div>
          </div>
        
          <div class="form-group col-md-6">
            <label for="startDate" class="form-label">Start Date</label>
            <div class="col-sm-12 d-flex" align="center">
              <input type="date" name="startDate" placeholder="Select a date">
            </div>
			<label for="endDate" class="form-label">End Date</label>
            <div class="col-sm-12 d-flex" align="center">
				<input type="date" name="endDate" placeholder="Select a date">
			</div>
          </div>
    
        <div class="mb-3">
          <label for="inputFile" class="form-label">File/Evidence</label>
          <input class="form-control" type="file" name="files" id="inputFile">
        </div>
        <div class="mb-3">
          <label for="inputDescription" class="form-label">Description</label>
          <textarea class="form-control" name="inputDescription" id="inputDescription" rows="4"></textarea>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <button type="submit" class="btn btn-primary"  name="Submit">Submit</button>
        </div>
      </form>
    </section>
    
    <script>
    const dropdown = document.querySelector('.dropdown');
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');

    dropdown.addEventListener('click', (event) => {
      event.stopPropagation();
      dropdown.classList.toggle('open');
    });

    checkboxes.forEach((checkbox) => {
      checkbox.addEventListener('change', () => {
        const selectedOptions = Array.from(checkboxes)
          .filter((checkbox) => checkbox.checked)
          .map((checkbox) => checkbox.value);
        console.log('Selected options:', selectedOptions);
      });
    });

    document.addEventListener('click', () => {
      dropdown.classList.remove('open');
    });
    </script>
  </body>
</html>
