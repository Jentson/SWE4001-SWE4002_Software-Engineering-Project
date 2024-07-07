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
$query = "SELECT subject.*, department.department_name, staff.staff_id, staff.staff_name
          FROM subject
          INNER JOIN department ON subject.department_id = department.department_id
          INNER JOIN staff ON subject.staff_id = staff.staff_id"; // Added staff table join
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Check if any rows are returned
    if (mysqli_num_rows($result) > 0) {
        // Fetch all rows as an associative array
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        // No data found
        $data = [];
    }
    // Close the database connection
    mysqli_close($con);
} else {
    // Error in executing the query
    echo "Error: " . mysqli_error($con);
    exit();
}

// Export to CSV functionality
if (isset($_POST['export_csv'])) {
    $currentDate = date("Y-m-d");
    $csvFileName = 'subject_exported_data_' . $currentDate . '.csv';
    $csvFile = fopen($csvFileName, 'w', 'UTF-8');

    // Write headers to the CSV file
    $headers = ['Subject ID', 'Subject Name', 'Department Name', 'Staff ID', 'Staff Name']; // Added staff columns
    fputcsv($csvFile, $headers);

    // Iterate over the data and write each row to the CSV file
    foreach ($data as $row) {
        $csvRow = [
            $row['subject_id'],
            $row['subject_name'],
            $row['department_name'],
            $row['staff_id'],
            $row['staff_name']
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
    <script>
        function searchSubjects() {
            var searchTerm = document.getElementById('searchTerm').value;
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('subjectTableBody').innerHTML = xhr.responseText;
                }
            };
            xhr.open('GET', 'search_subject.php?searchTerm=' + searchTerm, true);
            xhr.send();
        }
    </script>
</head>
<body>
    
        <!-- Images -->
        <div class="row justify-content-center">
            <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        </div>
        
    <div class="container mt-3">
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
        <input type="text" id="searchTerm" class="form-control mb-3" placeholder="Search by subject name, department, or staff" oninput="searchSubjects()">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject ID</th>
                    <th>Subject Name</th>
                    <th>Department Name</th>
                    <th>Staff ID</th>
                    <th>Staff Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="subjectTableBody">
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $row['subject_id']; ?></td>
                            <td><?php echo $row['subject_name']; ?></td>
                            <td><?php echo $row['department_name']; ?></td>
                            <td><?php echo $row['staff_id']; ?></td>
                            <td><?php echo $row['staff_name']; ?></td>
                            <td>
                                <form method="post" action="updateSubject.php">
                                    <input type="hidden" name="subject_id" value="<?php echo $row['subject_id']; ?>">
                                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No subjects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
