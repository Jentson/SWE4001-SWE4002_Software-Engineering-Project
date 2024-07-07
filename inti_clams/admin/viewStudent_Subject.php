<?php
require_once "../database/dbconnect.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../login.php";</script>';
    exit();
}

$query = "SELECT student_subjects.student_subject_id, student.student_id, student.student_name, subject.subject_id, subject.subject_name, program.program_id, program.program_name, student_subjects.session, student_subjects.semester, student_subjects.section_id 
          FROM student_subjects 
          INNER JOIN student ON student_subjects.student_id = student.student_id 
          INNER JOIN subject ON student_subjects.subject_id = subject.subject_id 
          INNER JOIN program ON student_subjects.program_id = program.program_id";

$result = $con->query($query);
//echo "Query: " . $query . "<br>";

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "Error fetching data: " . $con->error;
}

// Export to CSV functionality
if (isset($_POST['export_csv'])) {
    $currentDate = date("Y-m-d");
    $csvFileName = 'student_subject_exported_data_' . $currentDate . '.csv';
    $csvFile = fopen($csvFileName, 'w', 'UTF-8');

    // Write headers to the CSV file
    $headers = ['Student_Subject ID','Student ID', 'Student Name', 'Subject ID', 'Subject Name', 'Program ID', 'Program Name', 'Session', 'Semester', 'Section ID'];
    fputcsv($csvFile, $headers);

    // Iterate over the data and write each row to the CSV file
    foreach ($data as $row) {
        $csvRow = [
            $row['student_subject_id'],
            $row['student_id'],
            $row['student_name'],
            $row['subject_id'],
            $row['subject_name'],
            $row['program_id'],
            $row['program_name'],
            '=' . '"' . $row['session'] . '"',
            $row['semester'],
            $row['section_id']
        ];
        fputcsv($csvFile, $csvRow);
    }

    // Close the file handle
    fclose($csvFile);

    // Provide the CSV file for download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $csvFileName . '"');
    readfile($csvFileName);

    // Exit the script
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        /* Optional: Add custom CSS styles here */
        body {
            padding-top: 20px;
        }
    </style>
    <script>
        function fetchStudentSubjectData(searchTerm = "") {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    var tbody = document.getElementById('studentSubjectTableBody');
                    tbody.innerHTML = '';
                    data.forEach(function(row) {
                        var tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td><input type="checkbox" name="selectedRows" value="${row.student_subject_id}"></td>
                            <td>${row.student_id}</td>
                            <td>${row.student_name}</td>
                            <td>${row.subject_id}</td>
                            <td>${row.subject_name}</td>
                            <td>${row.program_name}</td>
                            <td>${row.session}</td>
                            <td>${row.semester}</td>
                            <td>${row.section_id}</td>
                            <td>
                                <form method="post" action="updateStudent_Subject.php">
                                    <input type="hidden" name="student_subject_id" value="${row.student_subject_id}">
                                    <input type="submit" name="update" value="Update" class="btn btn-primary btn-sm">
                                </form>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            };
            xhttp.open("GET", "search_student_subjects.php?searchTerm=" + encodeURIComponent(searchTerm), true);
            xhttp.send();
        }

        function deleteSelectedRows() {
            var checkboxes = document.querySelectorAll('input[name="selectedRows"]:checked');
            var selectedIds = [];
            checkboxes.forEach(function(checkbox) {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length > 0) {
                var xhttp = new XMLHttpRequest();
                xhttp.open("POST", "delete_student_subjects.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        fetchStudentSubjectData(); // Refresh the table data
                    }
                };
                xhttp.send("ids=" + JSON.stringify(selectedIds));
            } else {
                alert("No rows selected for deletion.");
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchStudentSubjectData(); // Load all data on page load
            document.getElementById('searchTerm').addEventListener('input', function() {
                fetchStudentSubjectData(this.value); // Update data dynamically on input
            });
        });
    </script>

</head>
<body>
    <div class="container">
        <div class="text-center">
        <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        <h2>Display Data</h2>
        </div>
        <a href="adminmain.php" class="btn btn-secondary mb-3">Back</a>
        <form class="mb-3" method="post" action="">
            <div class="input-group">
                <input type="text" id="searchTerm" name="searchTerm" class="form-control" placeholder="Search by student name or subject name">
                <button type="submit" name="export_csv" class="btn btn-primary">Export to CSV</button>
                <button type="button" class="btn btn-danger" onclick="deleteSelectedRows()">Delete Selected</button>
            </div>
        </form>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><input class="form-check-input" type="checkbox" id="selectAllCheckbox" onclick="toggleSelectAll()">
                    Select</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Subject ID</th>
                    <th>Subject Name</th>
                    <th>Program Name</th>
                    <th>Session</th>
                    <th>Semester</th>
                    <th>Section ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="studentSubjectTableBody">
                <!-- The table rows will be dynamically populated here -->
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script>
        function toggleSelectAll() {
        var checkboxes = document.querySelectorAll('input[name="selectedRows"]');
        var selectAllCheckbox = document.getElementById('selectAllCheckbox');

        checkboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

