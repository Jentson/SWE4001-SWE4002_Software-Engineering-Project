<?php
include_once "../validation/session.php";
include_once '../database/db.php';

$usersession = $_SESSION['Staff_id'];
$res = mysqli_query($con, "SELECT * FROM staff WHERE staff_id='" . $usersession . "'"); // Updated table name and added quotes
$userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);
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
    <!-- <link rel="stylesheet" href="../css/styles.css">
    <script src="../javascript/scripts.js"></script> -->
    <!-- Include your custom CSS file if any -->
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
<?php include '../index/staff_navbar.php'; ?>
<Br>
    <div class="card">
        <h4 class ="text-center"> Staff Cancel Student Booking History</h4>
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
        <thead class="table-dark">
            <tr>
                <th data-column="student_id">
                Student ID 
                    <button class="search-button" onclick="showSearchInput('student_id')" style="background-color:#212529; color: white;">🔍</button>
                </th>
                <th data-column="student_name">
                Student Name
                    <button class="search-button" onclick="showSearchInput('student_name')" style="background-color:#212529; color: white;">🔍</button>
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
                <th data-column="cancel_timestamp">
                Cancel Date and Time
                    <button class="search-button" onclick="showSearchInput('cancel_timestamp')" style="background-color:#212529; color: white;">🔍</button>
                </th>
                <th data-column="reason">
                Reason
                    <button class="search-button" onclick="showSearchInput('reason')" style="background-color:#212529; color: white;">🔍</button>
                </th>
            </tr>
        </thead>
            <tbody class = "table-group-divider">
                
<?php


$usersession = $_SESSION['Staff_id'];

// Initialize variables
$search_query = "";
$where_clause = "";

// Check if search query is present in the URL
if(isset($_GET['search_query'])) {
    $search_query = mysqli_real_escape_string($con, $_GET['search_query']);
    
    // Construct the WHERE clause based on search query
    $where_clause = "WHERE staff_id = '$usersession' AND (student_id LIKE '%$search_query%' OR schedule_date LIKE '%$search_query%' OR start_time LIKE '%$search_query%' OR end_time LIKE '%$search_query%' OR modal LIKE '%$search_query%' OR cancel_timestamp LIKE '%$search_query%' OR reason LIKE '%$search_query%')";
}

// Construct the SQL query
$query = "
    SELECT c.*, s.student_name
    FROM cancel_stafftime c
    JOIN student s ON c.student_id = s.student_id
    $where_clause
";
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
        echo "<td>" . $row['cancel_timestamp'] . "</td>";
        echo "<td>" . $row['reason'] . "</td>";
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

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript function to clear the search -->
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
        // 隐藏其他搜索输入框和按钮
        $('.search-input, .search-button').hide();

        // 获取所点击列对应的th元素
        var columnHeader = $('th[data-column="' + column + '"]');

        // 创建新的输入框
        var inputField = $('<input type="text" class="form-control search-input" placeholder="Search ' + column + '">');
        inputField.on('input', function() {
            searchColumn(column);
        });

        // 创建一个取消按钮
        var cancelButton = $('<button class="btn btn-secondary search-cancel-button">Cancel</button>');
        cancelButton.click(function() {
            cancelSearch();
        });

        // 将输入框和取消按钮添加到列头
        columnHeader.append(inputField);
        columnHeader.append(cancelButton);

        // 设置输入框获得焦点
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

        $('#searchInput').on('input', function() {
        searchTable($(this).val());
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
