<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History</title>
    <!-- Include Bootstrap CSS and any other CSS files -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include your custom CSS file if any -->
    <a href="../staff/staffDashboard.php" class="btn btn-primary">Go Back to Dashboard</a>
</head>
<body>
    <div class="container">
        <h1>Appointment History</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Schedule Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Modal</th>
                    <th>Book Available</th>
                    <th>Cancel Reason</th>
                    <th>Booking Timestamp</th>
                </tr>
            </thead>
            <tbody>
                
<?php
session_start();
include_once '../database/dbconnect.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['Staff_id'])) {
    header("Location: ../staff/LoginForStaff.php");
    exit;
}
$usersession = $_SESSION['Staff_id'];
                $query = "SELECT * FROM appointment_history WHERE staff_id = '$usersession'";
                $result = mysqli_query($con, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['student_id'] . "</td>";
                        echo "<td>" . $row['student_name'] . "</td>";
                        echo "<td>" . $row['schedule_date'] . "</td>";
                        echo "<td>" . $row['start_time'] . "</td>";
                        echo "<td>" . $row['end_time'] . "</td>";
                        echo "<td>" . $row['modal'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . $row['cancel_reason'] . "</td>";
                        echo "<td>" . $row['booking_timestamp'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No appointment history found.</td></tr>";
                }

                // Close the connection
                mysqli_close($con);
                ?>
            </tbody>
        </table>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
