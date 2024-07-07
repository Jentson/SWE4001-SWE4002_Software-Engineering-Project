<?php
require_once "../database/dbconnect.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../login.php";</script>';
    exit();
}

// Export to CSV functionality
if (isset($_POST['export_csv'])) {
    // Open a file handle for writing to a CSV file
    $csvFileName = 'exported_staff_data.csv';
    $csvFile = fopen($csvFileName, 'w');

    // Write headers to the CSV file
    $headers = ['Staff ID', 'Staff Name', 'Staff Email', 'Staff Identity Number', 'Staff Address', 'Phone Number', 'Position', 'Department', 'Gender'];
    fputcsv($csvFile, $headers);

    // Fetch the data from the database
    $query = "SELECT s.staff_id, s.staff_name, s.staff_email, s.staff_identity_number, s.staff_address, s.phone_number, p.position_name, d.department_name, s.gender 
              FROM staff s 
              JOIN position p ON s.position_id = p.position_id 
              JOIN department d ON s.department_id = d.department_id";
    $result = mysqli_query($con, $query);

    // Iterate over the data and write each row to the CSV file
    while ($row = mysqli_fetch_assoc($result)) {
        $csvRow = [
            $row['staff_id'],
            $row['staff_name'],
            $row['staff_email'],
            $row['staff_identity_number'],
            $row['staff_address'],
            $row['phone_number'],
            $row['position_name'],
            $row['department_name'],
            $row['gender']
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
    <title>Display Staff Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        function fetchStaffData(searchTerm = "") {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    var tbody = document.getElementById('staffTableBody');
                    tbody.innerHTML = '';
                    data.forEach(function(row) {
                        var tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.staff_id}</td>
                            <td>${row.staff_name}</td>
                            <td>${row.staff_email}</td>
                            <td>${row.staff_identity_number}</td>
                            <td>${row.staff_address}</td>
                            <td>${row.phone_number}</td>
                            <td>${row.position_name}</td>
                            <td>${row.department_name}</td>
                            <td>${row.gender}</td>
                            <td>
                                <form method="post" action="updateStaff.php">
                                    <input type="hidden" name="staff_id" value="${row.staff_id}">
                                    <input type="submit" name="update" value="Update" class="btn btn-warning btn-sm">
                                </form>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            };
            xhttp.open("GET", "search_staff.php?searchTerm=" + encodeURIComponent(searchTerm), true);
            xhttp.send();
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchStaffData(); // Load all data on page load
            document.getElementById('searchTerm').addEventListener('input', function() {
                fetchStaffData(this.value); // Update data dynamically on input
            });
        });
    </script>
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
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Staff Name</th>
                    <th>Staff Email</th>
                    <th>Staff Identity Number</th>
                    <th>Staff Address</th>
                    <th>Phone Number</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="staffTableBody">
                <!-- The table rows will be dynamically populated here -->
            </tbody>
        </table>
    </div>
</body>
</html>
