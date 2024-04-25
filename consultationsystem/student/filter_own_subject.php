<?php
session_start();
include_once '../database/dbconnect.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: ../student/LoginForStudent.php");
    exit;
}

$usersession = $_SESSION['student_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <a href="../student/student_dashboard.php">Go Back to dashboard</a>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                // Check if selected_own_staff_id is set
                if (isset($_GET['selected_own_staff_id'])) {
                    $selectedOwnStaffId = $_GET['selected_own_staff_id'];

                    // Fetch staff's available time schedule based on program and subject
                    // $staffQuery = "SELECT * FROM stafftimeschedule";
                    // $staffResult = mysqli_query($con, $staffQuery);
                    $staffQuery = "SELECT st.* FROM stafftimeschedule st
                    JOIN student_subjects ss ON st.staffID = ss.staff_id
                    JOIN subject s ON ss.subject_id = s.subject_id
                    WHERE ss.student_id = '" . $usersession . "' AND st.staffID = '" . $selectedOwnStaffId . "'";

                $staffResult = mysqli_query($con, $staffQuery);

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
                } else {
                    // Handle case when selected_own_staff_id is not set
                    echo "No staff ID selected.";
                }
            ?>
        </tbody>
    </table>
    <div class="modal" id="confirmMakeAppointmentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="appointmentInfo"></p>
                    <textarea id="appointmentReason" class="form-control" placeholder="Enter reason for the appointment"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="makeAppointment()">Confirm</button>
                </div>
            </div>
        </div>
    </div>
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

    <!-- Bootstrap JS (jQuery and Popper.js required) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function confirmCancelAppointment(scheduleDate, scheduleDay, timeID) {
        document.getElementById("appointmentDateCancel").textContent = scheduleDate + " (" + scheduleDay + ")";
        document.getElementById("appointmentTimeIDCancel").textContent = timeID;
        document.getElementById("cancelAppointmentForm").action = "../student/cancel_appointment.php?timeID=" + timeID;
        }

        function submitCancelAppointmentForm() {
            document.getElementById("cancelAppointmentForm").submit();
        }

    var appointmentData = {};

    function confirmMakeAppointment(timeID, scheduleDate, scheduleDay) {
        appointmentData = {
            timeID: timeID,
            scheduleDate: scheduleDate,
            scheduleDay: scheduleDay
        };
        $('#appointmentInfo').text(`Are you sure you want to make an appointment on ${timeID} (${scheduleDate})?`);
        $('#confirmMakeAppointmentModal').modal('show');
    }

    function makeAppointment() {
        var reason = $('#appointmentReason').val();
        if (!reason) {
            alert('Please enter a reason for the appointment.');
            return;
        }

        // You can use appointmentData to access timeID, scheduleDate, and scheduleDay
        // Very important reminder for the first time user
        // ** The assignment of timeID is jumping to thrid column (below scheduleDay is timeID!!)**
        // timeID is scheduleDate, scheduleDate is scheduleDay, scheduleDay is timeID
        var scheduleDay = appointmentData.scheduleDay;

        // Proceed with making appointment (you can use AJAX or window.location.href)
        var url = "make_appointment.php?timeID=" + scheduleDay + "&makeReason=" + encodeURIComponent(reason);
        window.location.href = url;
    }
</script>


    <a href="../student/student_dashboard.php">Go Back to dashboard</a>


</body>
</html>