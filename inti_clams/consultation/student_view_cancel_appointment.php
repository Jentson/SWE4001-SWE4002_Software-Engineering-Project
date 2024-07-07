<?php
include_once '../database/db.php';
include_once '../validation/session.php'; 
include_once '../validation/checkingconsultation.php'; 


// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    header("Location: ../validation/login.php");
    exit;
}

$studentId = $_SESSION['student_id']; // Assuming you have the student's ID stored in the session

$query = "SELECT DISTINCT s.schedule_date, s.schedule_day, s.start_time, s.end_time, 
                 (SELECT sb.modal 
                  FROM student_bookings sb 
                  WHERE sb.time_id = s.time_id AND sb.student_id = '$studentId' 
                  ORDER BY sb.booking_id DESC LIMIT 1) AS modal, 
                 s.time_id, st.staff_name
          FROM student_bookings sb
          JOIN staff_timeschedule s ON sb.time_id = s.time_id
          JOIN staff st ON s.staff_id = st.staff_id
          WHERE sb.student_id = '$studentId' 
          AND s.book_avail = 'booked'
          AND s.time_id IN (SELECT time_id 
                            FROM student_bookings 
                            WHERE student_id = '$studentId' 
                            AND time_id = s.time_id)
          ORDER BY s.schedule_date ASC";




$result = mysqli_query($con, $query);
if (!$result) { 
    die('Error: ' . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View and Cancel Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script> -->
</head>  
    

<body class="p-3 m-0 border-0 bd-example m-0 border-0"> 
 <!-- Navbar -->
 <?php  include '../index/student_navbar.php'; ?>
<br>
<div class = "card"> 
    <h4 class = "card-header text-center">Booked Appointment</h4>
    <div class="card-body">
    <div class="table-responsive">
    <table id="cancel_subject_table" class="table table-bordered table-sm text-center">
        <thead>
            <tr>
                <th>Lecturer Name</th>
                <th>Schedule Date</th>
                <th>Schedule Day</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Model</th>
                <th>Action</th>
            </tr>
        </thead>
        
        <tbody>
            <?php
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['staff_name'] . "</td>";
                echo "<td>" . $row['schedule_date'] . "</td>";
                echo "<td>" . $row['schedule_day'] . "</td>";
                echo "<td>" . $row['start_time'] . "</td>";
                echo "<td>" . $row['end_time'] . "</td>";
                echo "<td>" . $row['modal'] . "</td>";
                echo "<td>
                 <button type='button' class='btn btn-danger btn-sm' onclick='confirmCancelAppointment(\"" . $row['schedule_date'] . "\", \"" . $row['schedule_day'] . "\", \"" . $row['time_id'] . "\")' data-toggle='modal' data-target='#confirmCancelAppointment'>Cancel Appointment</button>
                 </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Cancel Appointment -->
<div class="modal fade" id="confirmCancelAppointment" tabindex="-1" role="dialog" aria-labelledby="confirmCancelAppointmentTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCancelAppointmentTitle">Confirm Cancel Appointment</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel the appointment on <span id="appointmentDateCancel"></span><span id="appointmentTimeIDCancel" style="display: none;"></span>?
                    <form id="cancelAppointmentForm" method="post" action="../consultation/cancel_appointment.php">
                        <div class="form-group">
                            <label for="cancelReason">Reason for cancellation:</label>
                            <textarea class="form-control" id="cancelReason" name="cancelReason" rows="3"  required oninput="validateDescription()"></textarea>
                            <div id="descriptionError" style="color: red;"></div>
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

<!-- Bootstrap JS (jQuery and Popper.js required) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    // Description validation
    function validateDescription() {
        var description = document.getElementById("cancelReason").value;
        var alphaNumRegex = /^[a-z\d\-_\s]+$/i;
        var descriptionError = document.getElementById('descriptionError');
        var confirmButton = document.getElementById('confirmCancelAppointmentButton');

        if (!alphaNumRegex.test(description)) {
            descriptionError.textContent = 'Description must be alphanumeric and should not contain any special characters.';
            confirmButton.disabled = true;
            return false;
        } else {
            descriptionError.textContent = ''; // Clear the error message
            confirmButton.disabled = false;
            return true;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var appointmentReason = document.getElementById("cancelReason");
        if (appointmentReason) {
            appointmentReason.oninput = validateDescription;
        }
        // Initial call to disable the confirm button if textarea is empty
        validateDescription();
    });
</script>

    <script>
        function confirmCancelAppointment(schedule_date, schedule_day, time_id) {
        document.getElementById("appointmentDateCancel").textContent = schedule_date + " (" + schedule_day + ")";
        document.getElementById("appointmentTimeIDCancel").textContent = time_id;
        document.getElementById("cancelAppointmentForm").action = "../consultation/cancel_appointment.php?time_id=" + time_id;
        }

        function submitCancelAppointmentForm() {
            document.getElementById("cancelAppointmentForm").submit();
        }

    var appointmentData = {};

     // 实时监听输入框的文本变化，触发搜索
     $('#cancel_searchInput').on('input', function() {
            searchTable($(this).val(), 'cancel_subject_table');
        });

        // 搜索功能，根据输入的关键字过滤表格中的行
        function searchTable(keyword) {
        var $rows = $('tbody tr');
        $rows.hide();
        $rows.filter(function() {
            return $(this).text().toLowerCase().includes(keyword.toLowerCase());
        }).show();
    }
    </script>
</body>
</html>
