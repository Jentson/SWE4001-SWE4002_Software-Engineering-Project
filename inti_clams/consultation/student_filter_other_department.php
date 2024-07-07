<?php
include '../validation/session.php'; 
include '../validation/checkingconsultation.php'; 


$usersession = $_SESSION['student_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
       <!-- Bootstrap CSS --> 
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <title>Main Dashboard</title>
</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">

 <!-- Navbar -->
 <?php include '../index/student_navbar.php'; ?>
 
<br>

<div class="card">

            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
    
                <!-- Page content-->
                <div class="container-fluid">
                <h3 class ="text-center">Available Slot</h3>
                <div class="card-body">
                <div class="table-responsive">
            <table class="table table-sm table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Lecturer Name</th>
                        <th>Schedule Date</th>
                        <th>Schedule Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Model</th>
                        <th>Availability</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
    <?php
        include_once '../database/db.php';

        // Check if the form is submitted
        $staffResult = null;

        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['selected_other_staff_id'])) {
            $selectedStaffId = $_GET['selected_other_staff_id'];
            // echo "Other Program or Other Department "; // Add this line for debugging
            // Fetch staff's available time schedule for the selected staff ID
            $staffQuery = "
            SELECT st.*, s.staff_name 
            FROM staff_timeschedule st
            JOIN staff s ON st.staff_id = s.staff_id
            WHERE st.staff_id = '$selectedStaffId' 
            AND st.book_avail = 'available'
            AND (st.schedule_date > NOW() OR (st.schedule_date = CURDATE() AND st.end_time > CURTIME()))
            ORDER BY st.schedule_date ASC
            ";            
            $staffResult = mysqli_query($con, $staffQuery);
            if (!$staffResult) {
                die('Error: ' . mysqli_error($con));
            }
        }

        // Display the staff's available time schedule
        while ($staffRow = mysqli_fetch_array($staffResult)) {
            echo "<tr>";
            echo "<td>" . $staffRow['staff_name'] . "</td>";
            echo "<td>" . $staffRow['schedule_date'] . "</td>";
            echo "<td>" . $staffRow['schedule_day'] . "</td>";
            echo "<td>" . $staffRow['start_time'] . "</td>";
            echo "<td>" . $staffRow['end_time'] . "</td>";
            echo "<td>" . $staffRow['modal'] . "</td>";
            echo "<td class='" . ($staffRow['book_avail'] == 'booked' ? 'booked' : '') . "'>" . $staffRow['book_avail'] . "</td>";
            if ($staffRow['book_avail'] == 'available') {
                echo "<td>
                <a href='#' class='btn btn-success btn-sm' onclick='confirmMakeAppointment(\"" . $staffRow['schedule_date'] . "\", \"" . $staffRow['schedule_day'] . "\", \"" . $staffRow['time_id'] . "\", \"" . $staffRow['modal'] . "\")' 
                data-bs-toggle='modal' data-bs-target='#confirmMakeAppointmentModal'>Make Appointment</a>
            </td>";
            
            } else if($staffRow['book_avail'] == 'cancel'){
                echo "<td><a href='../student/student_cancel_staffreason.php'/><span class='cancel_staffreason_text'>Appointment been cancelled by lecturer</span></td>";
            }
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
        </div>
    </div>
</div>

<!-- Modal Make Appointment -->
<div class="modal fade" id="confirmMakeAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="confirmMakeAppointmentTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmMakeAppointmentTitle">Confirm Appointment</h5>
            </div>
            <div class="modal-body">
                <p id="appointmentInfo">Modal body text goes here.</p>
                <textarea id="appointmentReason" class="form-control" placeholder="Enter reason for the appointment" required oninput="validateDescription()"></textarea>
                </div>
                <div class="form-group form-group-lg" id="student_modal_group" style="display: none;">
                    <label class="control-label col-sm-2 requiredField" for="student_modal"></label>
                    <div class="col-sm-10">
                        <select class="select form-control" id="student_modal" name="student_modal" required>
                            <option value="">Please Select Model</option>
                            <option value="F2F">Face-to-Face</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmButton" class="btn btn-primary" onclick="makeAppointment()">Confirm</button>
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
        var description = document.getElementById("remarks").value;
        var alphaNumRegex = /^[a-z\d\-_\s]+$/i;
        var descriptionError = document.getElementById('descriptionError');
        var approveButton = document.getElementById('approve_button');

        if (!descriptionError) {
            descriptionError = document.createElement('div');
            descriptionError.id = 'descriptionError';
            descriptionError.style.color = 'red';
            var formGroup = document.querySelector('.form-group');
            formGroup.parentNode.insertBefore(descriptionError, formGroup.nextSibling);
        }

        if (description === "") {
            descriptionError.style.display = 'none';
            approveButton.disabled = true;
            rejectButton.disabled = true;
            return;
        }

        if (!alphaNumRegex.test(description)) {
            descriptionError.textContent = 'Description must be alphanumeric and should not contain any special characters.';
            approveButton.disabled = true;
            rejectButton.disabled = true;
            descriptionError.style.display = 'block';
            return false;
        } else {
            descriptionError.textContent = ''; // Clear the error message
            approveButton.disabled = false;
            rejectButton.disabled = false;
            descriptionError.style.display = 'none';
            return true;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var remarks = document.getElementById("remarks");
        if (remarks) {
            remarks.oninput = validateDescription;
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

    function confirmMakeAppointment(time_id, schedule_date, schedule_day, modal) {
        appointmentData = {
            time_id: time_id,
            schedule_date: schedule_date,
            schedule_day: schedule_day,
            modal:modal
        };
        $('#appointmentInfo').text(`Are you sure you want to make an appointment on ${time_id} (${schedule_date})?`);
        if (modal === 'Both') {
            $('#student_modal_group').show();
        } else {
            $('#student_modal_group').hide();
        }

    $('#confirmMakeAppointmentModal').modal('show');
}

function makeAppointment() {
    var reason = $('#appointmentReason').val();
    if (!reason) {
        alert('Please enter a reason for the appointment.');
        return;
    }

    // Check if the staff modal is 'Both'
    if (appointmentData.modal === 'Both') {
        // Check if student modal selection is made
        var studentModal = $('#student_modal').val();
        if (!studentModal) {
            alert('Please select F2F or Online.');
            return;
        }

        // Proceed with making appointment including student modal selection
        var schedule_day = appointmentData.schedule_day;
        var url = "check_appointment.php?time_id=" + schedule_day + "&makeReason=" + encodeURIComponent(reason) + "&student_modal=" + studentModal;
        window.location.href = url;
    } else {
        // Proceed with making appointment without student modal selection
        var schedule_day = appointmentData.schedule_day;
        var url = "check_appointment.php?time_id=" + schedule_day + "&makeReason=" + encodeURIComponent(reason);
        window.location.href = url;
    }
}


</script>
</div>
</body>
</html>