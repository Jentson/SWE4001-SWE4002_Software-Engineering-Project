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
    <script src="../formValidation.js" ></script>
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
<script>
      let addedSubjects = [];

      function fetchLecturerInfo() {
        const subjectCode = document.getElementById('subjectCode').value;
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_lecturer.php?subject_code=' + subjectCode, true);
        xhr.onload = function() {
          if (xhr.status === 200) {
            document.getElementById('lecturerName').value = xhr.responseText;
          } else {
            document.getElementById('lecturerName').value = '';
          }
        };
        xhr.send();
      }

      function addSubject() {
        const subjectCode = document.getElementById('subjectCode').value;
        const lecturerName = document.getElementById('lecturerName').value;

        if (subjectCode && lecturerName) {
          const subject = { code: subjectCode, lecturer: lecturerName };
          addedSubjects.push(subject);
          renderAddedSubjects();
          document.getElementById('subjectCode').value = '';
          document.getElementById('lecturerName').value = '';
        }
      }

      function renderAddedSubjects() {
        const addedSubjectsContainer = document.getElementById('addedSubjectsContainer');
        addedSubjectsContainer.innerHTML = '';

        addedSubjects.forEach((subject) => {
          const subjectElement = document.createElement('div');
          subjectElement.classList.add('added-subject');
          subjectElement.textContent = `${subject.code} - ${subject.lecturer}`;
          addedSubjectsContainer.appendChild(subjectElement);
        });

        // Update hidden input value with JSON data
        document.getElementById('addedSubjectsInput').value = JSON.stringify(addedSubjects);
}
</script>

    <title>Leave Form Page</title>
  </head>
  
  <body>  
  <section class="container my-2 bg-dark w-50 text-light p-2">
        <header class="text-center">
            <h1 class="display-6">Leave Application Page</h1>
        </header>
  <form method="POST" action="LeaveSubmit.php" enctype="multipart/form-data">
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
          <input type="text"  name="phone" value="<?php echo $studentInfo['phone']; ?>">
        </div>
        <div class="col-12">
          <label for="staticEmail" class="form-label">Address</label>
          <input type="text"  name="session" value="<?php echo $studentInfo['address']; ?>">
        </div>
        <div class="form-group col-md-6">
       <label for="subjectCode" class="form-label">Subject Code</label>
       <div class="input-group">
        <input type="text" class="form-control" id="subjectCode" name="subjectCode" onkeyup="fetchLecturerInfo()">
        <button class="btn btn-primary" type="button" onclick="addSubject()">Add</button>
       </div>
      </div>

        <div class="form-group col-md-6">
          <label for="lecturerName" class="form-label">Lecturer Name</label>
          <input type="text" class="form-control" id="lecturerName" name="lecturerName" readonly>
        </div>

        <div class="form-group">
          <label>Added Subjects:</label>
          <div id="addedSubjectsContainer"></div>
        </div>

        <input type="hidden" name="addedSubjects" id="addedSubjectsInput">

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
          <input class="form-control" type="file" name="files" id="inputFile" Required>
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
  </body>
</html>
