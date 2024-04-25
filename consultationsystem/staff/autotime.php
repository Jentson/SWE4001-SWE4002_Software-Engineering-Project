<?php
session_start();
include_once '../database/dbconnect.php';

$usersession = $_SESSION['Staff_id']; // Updated variable name

$res = mysqli_query($con, "SELECT * FROM staff WHERE staff_id=" . $usersession); // Updated table name
$userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);

if (isset($_POST['submit'])) {
    $staffID = isset($_POST['staffID']) ? mysqli_real_escape_string($con, $_POST['staffID']) : null;
    $date = mysqli_real_escape_string($con,$_POST['date']);
    $scheduleday  = mysqli_real_escape_string($con,$_POST['scheduleday']);
    $starttime     = mysqli_real_escape_string($con,$_POST['starttime']);
    $endtime     = mysqli_real_escape_string($con,$_POST['endtime']);
    $bookavail         = mysqli_real_escape_string($con,$_POST['bookavail']);

    //INSERT
    $query = " INSERT INTO stafftimeschedule (  staffID, scheduleDate, scheduleDay, startTime, endTime,  bookAvail)
    VALUES ( '$staffID', '$date', '$scheduleday', '$starttime', '$endtime', '$bookavail' ) ";

    $result = mysqli_query($con, $query);
    if ($result) {
        ?>
        <script type="text/javascript">
            alert('Schedule added successfully.');
        </script>
        <?php
    } else {
        ?>
        <script type="text/javascript">
            alert('Added fail. Please try again.');
        </script>
        <?php
    }

    // Check if the user wants to repeat for the next four weeks
    $repeatWeekly = isset($_POST['repeat_weekly']) ? true : false;
    if ($repeatWeekly) {
        generateTimeSlots($con,$staffID, $date, $scheduleday, $starttime, $endtime, $bookavail);
    }
}

// Function to generate time slots for the next four weeks
function generateTimeSlots($con,$staffID, $date, $scheduleday, $starttime, $endtime, $bookavail) {
    // Loop through each week
    for ($i = 0; $i < 4; $i++) {
        // Calculate the date for the next occurrence of the selected day
        $nextDate = date('Y-m-d', strtotime($date . " +1 week"));

        // Insert time slot into the database
        $query = " INSERT INTO stafftimeschedule (  staffID, scheduleDate, scheduleDay, startTime, endTime,  bookAvail)
        VALUES ( '$staffID', '$nextDate', '$scheduleday', '$starttime', '$endtime', '$bookavail' ) ";

        $result = mysqli_query($con, $query);
        if ($result) {
            // Provide feedback to the user indicating that the time slots have been successfully generated
            echo "Time slots for $scheduleday on $nextDate have been generated.<br>";
        } else {
            // Provide feedback to the user in case of failure
            echo "Failed to generate time slots for $scheduleday on $nextDate.<br>";
        }

        // Update date for the next week
        $date = $nextDate;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Welcome Dear <?php echo $userRow['staff_id'];?> <?php echo $userRow['staff_name'];?></title>
    <!-- Bootstrap Core CSS -->
    <link href="../css/material.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link href="../css/bootstrap-clockpicker.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/font-awesome.css" rel="stylesheet">
    <!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" /> 
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/font-awesome/4.4.0/css/font-awesome.min.css" />
    <!-- Inline CSS based on choices in "Settings" tab -->
    <style>.bootstrap-iso .formden_header h2, .bootstrap-iso .formden_header p, .bootstrap-iso form{font-family: Arial, Helvetica, sans-serif; color: black}.bootstrap-iso form button, .bootstrap-iso form button:hover{color: white !important;} .asteriskField{color: red;}</style>
</head>
<body>
    <div id="wrapper">
        <div class="panel-heading">
            <h3 class="panel-title">Add Schedule</h3>
        </div>
        <!-- panel heading end -->
        <div class="panel-body">
            <!-- panel content start -->
            <div class="bootstrap-iso">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <form class="form-horizontal" method="post">
                                <!-- Form fields for adding time slot -->
                                <!-- This part is same as your existing HTML -->
                                <div class="form-group form-group-lg">
                                  <label class="control-label col-sm-2 requiredField" for="date">
                                   Date
                                   <span class="asteriskField">
                                    *
                                   </span>
                                  </label>
                                  <div class="col-sm-10">
                                   <div class="input-group">
                                    <div class="input-group-addon">
                                     <i class="fa fa-calendar">
                                     </i>
                                    </div>
                                    <input class="form-control" id="date" name="date" type="text" required/>
                                   </div>
                                  </div>
                                 </div>
                                 <div class="form-group form-group-lg">
                                  <label class="control-label col-sm-2 requiredField" for="scheduleday">
                                   Day
                                   <span class="asteriskField">
                                    *
                                   </span>
                                  </label>
                                  <div class="col-sm-10">
                                   <select class="select form-control" id="scheduleday" name="scheduleday" required>
                                    <option value="Monday">
                                     Monday
                                    </option>
                                    <option value="Tuesday">
                                     Tuesday
                                    </option>
                                    <option value="Wednesday">
                                     Wednesday
                                    </option>
                                    <option value="Thursday">
                                     Thursday
                                    </option>
                                    <option value="Friday">
                                     Friday
                                    </option>
                                    <option value="Saturday">
                                     Saturday
                                    </option>
                                    <option value="Sunday">
                                     Sunday
                                    </option>
                                   </select>
                                  </div>
                                 </div>
                                 <div class="form-group form-group-lg">
                                  <label class="control-label col-sm-2 requiredField" for="starttime">
                                   Start Time
                                   <span class="asteriskField">
                                    *
                                   </span>
                                  </label>

                                  <div class="col-sm-10">
                                   <div class="input-group clockpicker"  data-align="top" data-autoclose="true">
                                    <div class="input-group-addon">
                                     <i class="fa fa-clock-o">
                                     </i>
                                    </div>
                                    <input class="form-control" id="starttime" name="starttime" type="text" required/>
                                   </div>
                                  </div>
                                 </div>
                                 <div class="form-group form-group-lg">
                                  <label class="control-label col-sm-2 requiredField" for="endtime">
                                   End Time
                                   <span class="asteriskField">
                                    *
                                   </span>
                                  </label>
                                  <div class="col-sm-10">
                                   <div class="input-group clockpicker"  data-align="top" data-autoclose="true">
                                    <div class="input-group-addon">
                                     <i class="fa fa-clock-o">
                                     </i>
                                    </div>
                                    <input class="form-control" id="endtime" name="endtime" type="text" required/>
                                   </div>
                                  </div>
                                 </div>
                                 <div class="form-group form-group-lg">
                                  <label class="control-label col-sm-2 requiredField" for="bookavail">
                                   Availabilty
                                   <span class="asteriskField">
                                    *
                                   </span>
                                  </label>
                                  <div class="col-sm-10">
                                   <select class="select form-control" id="bookavail" name="bookavail" required>
                                    <option value="available">
                                     available
                                    </option>
                                    <option value="notavail">
                                     notavail
                                    </option>
                                   </select>
                                  </div>
                                 </div>
                                 <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label>
                <input type="checkbox" id="repeat_weekly" name="repeat_weekly"> Repeat weekly for the next four weeks
            </label>
        </div>
    </div>
</div>
                                 <div class="form-group">
                                  <div class="col-sm-10 col-sm-offset-2">
                                   <button class="btn btn-primary " name="submit" type="submit">
                                    Submit
                                   </button>
                                  </div>
                                 </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- panel content end -->
            <!-- panel end -->
        </div>
    </div>

    <!-- JavaScript libraries -->
    <script src="../javascript/jquery.js"></script>
    <script src="../javascript/bootstrap.min.js"></script>
    <script src="../javascript/bootstrap-clockpicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script>
        // JavaScript code for datepicker and clockpicker
        // This part is same as your existing JavaScript
        $(document).ready(function(){
        var date_input=$('input[name="date"]'); //our date input has the name "date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'yyyy/mm/dd',
            container: container,
            todayHighlight: true,
            autoclose: true,
        }).on('changeDate', function(e) {
        // Get the selected date
        var selectedDate = e.format();
        
        // Get the day of the week for the selected date
        var selectedDay = new Date(selectedDate).toLocaleString('en-us', {  weekday: 'long' });

        // Update the dropdown to match the selected day
        $('#scheduleday').val(selectedDay);
    });
});
    </script>
    <script type="text/javascript">
    $('.clockpicker').clockpicker();
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
</body>
</html>

