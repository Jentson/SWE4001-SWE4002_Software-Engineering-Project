<?php
include_once "../validation/session.php";
include_once '../database/db.php';

$usersession = $_SESSION['Staff_id']; // Updated variable name

$res = mysqli_query($con, "SELECT * FROM staff WHERE staff_id='" . $usersession . "'"); // Updated table name and added quotes
$userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);


// Fetch students data for the dropdown
$studentsQuery = "SELECT student_id, student_name FROM student";
$studentsResult = mysqli_query($con, $studentsQuery);
$students = [];
if ($studentsResult) {
    while ($row = mysqli_fetch_assoc($studentsResult)) {
        $students[] = $row;
    }
}

if (isset($_POST['submit'])) {
    //$staff_id = isset($_POST['staff_id']) ? mysqli_real_escape_string($con, $_POST['staff_id']) : null;
    $staff_id = $usersession;
    $date = mysqli_real_escape_string($con,$_POST['date']);
    if (isset($_POST['scheduleday'])) {
        // use mysqli_real_escape_string defen sql injection
        $scheduleday = mysqli_real_escape_string($con, $_POST['scheduleday']);
    }else {
        echo "Scheduleday data not received";
    }
    $starttime= mysqli_real_escape_string($con,$_POST['starttime']);
    $endtime= mysqli_real_escape_string($con,$_POST['endtime']);
    $book_avail= mysqli_real_escape_string($con,$_POST['book_avail']);
    $modal= mysqli_real_escape_string($con,$_POST['modal']);

//INSERT
// Check if there are any existing time slots that overlap with the new time slot
$overlapQuery = "SELECT * FROM staff_timeschedule WHERE staff_id = '$staff_id' AND schedule_date = '$date' AND schedule_day = '$scheduleday' AND ((start_time >= '$starttime' AND start_time < '$endtime') OR (end_time > '$starttime' AND end_time <= '$endtime') OR (start_time <= '$starttime' AND end_time >= '$endtime')) AND book_avail != 'cancel'";
$overlapResult = mysqli_query($con, $overlapQuery);
if (mysqli_num_rows($overlapResult) > 0) {
    // Display an error message if there is an overlap
    echo "<script>alert('Time slot overlaps with existing slot. Please select a different time.');</script>";
} else {
    // Insert the new time slot into the database
    $query = "INSERT INTO staff_timeschedule (staff_id, schedule_date, schedule_day, start_time, end_time, book_avail, modal) VALUES ('$staff_id', '$date', '$scheduleday', '$starttime', '$endtime', '$book_avail', '$modal')";
    $result = mysqli_query($con, $query);
    if ($result) {
        $time_id = mysqli_insert_id($con);
        
        if ($book_avail == 'booked') {
            $student_info = explode('|', $_POST['student_id']);
            $student_id = mysqli_real_escape_string($con, $student_info[0]);
            $student_name = mysqli_real_escape_string($con, $student_info[1]);
            

            $insertHistoryQuery = "INSERT INTO appointment_history 
                                   (time_id, student_id, student_name, schedule_date, start_time, end_time, modal, status, staff_id, reason, booking_timestamp) 
                                   VALUES 
                                   ('$time_id', '$student_id', '$student_name', '$date', '$starttime', '$endtime', '$modal', 'booked', '$staff_id', 'replacement', NOW())";
            $insertHistoryResult = mysqli_query($con, $insertHistoryQuery);

            $bookingQuery = "INSERT INTO student_bookings 
                             (student_id, staff_id, schedule_date, start_time, end_time, modal, status, reason, booking_time, time_id) 
                             VALUES 
                             ('$student_id', '$staff_id', '$date', '$starttime', '$endtime', '$modal', 'booked', 'replacement', NOW(), '$time_id')";
            $bookingResult = mysqli_query($con, $bookingQuery);

            if ($insertHistoryResult && $bookingResult) {
                echo "<script>alert('Schedule added and booked successfully.');</script>";
            } else {
                echo "<script>alert('Failed to add booking details. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Schedule added successfully.');</script>";
        }
    } else {
        // Provide feedback to the user in case of failure
        echo "<script>alert('Failed to add schedule. Please try again.');</script>";
    }

    // Check if the user wants to repeat for the next four weeks
    if (isset($_POST['repeat_weekly'])) {
        $repeat_weeks = intval($_POST['repeat_weeks']);
        generateTimeSlots($con, $staff_id, $date, $scheduleday, $starttime, $endtime, $book_avail, $modal, $repeat_weeks);
    }
}
}
// Function to generate time slots for the next four weeks
function generateTimeSlots($con, $staff_id, $date, $scheduleday, $starttime, $endtime, $book_avail, $modal, $repeat_weeks) {
    for ($i = 0; $i < $repeat_weeks - 1; $i++) {
        $nextDate = date('Y-m-d', strtotime($date . " +1 week"));

        $query = "INSERT INTO staff_timeschedule (staff_id, schedule_date, schedule_day, start_time, end_time, book_avail, modal) VALUES ('$staff_id', '$nextDate', '$scheduleday', '$starttime', '$endtime', '$book_avail', '$modal')";
        $result = mysqli_query($con, $query);

        $displayMessages = false; // Set this to true to display messages, false to hide them

        if ($result) {
            if ($displayMessages) {
                echo "Time slots for $scheduleday on $nextDate have been generated.<br>";
            }
        } else {
            if ($displayMessages) {
                echo "Failed to generate time slots for $scheduleday on $nextDate.<br>";
            }
        }

        $date = $nextDate;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
 
<!-- Navbar -->
<?php include '../index/staff_navbar.php'; ?>

<!-- Page content-->

    <div class="container">
    <br>
    <h4>Add Booking Slots</h4>
    <form method="post" class="row g-3">
        <div class="col-md-3">
            <label for="inputDate" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" id="inputDate" value="" required>
        </div>

        <div class="col-md-3">
            <label for="inputDay" class="form-label">Day</label>
            <input type="text" name="scheduleday" readonly class="form-control" id="inputDay" value="">
        </div>
        <!-- set min="08:00" max="18:00" to start time-->
        <div class="col-md-3">
            <label for="inputStartTime" class="form-label">Start Time</label>
            <input type="time" class="form-control" name="starttime" id="inputStartTime" value="" min="08:00" max="21:50" required>
        </div>
        <!-- set min="08:00" max="18:00" to end time-->
        <div class="col-md-3">
            <label for="inputEndTime" class="form-label">End Time</label>
            <input type="time" class="form-control" name="endtime" id="inputEndTime" value="" min="08:10" max="21:50" required>
        </div>

        <div class="col-md-6">
            <div class="mb-6">
                <label for="inputAvailability" class="form-label">Availability</label>
                <select id="inputAvailability" name="book_avail" class="form-select form-select-sm" aria-label="Small select example" onchange="toggleStudentSelect()" required>
                    <option value="" selected disabled>Select...</option>
                    <option value="available">Available</option>
                    <option value="booked">Booked</option>
                </select>
            </div>
            <br>
            <div id="studentSelect" class="mb-3" style="display: none;">
                <label for="inputStudent" class="form-label">Select Student ID/Student Name</label>
                <select id="inputStudent" name="student_id" class="form-select form-select-sm">
                    <?php
                    foreach ($students as $student) {
                        echo '<option value="' . $student['student_id'] . '|' . $student['student_name'] . '">' . $student['student_id'] . ' - ' . $student['student_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <label for="inputModal" class="form-label" >Model</label>
            <select class="form-select form-select-sm" name="modal" id="inputModal" aria-label="Small select example" required>
                <option value="" selected disabled>Select...</option>
                <option value="Online">Online</option>
                <option value="F2F">Face to Face</option>
                <option value="Both">Either F2F or OL</option>
            </select>
        </div>

        <div class="col-md-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="repeat_weekly" name="repeat_weekly">
                <label class="form-check-label" for="repeat_weekly">
                    Repeat weekly for the next weeks
                </label>
                <select name="repeat_weeks" id="repeat_weeks" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                    <?php for ($i = 1; $i <= 16; $i++) {
                        echo "<option value=\"$i\">$i</option>";
                    } ?>
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <button id="submitBtn" class="btn btn-sm btn-primary" name="submit" type="submit">
                Add
            </button>
        </div>
    </form>
</div>
                 
                </div>
            </div>
 
        </div>
            </div>
        </div> 
        <!-- Bootstrap core JS-->
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script> -->
        <!-- Core theme JS-->
        <!-- <script src="../javascripts/scripts.js"></script> -->
        <!-- <script src="../javascript/jquery.js"></script> -->
        
        <!-- Bootstrap Core JavaScript -->
        <!-- <script src="../javascript/bootstrap.min.js"></script>
        <script src="../javascript/bootstrap-clockpicker.js"></script> -->
        <!-- Latest compiled and minified JavaScript -->
         <!-- script for jquery datatable start-->
        <!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script>

    // Automatically display date according to the date selection
        document.getElementById('inputDate').addEventListener('change', function() {
            var inputDate = new Date(this.value);
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var dayName = days[inputDate.getUTCDay()];
            document.getElementById('inputDay').value = dayName;
        });

        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.getElementById('inputDate');
            var today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
        });


    $(document).ready(function(){
    $('#date').change(function(){
        var selectedDate = new Date($(this).val());
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var selectedDayIndex   = days[selectedDate.getDay()];
        $('#scheduleday').text(selectedDayIndex);
        $('#scheduleday').val(selectedDayIndex);

        $.ajax({
            url: 'addtime.php',
            type: 'POST',
            data: {scheduleday: selectedDayIndex},
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

</script>

<script type="text/javascript">
    // JavaScript code for time selection
$(document).ready(function() {
    $('#starttime').change(function() {
        $('#endtime').attr('min', this.value);
    });

    $('#endTime').change(function() {
        if (this.value <= $('#starttime').val()) {
            alert('End time must be after start time.');
            this.value = '';
        }
    });

    $('#submit').click(function() {
        let starttime = $('#starttime').val();
        let endtime = $('#endtime').val();

        if (!starttime || !endtime) {
            alert('Please select both start time and end time.');
            return;
        }

        alert('Start Time: ' + starttime + '\nEnd Time: ' + endtime);
    });
});


function toggleStudentSelect() {
    var availability = document.getElementById("inputAvailability").value;
    var studentSelect = document.getElementById("studentSelect");
    if (availability === "booked") {
        studentSelect.style.display = "block";
    } else {
        studentSelect.style.display = "none";
    }
}
</script>
<script>
    $(document).ready(function() {
        $('#book_avail').change(function() {
            var selectedAvailability = $(this).val();
            if (selectedAvailability === 'booked') {
                // If booked, only allow Face-to-Face
                $('#modal').html('<option value="F2F">Face-to-Face</option><option value="Online">Online</option>');
            } else {
                // If available, allow all options
                $('#modal').html('<option value="F2F">Face-to-Face</option><option value="Online">Online</option><option value="Both">Either F2F or OL</option>');
            }
        });
    });
</script>
<script>
        // JavaScript code for repeating time slots
        $(document).ready(function() {
            $('#repeat_weekly').change(function() {
                if (this.checked) {
                    // Show the input fields for repeating time slots
                    $('.repeat-options').show();
                } else {
                    // Hide the input fields for repeating time slots
                    $('.repeat-options').hide();
                }
            });
        });
    </script>
<script type="text/javascript">
$(function() {
    $(".delete").click(function(){
        var element = $(this);
        var id = element.closest('form').find('input[name="time_id"]').val();
        var info = 'id=' + id;
        if(confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: "POST",
                url: "../staff/staff_deleteschedule.php",
                data: info,
                success: function(response){
                    // Perform any action after successful deletion, if needed
                    console.log(response); // Output server response to console for debugging
                    element.closest('tr').fadeOut(300, function(){ $(this).remove();});
                }
            });
        }
        return false;
    });
});
</script>
</div>
</body>
</html>