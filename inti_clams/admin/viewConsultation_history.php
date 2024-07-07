<?php
require_once "../database/dbconnect.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../login.php";</script>';
    exit();
}

// Prepare the query with placeholders
$query = "SELECT * FROM appointment_history WHERE status != 'deleted'";

$result = $con->query($query);
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
    $csvFileName = 'appointment_history_exported_data_' . $currentDate . '.csv';
    $csvFile = fopen($csvFileName, 'w');

    // Write headers to the CSV file
    $headers = ['Appointment History ID', 'Time ID', 'Student ID', 'Student Name', 'Schedule Date', 'Start Time', 'End Time', 'Modal', 'Status', 'Staff ID', 'Reason', 'Booking Timestamp'];
    fputcsv($csvFile, $headers);

    // Iterate over the data and write each row to the CSV file
    foreach ($data as $row) {
        $csvRow = [
            $row['appointment_history_id'],
            $row['time_id'],
            $row['student_id'],
            $row['student_name'],
            $row['schedule_date'],
            $row['start_time'],
            $row['end_time'],
            $row['modal'],
            $row['status'],
            $row['staff_id'],
            $row['reason'],
            $row['booking_timestamp']
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
    <title>Consultation History</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            padding-top: 20px;
        }
    </style>
    <script>
        function fetchFilteredData(searchTerm = "") {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    var tbody = document.getElementById('tableBody');
                    tbody.innerHTML = '';
                    data.forEach(function(row) {
                        var tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.appointment_history_id}</td>
                            <td>${row.time_id}</td>
                            <td>${row.student_id}</td>
                            <td>${row.student_name}</td>
                            <td>${row.schedule_date}</td>
                            <td>${row.start_time}</td>
                            <td>${row.end_time}</td>
                            <td>${row.modal}</td>
                            <td>${row.status}</td>
                            <td>${row.staff_id}</td>
                            <td>${row.reason}</td>
                            <td>${row.booking_timestamp}</td>
                            <td>
                                <form method="post" action="update_appointment_history.php">
                                    <input type="hidden" name="appointment_history_id" value="${row.appointment_history_id}">
                                    <input type="submit" name="update" value="Update" class="btn btn-primary btn-sm">
                                </form>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
            };
        }

    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#searchTerm').on('input', function() {
                searchTable($(this).val());
            });

            function searchTable(keyword) {
                var $rows = $('tbody tr');
                $rows.hide();
                $rows.filter(function() {
                    return $(this).text().toLowerCase().includes(keyword.toLowerCase());
                }).show();
            }
        });
    </script>

</head>
<body>
    <div class="container">

        <!-- Images -->
        <div class="row justify-content-center">
        <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        </div>

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
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">Student ID</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Schedule Date</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">Modal</th>
                    <th scope="col">Status</th>
                    <th scope="col">Staff ID</th>
                    <th scope="col">Reason</th>
                    <th scope="col">Booking Timestamp</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['schedule_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['modal']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['staff_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                        <td><?php echo htmlspecialchars($row['booking_timestamp']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
