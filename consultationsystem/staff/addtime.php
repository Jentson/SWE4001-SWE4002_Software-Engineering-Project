<?php
session_start();
include_once '../database/dbconnect.php';

$usersession = $_SESSION['Staff_id']; // Updated variable name

$res = mysqli_query($con, "SELECT * FROM staff WHERE staff_id=" . $usersession); // Updated table name
$userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);

if (isset($_POST['submit'])) {
    //$staffID = isset($_POST['staffID']) ? mysqli_real_escape_string($con, $_POST['staffID']) : null;
    $staffID = $usersession;
    $date = mysqli_real_escape_string($con,$_POST['date']);
    $scheduleday= mysqli_real_escape_string($con,$_POST['scheduleday']);
    $starttime= mysqli_real_escape_string($con,$_POST['starttime']);
    $endtime= mysqli_real_escape_string($con,$_POST['endtime']);
    $bookavail= mysqli_real_escape_string($con,$_POST['bookavail']);
    $modal= mysqli_real_escape_string($con,$_POST['modal']);

//INSERT
$query = " INSERT INTO stafftimeschedule (  staffID, scheduleDate, scheduleDay, startTime, endTime,  bookAvail, modal)
VALUES ( '$staffID', '$date', '$scheduleday', '$starttime', '$endtime', '$bookavail', '$modal') ";

$result = mysqli_query($con, $query);
if( $result )
{
?>
<script type="text/javascript">
alert('Schedule added successfully.');
</script>
<?php
}
else
{
?>
<script type="text/javascript">
alert('Added fail. Please try again.');
</script>
<?php
}
// Check if the user wants to repeat for the next four weeks
$repeatWeekly = isset($_POST['repeat_weekly']) ? true : false;
if ($repeatWeekly) {
    generateTimeSlots($con,$staffID, $date, $scheduleday, $starttime, $endtime, $bookavail, $modal);
}
}
// Function to generate time slots for the next four weeks
function generateTimeSlots($con,$staffID, $date, $scheduleday, $starttime, $endtime, $bookavail, $modal) {
    // Loop through each week
    for ($i = 0; $i < 3; $i++) {
        // Calculate the date for the next occurrence of the selected day
        $nextDate = date('Y-m-d', strtotime($date . " +1 week"));

        // Insert time slot into the database
        $query = " INSERT INTO stafftimeschedule (  staffID, scheduleDate, scheduleDay, startTime, endTime,  bookAvail, modal)
        VALUES ( '$staffID', '$nextDate', '$scheduleday', '$starttime', '$endtime', '$bookavail' , '$modal') ";

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
        <!-- <link href="assets/css/bootstrap.css" rel="stylesheet"> -->
        <link href="../css/material.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="../css/sb-admin.css" rel="stylesheet">
        <link href="../css/bootstrap-clockpicker.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <link href="../css/font-awesome.css" rel="stylesheet">
        <!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
        <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" /> 

        <!--Font Awesome (added because you use icons in your prepend/append)-->
        <link rel="stylesheet" href="https://formden.com/static/cdn/font-awesome/4.4.0/css/font-awesome.min.css" />

        <!-- Inline CSS based on choices in "Settings" tab -->
        <style>.bootstrap-iso .formden_header h2, .bootstrap-iso .formden_header p, .bootstrap-iso form{font-family: Arial, Helvetica, sans-serif; color: black}.bootstrap-iso form button, .bootstrap-iso form button:hover{color: white !important;} .asteriskField{color: red;}</style>

        <!-- Custom Fonts -->
        <script>
    $(document).ready(function(){
        $('#date').change(function(){
            var selectedDate = new Date($(this).val());
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var selectedDay = days[selectedDate.getDay()];
            $('#scheduleday').val(selectedDay);
        });
    });
</script>
</head>
<body>
<a href="../staff/staffDashboard.php"><button>Dashboard</button></a>
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
                                   <select class="select form-control" id="scheduleday" name="scheduleday" required readonly>
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
                                        Availability
                                        <span class="asteriskField">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <select class="select form-control" id="bookavail" name="bookavail" required>
                                            <option value="available" selected>Available</option>
                                            <option value="booked">Booked</option>
                                        </select>
                                    </div>
                                </div>
                                 <div class="form-group form-group-lg">
                                  <label class="control-label col-sm-2 requiredField" for="modal">
                                   Modal
                                   <span class="asteriskField">
                                    *
                                   </span>
                                  </label>
                                  <div class="col-sm-10">
                                   <select class="select form-control" id="modal" name="modal" required>
                                    <option value="F2F">
                                     Face-to-Face
                                    </option>
                                    <option value="Online">
                                     Online
                                    </option>
                                    <option value="Both">
                                     Either F2F or OL
                                    </option>
                                   </select>
                                  </div>
                                 </div>
                                 <div class="form-group">
                                  <div class="col-sm-10 col-sm-offset-2">
                                  <div class="checkbox">
                                    <label>
                                    <input type="checkbox" id="repeat_weekly" name="repeat_weekly"> Repeat weekly for the next four weeks
                                    </label>
                                </div>
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
                    <div class="panel-body">
                        <!-- panel content start -->
                           <!-- Table -->
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="filters">
                                    <th><input type="text" class="form-control" placeholder="timeID" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="staffID" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="scheduleDate" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="scheduleDay" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="startTime" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="endTime" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="bookAvail" disabled></th>
                                    <th><input type="text" class="form-control" placeholder="modal" disabled></th>
                                </tr>
                            </thead>
                            
                            <?php 
                            $query_select = "SELECT * FROM stafftimeschedule WHERE staffID = '$usersession'";
                            $result_select = mysqli_query($con, $query_select);
                            $result=mysqli_query($con,"SELECT * FROM stafftimeschedule");
                            

                                  
                            while ($stafftimeschedule = mysqli_fetch_array($result_select)) {
                                
                              
                                echo "<tbody>";
                                echo "<tr>";
                                    echo "<td>" . $stafftimeschedule['timeID'] . "</td>";
                                    echo "<td>" . $stafftimeschedule['staffID'] . "</td>";
                                    echo "<td>" . $stafftimeschedule['scheduleDate'] . "</td>";
                                    echo "<td>" . $stafftimeschedule['scheduleDay'] . "</td>";
                                    echo "<td>" . $stafftimeschedule['startTime'] . "</td>";
                                    echo "<td>" . $stafftimeschedule['endTime'] . "</td>";
                                    echo "<td>" . $stafftimeschedule['bookAvail'] . "</td>";
                                    echo "<td>" . $stafftimeschedule['modal'] . "</td>";
                                    echo "<td class='text-center'>";
                                    echo "<form method='POST'>";
                                    echo "<input type='hidden' name='timeID' value='".$stafftimeschedule['timeID']."' />";
                                    echo "<button type='submit' class='delete btn btn-link'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></button>";
                                    echo "</form>";
                                    echo "</td>";
                               
                            } 
                                echo "</tr>";
                           echo "</tbody>";
                       echo "</table>";
                       echo "<div class='panel panel-default'>";
                       echo "<div class='col-md-offset-3 pull-right'>";
                       echo "<button class='btn btn-primary' type='submit' value='Submit' name='submit'>Update</button>";
                        echo "</div>";
                        echo "</div>";
                            
                        ?>
                        <!-- panel content end -->
                        <!-- panel end -->
                        </div>
                    </div>
                    <!-- panel start -->
                </div>
            </div>
        <!-- /#wrapper -->
        <script src="../javascript/jquery.js"></script>
        
        <!-- Bootstrap Core JavaScript -->
        <script src="../javascript/bootstrap.min.js"></script>
        <script src="../javascript/bootstrap-clockpicker.js"></script>
        <!-- Latest compiled and minified JavaScript -->
         <!-- script for jquery datatable start-->
        <!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<script>
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
<script type="text/javascript">
$(function() {
    $(".delete").click(function(){
        var element = $(this);
        var id = element.closest('form').find('input[name="timeID"]').val();
        var info = 'id=' + id;
        if(confirm("Are you sure you want to delete this?")) {
            $.ajax({
                type: "POST",
                url: "../staff/deleteschedule.php",
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
</body>
</html>