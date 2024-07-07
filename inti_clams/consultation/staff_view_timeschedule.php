<?php
// Include session and database connection
include_once "../validation/session.php";
include_once '../database/db.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['Staff_id'])) {
    header("Location: ../validation/login.php");
    exit;
}

$usersession = $_SESSION['Staff_id']; // Updated variable name

$res = mysqli_query($con, "SELECT * FROM staff WHERE staff_id='" . $usersession . "'"); // Updated table name and added quotes
$userRow = mysqli_fetch_array($res, MYSQLI_ASSOC);
function getStatusClass($book_avail) {
    switch ($book_avail) {
        case 'available':
            return 'green';
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
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
 
<!-- Navbar -->
<?php include '../index/staff_navbar.php'; ?>
<Br>
<div class="card">
    <h2 class="text-center">Consultation Meeting Times</h2>
    <div class="card-body">
    <div class="table-responsive">
    <div class="panel panel-default">
        <div class="text-center">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr class="filters">
                        <th data-column="schedule_date">
                            <input type="text" class="form-control search-input" placeholder="Search Schedule Date" style="display: none;">
                            Schedule Date
                            <button class="search-button" onclick="showSearchInput('schedule_date')" style="background-color:#212529; color: white;">🔍</button>
                        </th>
                        <th data-column="schedule_day">
                           
                            <input type="text" class="form-control search-input" placeholder="Search Schedule Day" style="display: none;">
                            Schedule Day
                            <button class="search-button" onclick="showSearchInput('schedule_day')" style="background-color:#212529; color: white;">🔍</button>
                        </th>
                        <th data-column="start_time">
                            
                            <input type="text" class="form-control search-input" placeholder="Search Start Time" style="display: none;">
                            Start Time
                            <button class="search-button" onclick="showSearchInput('start_time')" style="background-color:#212529; color: white;">🔍</button>
                        </th>
                        <th data-column="end_time">
                            
                            <input type="text" class="form-control search-input" placeholder="Search End Time" style="display: none;">
                            End Time
                            <button class="search-button" onclick="showSearchInput('end_time')" style="background-color:#212529; color: white;">🔍</button>
                        </th>
                        <th data-column="book_avail">
                            
                            <input type="text" class="form-control search-input" placeholder="Search Book Available" style="display: none;">
                            Book Available
                            <button class="search-button" onclick="showSearchInput('book_avail')" style="background-color:#212529; color: white;">🔍</button>
                        </th>
                        <th data-column="modal">
                            
                            <input type="text" class="form-control search-input" placeholder="Search Modal" style="display: none;">
                            Model
                            <button class="search-button" onclick="showSearchInput('modal')" style="background-color:#212529; color: white;">🔍</button>
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $staff_id = $_SESSION['Staff_id'];
                    $query_select = "SELECT ts.*, st.staff_name 
                    FROM staff_timeschedule ts
                    JOIN staff st ON ts.staff_id = st.staff_id
                    WHERE ts.staff_id = '$staff_id' AND ts.book_avail != 'cancel' AND ts.book_avail != 'booked'";
                    $result_select = mysqli_query($con, $query_select);
                    
                    while ($staff_timeschedule = mysqli_fetch_array($result_select)) {
                        echo "<tr>";
                        echo "<td>" . $staff_timeschedule['schedule_date'] . "</td>";
                        echo "<td>" . $staff_timeschedule['schedule_day'] . "</td>";
                        echo "<td>" . $staff_timeschedule['start_time'] . "</td>";
                        echo "<td>" . $staff_timeschedule['end_time'] . "</td>";
                        // echo "<td>" . $staff_timeschedule['book_avail'] . "</td>";
                        echo '<td><div class="status-box ' . getStatusClass($staff_timeschedule['book_avail']) . '">' . $staff_timeschedule['book_avail'] . '</div></td>';
                        echo "<td>" . $staff_timeschedule['modal'] . "</td>";
                        echo "<td class='text-center'>";
                        echo "<form method='POST' action='staff_deleteschedule.php'>";
                        echo "<input type='hidden' name='time_id' value='".$staff_timeschedule['time_id']."' />";
                        echo "<button type='submit' class='delete btn btn-link'><i class='fa fa-trash'></i></button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
</script>
</body>
</html>
