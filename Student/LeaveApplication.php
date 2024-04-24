<?php
require_once "LeaveSubmit.php";
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
    <script src="../Student/date.js"></script>
    <title>Leave Form Page</title>>
    
    <script>
    function validateForm() {
        var description = document.getElementById("inputDescription").value;
        // Regex to check if the description is strictly alphanumeric
        var alphaNumRegex = /^[a-z\d\-_\s]+$/i;

        if (!alphaNumRegex.test(description)) {
            alert("Description must be alphanumeric and should not contain any special characters.");
            return false; // Prevent form submission
        }
        return true;
    }
    </script>

  <body>  
  <section class="container my-2 bg-dark w-50 text-light p-2">
        <header class="text-center">
            <h1 class="display-6">Leave Application Page</h1>
        </header>

  <form  method="POST" action="LeaveSubmit.php" enctype="multipart/form-data" onsubmit ="return validateForm()">
        <div class="col-12">
          <label for="staticEmail" class="form-label">Email</label>
          <input type="text" readonly class="form-control" name="staticEmail" id="staticEmail" value="<?php echo $studentInfo['stud_email']; ?>">
        </div>

        <div class="col-md-6">
          <label for="inputStudentId" class="form-label">Student ID</label>
          <input type="text" readonly class="form-control" name="inputStudentId" id="inputStudentId" value="<?php echo $studentInfo['stud_id']; ?>">
        </div>

        <div class="col-md-6">
          <label for="inputStudentName" class="form-label">Student Name</label>
          <input type="text" readonly class="form-control" name="inputStudentName" id="inputStudentName" value ="<?php echo $student_name;?>">
        </div>

        <div class="col-12">
          <label for="staticEmail" class="form-label">Session</label>
          <input type="text" readonly class="form-control" name="session" value="<?php echo $studentInfo['session']; ?>">
        </div>

        <div class="col-12">
          <label for="staticEmail" class="form-label">Phone Number</label>
          <input type="text" readonly class="form-control" name="phone" value="<?php echo $studentInfo['phone']; ?>">
        </div>

        <div class="col-12">
          <label for="staticEmail" class="form-label">Address</label>
          <input type="text"  readonly class="form-control" name="session" value="<?php echo $studentInfo['address']; ?>">
        </div>

        <div class="col-12">
          <label for="staticEmail" class="form-label">Subjects: </label>
        <div id ="subjectCheckboxes">
          <?php
          // Prepare a SQL statement with a placeholder for the student ID
          $sql = "SELECT subj_code FROM enrollment WHERE stud_id = ?";
          $stmt = $conn->prepare($sql);

          // Bind the parameter and execute the statement
          $stmt->bind_param("s", $student_id); 
          $stmt->execute();

          // Get the result set
          $result = $stmt->get_result();

          if ($result === false) {
              // Query execution failed
              echo "Error executing query: " . $conn->error;
          } elseif ($result->num_rows > 0) {
              // Loop through the results and generate checkboxes
              while($row = $result->fetch_assoc()) {
                  $subjCode = $row["subj_code"];
                  echo "<div class='form-check'>";
                  echo "<input type='checkbox' class='form-check-input' name='enrolled_subjects[]' value='$subjCode'>";
                  echo "<label class='form-check-label'>$subjCode</label>";
                  echo "</div>";
              }
          } else {
              echo "No enrolled subjects found for the student.";
          }
          
          // Close the statement and connection
          $stmt->close();
          ?>
          </div>
          <div id = "subjectError" class="text-danger"></div>
        </div>

<script>
        document.querySelector('form').addEventListener('submit', function(event) {
        const selectedSubjects = document.querySelectorAll('input[name="enrolled_subjects[]"]:checked');
        const subjectError = document.getElementById('subjectError');

        if (selectedSubjects.length === 0) {
            event.preventDefault(); // Prevent form submission
            subjectError.textContent = 'Please select at least one subject.';
        } else {
            subjectError.textContent = ''; // Clear the error message
        }
      });
</script>

      <div class="form-group col-md-6">
          <label for="startDate" class="form-label">Start Date</label>
          <div class="col-sm-12 d-flex" align="center">
            <input type="date" id= "startDate" name="startDate" placeholder="Select a date">
      </div>
      
			<label for="endDate" class="form-label">End Date</label>
            <div class="col-sm-12 d-flex" align="center">
				<input type="date" id= "endDate" name="endDate" placeholder="Select a date">
			</div>
      </div>

    <div class="mb-3">
    <label for="inputFile" class="form-label">File/Evidence (Only PDF, JPEG, or PNG files are allowed)</label>
    <input class="form-control" type="file" name="files" id="inputFile" required>
    </div>

<script>
    document.getElementById('inputFile').addEventListener('change', function(e) {
    var allowedExtensions = /(\.pdf|\.jpeg|\.jpg|\.png)$/i;
      if (!allowedExtensions.exec(this.value)) {
            alert('Invalid file type. Only PDF, JPEG, and PNG files are allowed.');
            this.value = ''; // Clear the file input
            return false;
        }
      });
</script>

        <div class="mb-3">
          <label for="inputDescription" class="form-label">Description</label>
          <textarea class="form-control" name="inputDescription" id="inputDescription" rows="4" required></textarea>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
          <button type="submit" class="btn btn-primary"  name="Submit">Submit</button>
        </div>

      </form>
    </section>
  </body>
</html>
