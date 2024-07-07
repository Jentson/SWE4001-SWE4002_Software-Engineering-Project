<?php
session_start();
include_once '../database/dbconnect.php';
include '../validation/session.php'; 

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../javascript/scripts.js"></script>



    <style>
        .booked {
            color: red;
            font-weight: bold;
        }
    </style>


</head>
<body>
<div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <div class="border-end bg-white" id="sidebar-wrapper"style="width:245px">
                <div class="sidebar-heading border-bottom bg-light"style="padding:25.5px;">
                <img src="../images/inti_user_logo.png" width="25%" style="border-radius:50%">
                <?php echo substr($_SESSION['student_name'],0,13)  ?></div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="../student/student_dashboard.php">Dashboard</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="../student/student_history_appointment.php">Appointment History</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="../student/student_cancel_staffreason.php">Cancel Reason</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Settings</a>
                </div>
            </div>
            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->
                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom"style="padding:29px;">
                    <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">Toggle Menu</button>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                <li class="nav-item active"><a class="nav-link" href="../student/logout_student.php">Logout</a></li>
                                <!-- <li class="nav-item"><a class="nav-link" href="#!">Link</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="#!">Action</a>
                                        <a class="dropdown-item" href="#!">Another action</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#!">Something else here</a>
                                    </div>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="container-fluid">
    

    <input type="text" id="searchInput" placeholder="Search...">
        <i id="searchIcon" class="fa fa-search"></i>

    <table class="table">
<thead>
    <tr>
            <th data-column="time_id">
            Time ID 
            <button class="search-button" onclick="showSearchInput('time_id')">🔍</button>
        </th>
        <th data-column="staff_name">
            Lecturer Name 
            <button class="search-button" onclick="showSearchInput('staff_name')">🔍</button>
        </th>
        <th data-column="schedule_date">
            Schedule Date 
            <button class="search-button" onclick="showSearchInput('schedule_date')">🔍</button>
        </th>
        <th data-column="start_time">
            Start Time 
            <button class="search-button" onclick="showSearchInput('start_time')">🔍</button>
        </th>
        <th data-column="end_time">
            End Time 
            <button class="search-button" onclick="showSearchInput('end_time')">🔍</button>
        </th>
        <th data-column="modal">
            Model 
            <button class="search-button" onclick="showSearchInput('modal')">🔍</button>
        </th>
        <th data-column="cancel_timestamp">
            Cancel Date and Time 
            <button class="search-button" onclick="showSearchInput('cancel_timestamp')">🔍</button>
        </th>
        <th data-column="reason">
            Reason 
            <button class="search-button" onclick="showSearchInput('reason')">🔍</button>
        </th>
    </tr>
</thead>
        <tbody>
            <?php
                $query = "SELECT sb.*, cs.*, cs.reason, st.staff_name
                FROM student_bookings sb
                LEFT JOIN cancel_stafftime cs ON sb.time_id = cs.time_id
                LEFT JOIN staff st ON cs.staff_id = st.staff_id
                WHERE sb.student_id = '{$_SESSION['student_id']}' 
                AND sb.status = 'booked' 
                AND sb.time_id IS NOT NULL
                AND sb.time_id = cs.time_id
                GROUP BY sb.time_id
                ORDER BY sb.booking_id DESC";

      $result = mysqli_query($con, $query);
      while ($row = mysqli_fetch_assoc($result)) {
          // Check if there is a corresponding record in the cancel_stafftime table for this time_id
          $disableCancel = !empty($row['reason']); // If there's a reason, disable the cancel button
      
          echo "<tr>";
          echo "<td>{$row['time_id']}</td>"; // Display time_id instead of booking_id
          echo "<td>{$row['staff_name']}</td>";
          echo "<td>{$row['schedule_date']}</td>";
          echo "<td>{$row['start_time']}</td>";
          echo "<td>{$row['end_time']}</td>";
          echo "<td>{$row['modal']}</td>";
          echo "<td>{$row['cancel_timestamp']}</td>";
          if ($disableCancel) {
              echo "<td>{$row['reason']}</td>"; // Display reason from cancel_stafftime table
              
          } else {
              echo "<td> Do not have reason.</td>"; // Empty cell if no reason is present
          }
          echo "</tr>";
      }

?>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    function searchColumn(column) {
        var keyword = $('th[data-column="' + column + '"] .search-input').val().toLowerCase();
        // 遍历表格行并根据关键字隐藏或显示行
        $('tbody tr').each(function() {
            var rowText = $(this).find('td:eq(' + getColumnIndex(column) + ')').text().toLowerCase();
            if (rowText.includes(keyword)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
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
    }

    // 实时监听输入框的文本变化，触发搜索
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
</div>
</body>
</html>