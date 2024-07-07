<?php
require_once "../database/dbconnect.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../login.php";</script>';
    exit();
}

// Fetch all data from the database table
$query = "SELECT student.*, department.department_name, program.program_name, status.status_name
          FROM student 
          INNER JOIN department ON student.department_id = department.department_id
          INNER JOIN program ON student.program_id = program.program_id
          INNER JOIN status ON student.status_id = status.status_id;";
$result = mysqli_query($con, $query);

// Check if any rows are returned
if (mysqli_num_rows($result) > 0) {
    // Fetch all rows as an associative array
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Close the database connection
    mysqli_close($con);
} else {
    $data = [];
}

// Export to CSV functionality
if (isset($_POST['export_csv'])) {
    $currentDate = date("Y-m-d");
    $csvFileName = 'student_exported_data' . $currentDate . '.csv';
    $csvFile = fopen($csvFileName, 'w', 'UTF-8');

    // Write headers to the CSV file
    $headers = ['Student ID', 'Student Name', 'Student Email', 'Student Phone Number', 'Student Identity Number', 'Student Address', 'State', 'Department', 'Program', 'Gender', 'Status'];
    fputcsv($csvFile, $headers);

    // Iterate over the data and write each row to the CSV file
    foreach ($data as $row) {
        $csvRow = [
            $row['student_id'],
            $row['student_name'],
            $row['student_email'],
            $row["student_phone_number"],
            $row["student_identify_number"],
            $row['student_address'],
            $row['state'],
            $row['department_name'],
            $row['program_name'],
            $row['gender'],
            $row['status_name']
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
    <title>Display Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
        <!-- Images -->
        <div class="row justify-content-center">
        <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        </div>
        
    <h2 class="text-center mb-4">Display Data</h2>
    <div class="row justify-content-end align-items-center mb-3">
    <div class="col-auto">
        <form method="post" action="" class="d-inline">
            <button type="submit" name="export_csv" class="btn btn-primary">Export to CSV</button>
        </form>
    </div>
    <div class="col-auto">
        <a href="adminmain.php" class="btn btn-secondary">Back</a>
    </div>
</div>

<form id="searchForm" method="get" action="" class="mb-2">
    <div class="input-group">
        <input type="text" id="searchTerm" name="searchTerm" class="form-control form-control-sm custom-search-input" 
        placeholder="Search by student name, email, or department">
    </div>
</form>


    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Student Email</th>
                <th>Student Phone Number</th>
                <th>Student Identity Number</th>
                <th>Student Address</th>
                <th>State</th>
                <th>Department</th>
                <th>Program</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="studentTableBody">
            <!-- The table rows will be dynamically populated here -->
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo $row['student_name']; ?></td>
                    <td><?php echo $row['student_email']; ?></td>
                    <td><?php echo $row['student_phone_number']; ?></td>
                    <td><?php echo $row['student_identify_number']; ?></td>
                    <td><?php echo $row['student_address']; ?></td>
                    <td><?php echo $row['state']; ?></td>
                    <td><?php echo $row['department_name']; ?></td>
                    <td><?php echo $row['program_name']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['status_name']; ?></td>
                    <td>
                        <form method="post" action="updatestudent.php">
                            <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                            <button type="submit" class="btn btn-secondary btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchTerm').addEventListener('input', function() {
        var searchTerm = this.value;
        fetchStudentData(searchTerm);
    });

    function fetchStudentData(searchTerm = "") {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                var tbody = document.getElementById('studentTableBody');
                tbody.innerHTML = '';
                data.forEach(function(row) {
                    var tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.student_id}</td>
                        <td>${row.student_name}</td>
                        <td>${row.student_email}</td>
                        <td>${row.student_phone_number}</td>
                        <td>${row.student_identify_number}</td>
                        <td>${row.student_address}</td>
                        <td>${row.state}</td>
                        <td>${row.department_name}</td>
                        <td>${row.program_name}</td>
                        <td>${row.gender}</td>
                        <td>${row.status_name}</td>
                        <td>
                            <form method="post" action="updateStudent.php">
                                <input type="hidden" name="student_id" value="${row.student_id}">
                                <button type="submit" class="btn btn-secondary btn-sm">Update</button>
                            </form>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        };
        xhttp.open("GET", "search_student.php?searchTerm=" + encodeURIComponent(searchTerm), true);
        xhttp.send();
    }
</script>

</body>
</html>
