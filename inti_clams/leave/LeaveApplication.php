<?php
require_once "LeaveSubmit.php";

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    header("Location: ../validation/login.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Leave Application Form</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../validation/date.js"></script>
    <style>
    .custom-card-header {
        background-color: red;
        color: white; /* Optional: Change text color to white */
    }

     .custom-card-footer {
                    background-color: white;
                }
        
    </style>
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
    <!-- Navbar -->
    <?php include '../index/student_navbar.php'; ?>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                 <div class="card">
                    <div class ="card-header  custom-card-header">
                        <h4 class="text-center ">Leave Application Form</h4>
                    </div>
        <form method="POST" action="LeaveSubmit.php" enctype="multipart/form-data" class="row g-3">
        <div class ="card-body">
            <div class="row g-3">
                <div class="col-md-6"> <!-- Adjust column width as needed -->
                    <label for="inputEmail" class="form-label">Email</label>
                    <input type="email" readonly class="form-control" id="inputEmail" value="<?php echo htmlspecialchars($studentResult['student_email']); ?>">
                </div>
                <div class="col-md-6"> <!-- Adjust column width as needed -->
                    <label for="inputStudentID" class="form-label">Student ID</label>
                    <input type="text" readonly class="form-control plain-text" id="inputStudentID" value="<?php echo htmlspecialchars($studentResult['student_id']); ?>">
                </div>
            </div>

            <div class="col-md-12">
                <label for="inputStudentName" class="form-label">Student Name</label>
                <input type="text" readonly class="form-control plain-text" id="inputStudentName" value="<?php echo htmlspecialchars($studentResult['student_name']); ?>">
            </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="inputAddress" class="form-label">Address</label>
                <input type="text" readonly class="form-control plain-text" name="address" id="inputAddress" value="<?php echo htmlspecialchars($studentResult['student_address']); ?>">
            </div>
            <div class="col-md-3">
                <label for="inputPhoneNumber" class="form-label">Phone Number</label>
                <input type="text" readonly class="form-control plain-text" name="phone" id="inputPhoneNumber" value="<?php echo htmlspecialchars($studentResult['student_phone_number']); ?>">
            </div>
            <div class="col-md-3">
                <label for="inputSession" class="form-label">Session</label>
                <input type="text" readonly class="form-control plain-text" name="session" id="inputSession" value="<?php echo htmlspecialchars($studentResult['session']); ?>">
            </div>
        </div>

            <div class="col-12">
                <label for="staticEmail" class="form-label">Subjects:<span style="color: red;">*</span></label>
                <div id="subjectCheckboxes">
                <?php
                    // Prepare a SQL statement with a placeholder for the student ID
                    $sql = "SELECT subject_id FROM student_subjects WHERE student_id = ?";
                    $stmt = $con->prepare($sql);

                    // Bind the parameter and execute the statement
                    $stmt->bind_param("s", $studentId);
                    $stmt->execute();

                    // Get the result set
                    $result = $stmt->get_result();

                    if ($result === false) {
                        // Query execution failed
                        echo "Error executing query: " . $con->error;
                    } elseif ($result->num_rows > 0) {
                        // Fetch all rows and count them
                        $subjects = $result->fetch_all(MYSQLI_ASSOC);
                        $totalSubjects = count($subjects);
                        $numPerColumn = ceil($totalSubjects / ceil($totalSubjects / 2)); // Calculate the number of subjects per column for equal distribution

                        // Output the subjects in columns
                        for ($i = 0; $i < $totalSubjects; $i += $numPerColumn) {
                            echo "<div class='col-md-6'>"; // Each column
                            for ($j = $i; $j < $i + $numPerColumn && $j < $totalSubjects; $j++) {
                                $subjCode = $subjects[$j]['subject_id'];
                                echo "<div class='form-check form-check-inline'>";
                                echo "<input type='checkbox' class='form-check-input' name='enrolled_subjects[]' value='$subjCode'>";
                                echo "<label class='form-check-label'>$subjCode</label>";
                                echo "</div>";
                            }
                            echo "</div>"; // Close column
                        }
                    } else {
                        echo "No enrolled subjects found for the student.";
                    }
                    $stmt->close();
                    ?>

                </div>
                <div id="subjectError" class="text-danger"></div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label for="startDate" class="form-label">Start Date<span style="color: red;">*</span></label>
                    <input type="date" class="form-control" id="startDate" name="startDate" placeholder="Select a date" required>
                </div>
                <div class="col-md-6">
                    <label for="endDate" class="form-label">End Date<span style="color: red;">*</span></label>
                    <input type="date" class="form-control" id="endDate" name="endDate" placeholder="Select a date" required>
                </div>
            </div>

            <div class="col-md-5">
                <label for="inputFile" class="form-label">File/Evidence (Only PDF, JPEG, or PNG files are allowed)</label>
                <input class="form-control form-control-sm" name="files" id="inputFile" type="file" 
                    <?php echo ($studentResult['state'] === 'International') ? 'required' : ''; ?>>
            </div>

            <div class="mb-3">
              <label for="inputDescription" class="form-label">Description<span style="color: red;">*</span></label>
              <textarea class="form-control" name="inputDescription" id="inputDescription" rows="3" required oninput="validateDescription()"></textarea>
              <div id="descriptionError" class="text-danger"></div>
           </div>  
                 

            <div class="card-footer custom-card-footer d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary" name="Submit">Submit</button>
            </div>

         </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Scripts for form validation -->
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

        document.getElementById('inputFile').addEventListener('change', function(e) {
            var allowedExtensions = /(\.pdf|\.jpeg|\.jpg|\.png)$/i;
            if (!allowedExtensions.exec(this.value)) {
                alert('Invalid file type. Only PDF, JPEG, and PNG files are allowed.');
                this.value = ''; // Clear the file input
                return false;
            }
        });

      // Description validation
      function validateDescription() {
          var description = document.getElementById("inputDescription").value;
          var alphaNumRegex = /^[a-z\d\-_\s]+$/i;
          var descriptionError = document.getElementById('descriptionError');

          if (!alphaNumRegex.test(description)) {
              descriptionError.textContent = 'Description must be alphanumeric and should not contain any special characters.';
              return false;
          } else {
              descriptionError.textContent = ''; // Clear the error message
              return true;
          }
      }

      document.addEventListener('DOMContentLoaded', function() {
          var inputDescription = document.getElementById("inputDescription");
          if(inputDescription) {
              inputDescription.oninput = validateDescription;
          }
      });


    </script>
</body>
</html>
