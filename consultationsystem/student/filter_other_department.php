<?php
session_start();
include_once '../database/dbconnect.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: ../student/LoginForStudent.php");
    exit;
}

$usersession = $_SESSION['student_id'];
$res = mysqli_query($con, "SELECT * FROM student WHERE student_id=" . $usersession);
$userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Bootstrap JS Bundle (Bootstrap + Popper.js) -->
     <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.bundle.min.js"></script>


    <style>
        .booked {
            color: red;
            font-weight: bold;
        }
    </style>


</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['student_name']; ?></h2>
    <h3>Available Timetables</h3>
    <table>
        <thead>
            <tr>
                <th>Time ID</th>
                <th>Schedule Date</th>
                <th>Schedule Day</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Modal</th>
                <th>Availability</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            include_once '../database/dbconnect.php';

            // Check if the form is submitted
            $staffResult = null;

            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['selected_other_staff_id'])) {
                $selectedStaffId = $_GET['selected_other_staff_id'];
                echo "Selected Staff ID: " . $selectedStaffId; // Add this line for debugging
                // Fetch staff's available time schedule for the selected staff ID
                $staffQuery = "SELECT * FROM stafftimeschedule WHERE staffID = '$selectedStaffId'";
                $staffResult = mysqli_query($con, $staffQuery);
                if (!$staffResult) {
                    die('Error: ' . mysqli_error($con));
                }
            }

            // Display the staff's available time schedule
            while ($staffRow = mysqli_fetch_array($staffResult)) {
                echo "<tr>";
                echo "<td>" . $staffRow['timeID'] . "</td>";
                echo "<td>" . $staffRow['scheduleDate'] . "</td>";
                echo "<td>" . $staffRow['scheduleDay'] . "</td>";
                echo "<td>" . $staffRow['startTime'] . "</td>";
                echo "<td>" . $staffRow['endTime'] . "</td>";
                echo "<td>" . $staffRow['modal'] . "</td>";
                echo "<td class='" . ($staffRow['bookAvail'] == 'booked' ? 'booked' : '') . "'>" . $staffRow['bookAvail'] . "</td>";
                if ($staffRow['bookAvail'] == 'available') {
                    echo  "<td><a href='#' onclick='confirmMakeAppointment(\"" . $staffRow['scheduleDate'] . "\", \"" . $staffRow['scheduleDay'] . "\", \"" . $staffRow['timeID'] . "\")' data-toggle='modal' data-target='#confirmMakeAppointment'>Make Appointment</a></td>";
                } else {
                    echo  "<td><a href='#' onclick='confirmCancelAppointment(\"" . $staffRow['scheduleDate'] . "\", \"" . $staffRow['scheduleDay'] . "\", \"" . $staffRow['timeID'] . "\")' data-toggle='modal' data-target='#confirmCancelAppointment' style='color:red'>Cancel Appointment</a></td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Modal Make Appointment -->
    <div class="modal fade" id="confirmMakeAppointment" tabindex="-1" role="dialog" aria-labelledby="confirmMakeAppointmentTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmMakeAppointmentTitle">Confirm Make Appointment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to make an appointment on <span id="appointmentDate"></span><span id="appointmentTimeID" style="display: none;"></span>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <a id="confirmAppointmentButton" href="#" class="btn btn-primary">Confirm</a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal Cancel Appointment -->
    <div class="modal fade" id="confirmCancelAppointment" tabindex="-1" role="dialog" aria-labelledby="confirmCancelAppointmentTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCancelAppointmentTitle">Confirm Cancel Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel the appointment on <span id="appointmentDateCancel"></span><span id="appointmentTimeIDCancel" style="display: none;"></span>?
                    <form id="cancelAppointmentForm" method="post" action="../student/cancel_appointment.php">
                        <div class="form-group">
                            <label for="cancelReason">Reason for cancellation:</label>
                            <textarea class="form-control" id="cancelReason" name="cancelReason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="confirmCancelAppointmentButton" type="button" class="btn btn-danger" onclick="submitCancelAppointmentForm()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmMakeAppointment(scheduleDate, scheduleDay, timeID) {
            document.getElementById("appointmentDate").textContent = scheduleDate + " (" + scheduleDay + ")";
            document.getElementById("appointmentTimeID").textContent = timeID;
            document.getElementById("confirmAppointmentButton").href = "../student/make_appointment.php?timeID=" + timeID;
        }

        function confirmCancelAppointment(scheduleDate, scheduleDay, timeID) {
        document.getElementById("appointmentDateCancel").textContent = scheduleDate + " (" + scheduleDay + ")";
        document.getElementById("appointmentTimeIDCancel").textContent = timeID;
        document.getElementById("cancelAppointmentForm").action = "../student/cancel_appointment.php?timeID=" + timeID;
        }

        function submitCancelAppointmentForm() {
            document.getElementById("cancelAppointmentForm").submit();
        }
    </script>

    <a href="../student/student_dashboard.php">Go Back to dashboard</a>
</body>
</html>
