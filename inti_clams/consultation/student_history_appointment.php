<?php

include_once '../database/db.php';
include '../validation/session.php'; 

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    header("Location: ../validation/login.php");
    exit;
}

$usersession = $_SESSION['student_id'];

$query = "SELECT ah.*, s.staff_name 
          FROM appointment_history ah
          JOIN staff s ON ah.staff_id = s.staff_id
          WHERE ah.student_id = '$usersession'";
$result = mysqli_query($con, $query);

// Function to get the appropriate class for status
function getStatusClass($status) {
    switch ($status) {
        case 'booked':
            return 'green';
        case 'cancelled':
            return 'red';
        case 'cancel_by_staff':
            return 'red';
        default:
            return 'default';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History</title>
    <!-- Include Bootstrap CSS and any other CSS files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <style>
    .status-box {
        padding: 4px 8px;
        border-radius: 4px;
        color: white;
        display: inline-block;
    }
    .status-box.green {
        background-color: green;
    }
    .status-box.red {
        background-color: red;
    }
    .status-box.default {
        background-color: grey;
    }
</style>

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

</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">
 
<!-- Navbar -->
<?php include '../index/student_navbar.php'; ?>
<br>
<div class="card">
        <div class="container-fluid">
        <h4 class ="text-center">Appointment History</h4>
        <hr>
        <div class="card-body">
        <div class="table-responsive">
         <!-- <div class="container1"> -->
        <input type="text" id="searchInput" placeholder="Search...">
        <button id="clearButton" class="btn btn-danger">
            <i id="clearIcon" class="fa fa-trash"></i>
        </button>        
        <br><br>

      
        <table class="table table-bordered table-sm text-center">
            <thead class= "table-dark">
                <tr>
                <!-- <th data-column="student_id">
                    Student ID
                <button class="search-button" onclick="showSearchInput('student_id')">🔍</button>
                </th>

                <th data-column="student_name">
                    Student Name
                <button class="search-button" onclick="showSearchInput('student_name')">🔍</button>
                </th> -->
                <th data-column="lecturer_name">
                Lecturer Name
                    <button class="search-button" onclick="showSearchInput('lecturer_name')" style="background-color:#212529; color: white;">🔍</button>
                </th>
                
                <th data-column="schedule_date">
                Schedule Date
                <button class="search-button" onclick="showSearchInput('schedule_date')" style="background-color:#212529; color: white;">🔍</button>
                </th>

                <th data-column="start_time">
                Start Time
                <button class="search-button" onclick="showSearchInput('start_time')" style="background-color:#212529; color: white;">🔍</button>
                </th>

                <th data-column="end_time">
                End Time 
                <button class="search-button" onclick="showSearchInput('end_time')" style="background-color:#212529; color: white;">🔍</button>
                </th>

                <th data-column="modal">
                Model
                <button class="search-button" onclick="showSearchInput('modal')" style="background-color:#212529; color: white;">🔍</button>
                </th>

                <th data-column="book_avail">
                    Status
                <button class="search-button" onclick="showSearchInput('book_avail')" style="background-color:#212529; color: white;">🔍</button> 
                </th>

                <th data-column="reason">
                    Reason
                    <button class="search-button" onclick="showSearchInput('reason')" style="background-color:#212529; color: white;">🔍</button>
                </th>
                <!-- 
                <th data-column="booking_timestamp">
                    Booking Timestamp 
                    <button class="search-button" onclick="showSearchInput('booking_timestamp')">🔍</button>
                </th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        // echo "<td>" . $row['student_id'] . "</td>";
                        // echo "<td>" . $row['student_name'] . "</td>";
                        echo "<td>" . $row['staff_name'] . "</td>";
                        echo "<td>" . $row['schedule_date'] . "</td>";
                        echo "<td>" . $row['start_time'] . "</td>";
                        echo "<td>" . $row['end_time'] . "</td>";
                        echo "<td>" . $row['modal'] . "</td>";
                        echo '<td><div class="status-box ' . getStatusClass($row['status']) . '">' . $row['status'] . '</div></td>';
                        echo "<td>" . $row['reason'] . "</td>";
                        // echo "<td>" . $row['booking_timestamp'] . "</td>";
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
            </div>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function searchColumn(column) {
        var keyword = $('th[data-column="' + column + '"] .search-input').val().toLowerCase();
        var matchFound = false;

        // Iterate through table rows and hide or show rows based on the keyword
        $('tbody tr').each(function() {
            var rowText = $(this).find('td:eq(' + getColumnIndex(column) + ')').text().toLowerCase();
            if (rowText.includes(keyword)) {
                $(this).show();
                matchFound = true;
            } else {
                $(this).hide();
            }
        });

        // If no matches are found, display a "No result" message
        var columnHeader = $('th[data-column="' + column + '"]');
        $('.no-result-message').remove(); // Remove any existing "No result" message
        if (!matchFound) {
            var noResultMessage = $('<div class="no-result-message">No result</div>').css({
                'color': 'red', // Customize the style as needed
                'margin-top': '10px'
            });
            columnHeader.append(noResultMessage);
        }
    }


    function getColumnIndex(column) {
        var index = -1;
        $('th').each(function(i) {
            if ($(this).data('column') === column) {
                index = i;
                return false; // 跳出循环
            }
        });
        return index;
    }

    function showSearchInput(column) {
        // Hide other search input fields and buttons
        $('.search-input, .search-button, .search-cancel-button').hide();

        // Get the th element corresponding to the clicked column
        var columnHeader = $('th[data-column="' + column + '"]');

        // Create a new input field with a specified width
        var inputField = $('<input type="text" class="form-control search-input" placeholder="Search ' + column + '">').css({
            'width': '200px', // Set width to 200px, adjust as needed
            'display': 'inline-block', // Ensure the input field is displayed inline
            'margin-right': '10px' // Add some right margin
        });
        inputField.on('input', function() {
            searchColumn(column);
        });

        // Create a cancel button
        var cancelButton = $('<button class="btn btn-danger search-cancel-button">Cancel</button>').css({
            'display': 'inline-block'
        });
        cancelButton.click(function() {
            cancelSearch();
        });

        // Add the input field and cancel button to the column header
        columnHeader.append(inputField);
        columnHeader.append(cancelButton);

        // Set the input field to gain focus
        inputField.focus();
    }


    function cancelSearch() {
        // 隐藏所有搜索输入框和取消按钮
        $('.search-input, .search-cancel-button').remove();

        // 显示所有搜索按钮
        $('.search-button').show();

        // 恢复表格所有行的显示状态
        $('tbody tr').show();

        // Remove "No result" message if present
        $('.no-result-message').remove();
    }

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
