<?php
include_once '../database/db.php';
include_once "../validation/session.php";

// Fetch user details from the database
$usersession = $_SESSION['Staff_id'];
$res = mysqli_query($con, "SELECT * FROM staff WHERE staff_id='" . $usersession . "'"); // Updated table name and added quotes
$userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="../javascript/scripts.js"></script> -->
     <style>
    #clearButton {
        cursor: pointer;
        margin-left: 5px;
        border: none;
        background: none;
        padding: 0;
    }

    #clearIcon {
        color: red; /* Customize the icon color */
    }
</style>

<style>
    .button-spacing {
        margin-right: 10px; /* 调整按钮之间的间距 */
    }
</style>



<body class="p-3 m-0 border-0 bd-example m-0 border-0">
<?php include '../index/staff_navbar.php'; ?>
    <br>
<div class="card">
    <h4 class="card-header text-center">Student Booking Records</h4>
    <div class="card-body">
        <div class="table-responsive">
        <input type="text" id="searchInput" placeholder="Search...">
        <button id="clearButton" class="btn btn-danger">
            <i id="clearIcon" class="fa fa-trash"></i>
        </button>   
        <br><br>
        <table class="table table-bordered table-bm text-center">
            <thead class="table-dark">
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Schedule Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Modal</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                // Check if the staff_timeschedule table has a record with book_avail as 'booked'
                $checkQuery = "SELECT * FROM staff_timeschedule WHERE book_avail = 'booked' AND staff_id = '{$_SESSION['Staff_id']}'";
                $checkResult = mysqli_query($con, $checkQuery);
                $hasBookedSlot = mysqli_num_rows($checkResult) > 0;
                
                // Proceed only if there is a booked slot
                if ($hasBookedSlot) {
                    // Fetch appointments from student_bookings table
                    $query = "SELECT sb.*, s.student_name, sb.booking_id
                            FROM student_bookings sb
                            LEFT JOIN student s ON sb.student_id = s.student_id
                            JOIN staff_timeschedule st ON sb.staff_id = st.staff_id AND sb.time_id = st.time_id
                            WHERE sb.staff_id = '{$_SESSION['Staff_id']}'
                            AND sb.status = 'booked'
                            AND sb.time_id IS NOT NULL
                            AND st.book_avail = 'booked'
                            AND sb.booking_id = (SELECT MAX(booking_id) FROM student_bookings WHERE time_id = sb.time_id AND staff_id = '{$_SESSION['Staff_id']}')
                            ORDER BY sb.booking_id";    
                                     
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $cancelQuery = "SELECT * FROM cancel_stafftime WHERE time_id = '{$row['time_id']}'";
                        $cancelResult = mysqli_query($con, $cancelQuery);
                        $disableCancel = mysqli_num_rows($cancelResult) > 0; // If there's a record, disable the cancel button
                        echo "<tr>";
                        echo "<td>{$row['student_id']}</td>"; // Display time_id instead of booking_id
                        echo "<td>{$row['student_name']}</td>";
                        echo "<td>{$row['schedule_date']}</td>";
                        echo "<td>{$row['start_time']}</td>";
                        echo "<td>{$row['end_time']}</td>";
                        echo "<td>{$row['modal']}</td>";
                        echo "<td>{$row['status']}</td>";
                        echo "<td>{$row['reason']}</td>";
                        echo "<td class='d-flex justify-content-center align-items-center'>"; 

                        // Complete button
                        echo "<button class='btn btn-sm  btn-success complete-btn mt-2 me-3' data-time-id='{$row['time_id']}' data-booking-id='{$row['booking_id']}'>Complete</button> ";
                        // Cancel button
                        if ($disableCancel) {
                            echo "<button class='btn btn-sm btn-danger cancel-btn mt-2' data-time-id='{$row['time_id']}' disabled>Cancel</button>";
                        } else {
                            echo "<button class='btn btn-sm btn-danger cancel-btn mt-2' data-time-id='{$row['time_id']}'>Cancel</button>";
                        }                       
                        
                        echo "</td>"; // End Action cell
                        echo "</tr>";
                    }
                } else {
                    echo "<p>No booked slots found.</p>";
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
      <!-- Cancel Appointment Form -->
    <div class="modal fade" id="confirmCancelAppointment" tabindex="-1" aria-labelledby="confirmCancelAppointmentTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmCancelAppointmentTitle">Confirm Cancel Appointment</h5>
                    <!-- Optional close button -->
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel the appointment on <span id="appointmentDateCancel"></span><span id="appointmentTimeIDCancel" style="display: none;"></span>?
                    <form id="cancelAppointmentForm" method="post" action="../consultation/staff_cancel_appointment.php">
                        <input type="hidden" name="time_id" value="">
                        <div class="mb-3">
                            <label for="cancelReason" class="form-label">Reason for cancellation:</label>
                            <textarea class="form-control" id="cancelReason" name="cancelReason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="confirmCancelAppointmentButton" type="button" class="btn btn-danger" onclick="submitCancelAppointmentForm()">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    
<!-- Complete Appointment Form -->
<div class="modal fade" id="confirmCompleteAppointment" tabindex="-1" aria-labelledby="confirmCompleteAppointmentTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmCompleteAppointmentTitle">Confirm Complete Appointment</h5>
                <!-- Optional close button -->
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                Are you sure you want to complete the appointment with <span id="appointmentCompleteDate"></span>?
                <form id="completeAppointmentForm" method="post" action="../consultation/staff_complete_appointment.php">
                    <input type="hidden" name="time_id" id="appointmentCompleteTimeID" value="">
                    <input type="hidden" name="booking_id" id="appointmentBookingIDComplete" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="confirmCompleteAppointmentButton" type="button" class="btn btn-success" onclick="submitCompleteAppointmentForm()">Complete</button>
            </div>
        </div>
    </div>
</div>




    <!-- Bootstrap JS (jQuery and Popper.js required) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
        // Function to handle cancel button click
        $(document).on('click', '.cancel-btn', function() {
            var timeId = $(this).data('time-id'); // Get time_id from data-time-id attribute
            $('#confirmCancelAppointment').modal('show'); // Show the confirmation modal
            // Call the confirmCancelAppointment function with the appropriate data
            confirmCancelAppointment(
                $(this).closest('tr').find('td:eq(1)').text(), // Student Name
                $(this).closest('tr').find('td:eq(2)').text(), // Schedule Date
                timeId // Pass time_id
            );
        });

            $(document).on('click', '.complete-btn', function() {
                var timeId = $(this).data('time-id'); // Get time_id from data-time-id attribute
                var bookingId = $(this).data('booking-id'); // Get booking_id from data-booking-id attribute

                $('#confirmCompleteAppointment').modal('show'); // Show the confirmation modal

                // Call the confirmCompleteAppointment function with the appropriate data
                confirmCompleteAppointment(
                    timeId, 
                    bookingId, 
                    $(this).closest('tr').find('td:eq(2)').text(), // Schedule Date
                );
            });
        });

        function confirmCompleteAppointment(time_id, booking_id, schedule_date) {
            // Set hidden input values for the form
            document.getElementById("appointmentCompleteTimeID").textContent = time_id;
            document.getElementById("appointmentBookingIDComplete").textContent = booking_id;
            document.getElementById("appointmentCompleteDate").textContent = schedule_date;
            
            $('#completeAppointmentForm').attr('action', '../consultation/staff_complete_appointment.php?time_id=' + time_id + '&booking_id=' + booking_id );
        }


        function submitCompleteAppointmentForm() {
            var time_id = $('#appointmentTimeIDComplete').text(); // get time_id
            var booking_id = $('#appointmentBookingIDComplete').text(); // get booking_id
            var student_id = $('#appointmentStudentIDComplete').text(); // get student_id
            

            // Perform your actions here, such as validating data or displaying loading status

            // 设置表单的隐藏字段值
            $('#completeAppointmentForm input[name="time_id"]').val(time_id);
            $('#completeAppointmentForm input[name="booking_id"]').val(booking_id);
            $('#completeAppointmentForm input[name="student_id"]').val(student_id);

            // 提交表单
            $('#completeAppointmentForm').submit();
        }

        function confirmCancelAppointment(schedule_date, schedule_day, time_id) {
            document.getElementById("appointmentDateCancel").textContent = schedule_date + " (" + schedule_day + ")";
            document.getElementById("appointmentTimeIDCancel").textContent = time_id;
            $('#cancelAppointmentForm').attr('action', '../consultation/staff_cancel_appointment.php?time_id=' + time_id);
        }

        function submitCancelAppointmentForm() {
            var time_id = $('#appointmentTimeIDCancel').text();
            var cancelReason = $('#cancelReason').val();

            // Set the hidden input value for time_id
            $('#cancelAppointmentForm input[name="time_id"]').val(time_id);

            // Submit the form
            $('#cancelAppointmentForm').submit();
        }

        
        
    </script>
    </table>
    </div>
    </div>
<script>
 // 实时监听输入框的文本变化，触发搜索
 $('#searchInput').on('input', function() {
        searchTable($(this).val());
    });

    // Search function to filter table rows based on the keyword
        function searchTable(keyword) {
            var $rows = $('tbody tr');
            $rows.hide();
            $rows.filter(function() {
                return $(this).text().toLowerCase().includes(keyword.toLowerCase());
            }).show();
        }

        // Event listener for the search input
        $('#searchInput').on('input', function() {
            var keyword = $(this).val();
            searchTable(keyword);
        });

        // Event listener for the clear button
        $('#clearButton').on('click', function() {
            $('#searchInput').val(''); // Clear the search input
            $('tbody tr').show(); // Show all table rows
        });
</script>
</body>
</html>
