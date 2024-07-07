<?php
session_start();
include_once '../database/db.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['student_id'])) {
    header("Location: ../validation/login.php");
    exit;
}


// Fetch user details from the database
$usersession = $_SESSION['student_id'];


$query = "SELECT * FROM cancel_stafftime WHERE student_id = '$usersession' AND isRead = 0";
$result = mysqli_query($con, $query);

// Check if there are new unread bookings
if (mysqli_num_rows($result) > 0) {
    // Fetch all unread bookings
    $bookingDetails = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bookingDetails[] = $row;
    }
    $iconColor = 'red'; // Change the icon color to red
    mysqli_query($con, "UPDATE cancel_stafftime SET isRead = 1 WHERE student_id = '$usersession' AND isRead = 0");
} else {
    // No new unread bookings
    $bookingDetails = [];
    $iconColor = '#ccc'; // Set the default icon color
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Appointment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../javascript/scripts.js"></script>
<style>
      body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.header {
    margin-bottom: 30px;
    text-align: center;
    font-size: 24px;
    color: #343a40;
}

.form-check {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.form-check-input {
    margin-right: 15px;
    accent-color: #17a2b8;
}

.form-check-label {
    font-size: 18px;
    color: #495057;
    display: flex;
    align-items: center;
}

.icon {
    margin-right: 10px;
    color: #17a2b8;
}

.form-check:hover .form-check-label {
    color: #17a2b8;
}

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

    <!-- Page content-->
    <div class="container-fluid">

    <!-- <button onclick="window.location.href='../student/student_history_appointment.php'" class="btn btn-info">Appointment History</button>
    <button onclick="window.location.href='../student/cancel_staffreason.php'" class="btn btn-info">Cancel Appointment by Staff</button> -->
                
    <form method="GET" id="filterForm">
    <div class = "container">
    <h3 class ="header"> Book your consultation </h3>
        <div class="form-check">
            <input class="form-check-input" type="radio" id="own_subject_radio" name="label_selection" value="own_subject">
            <label class="form-check-label" for="own_subject_radio">
                <i class="icon fa fa-user-graduate"></i>
                Lecturer from your Programme/Subject
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" id="other_department_radio" name="label_selection" value="other_department">
            <label class="form-check-label" for="other_department_radio">
                <i class="icon fa fa-users"></i>
                Lecturer outside your Programme/Subject
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" id="service_department_radio" name="label_selection" value="service_department">
            <label class="form-check-label" for="service_department_radio">
                <i class="icon fa fa-building"></i>
                INTI International College Subange Service Center
            </label>
        </div>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<div class="card">
    <div id="own_subject_select" style="display: none;">
        <div class="card-body">
        <div class="table-responsive">
        <table id="own_subject_table" class="table table-sm table-bordered text-center">
        <input type="text" id="own_searchInput" placeholder="Search...">
        <button type="button" id="clearButton" class="clearButton btn btn-danger" data-div="own_subject_select">
            <i id="clearIcon" class="clearIcon fa fa-trash"></i>
        </button>
        <br><br>
            <thead class="table-dark">
                <tr>
                    <th>Staff Name</th>
                    <th>Subject Name</th>
                    <th>Subject ID</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class ="table-group-divider">
                <?php
                // Fetch staff based on subjects of the student
                $query = "
                SELECT s.staff_id, s.staff_name, subj.subject_name, subj.subject_id
                FROM student_subjects ss 
                JOIN subject subj ON ss.subject_id = subj.subject_id 
                JOIN staff s ON subj.staff_id = s.staff_id 
                WHERE ss.student_id ='$usersession'";
                
                $result = mysqli_query($con, $query);

                // Display options for selecting lecturer from own program/subject
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['staff_name'] . "</td>";
                    echo "<td>" . $row['subject_name'] . "</td>";
                    echo "<td>" . $row['subject_id'] . "</td>";
                    echo "<td>";
                    echo "<button type='button' class='btn btn-primary btn-sm' onclick='setSelectedStaffId(\"" . $row['staff_id'] . "\", \"own_subject\")'>Book</button>";

                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <input type="hidden" id="selected_own_staff_id" name="selected_own_staff_id" value="">
    </div> 
    </div> 
    </div> 
    </div> 
    
    <div class="card">
    <div id="other_department_select" style="display: none;">
        <div class="card-body">
        <div class="table-responsive">
            <input type="text" id="other_searchInput" placeholder="Search...">
            <button type="button" id="clearButton" class="clearButton btn btn-danger" data-div="other_department_select">
                <i id="clearIcon" class="clearIcon fa fa-trash"></i>
            </button>
            <br><br>
            <table id="other_department_table" class="table table-sm table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Staff Name</th>
                        <th>Subject Name</th>
                        <th>Subject ID</th>
                        <th>Department</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php
                    // Fetch student's department id
                    $dept_query = "SELECT department_id FROM student WHERE student_id = '$usersession'";
                    $dept_result = mysqli_query($con, $dept_query);
                    $student_dept = mysqli_fetch_assoc($dept_result)['department_id'];

                    // Fetch lecturers from other departments
                    $query = "
                    SELECT 
                        s.staff_id, 
                        s.staff_name, 
                        IFNULL(subj.subject_name, '-') as subject_name, 
                        IFNULL(subj.subject_id, '-') as subject_id,
                        d.department_name
                    FROM staff s 
                    LEFT JOIN subject subj ON s.staff_id = subj.staff_id
                    LEFT JOIN department d ON s.department_id = d.department_id
                    WHERE s.department_id != '$student_dept' 
                    OR s.staff_id NOT IN (
                        SELECT subj.staff_id 
                        FROM student_subjects ss 
                        JOIN subject subj ON ss.subject_id = subj.subject_id 
                        WHERE ss.student_id = '$usersession'
                    )";
                    // Debugging information
                    echo "<!-- SQL Query: " . $query . " -->";

                    $result = mysqli_query($con, $query);

                    // Check if query executed successfully
                    if (!$result) {
                        echo "<tr><td colspan='5'>Query failed: " . mysqli_error($con) . "</td></tr>";
                    } else {
                        // Display options for selecting lecturer from other departments
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['staff_name'] . "</td>";
                            echo "<td>" . $row['subject_name'] . "</td>";
                            echo "<td>" . $row['subject_id'] . "</td>";
                            echo "<td>" . $row['department_name'] . "</td>";
                            echo "<td>";
                            echo "<button type='button' class='btn btn-primary btn-sm' 
                            onclick='setSelectedStaffId(\"" . $row['staff_id'] . "\", \"other_department\")'>Book</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <input type="hidden" id="selected_other_staff_id" name="selected_other_staff_id" value="">
        </div>
    </div>
</div>
</div>

<div class="card">
    <div id="service_department_select" style="display: none;">
    <div class="card-body">
    <div class="table-responsive">
        <table id="service_department_table" class="table table-sm table-bordered text-center">
        <input type="text" id="service_searchInput" placeholder="Search...">
        <button type="button" id="clearButton" class="clearButton btn btn-danger" data-div="service_department_select">
            <i id="clearIcon" class="clearIcon fa fa-trash"></i>
        </button>
        <br><br>
            <thead class="table-dark">
                <tr>
                    <th>Staff Name</th>
                    <th>Service Information</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class ="table-group-divider"> 
                <?php
                // Fetch lecturers from other departments
                $query = "
                SELECT *
                FROM service_department sd 
                JOIN staff s ON sd.staff_id = s.staff_id ";
                // Debugging information
                echo "<!-- SQL Query: " . $query . " -->";
                                    
                $result = mysqli_query($con, $query);

                // Check if query executed successfully
                if (!$result) {
                    echo "<tr><td colspan='4'>Query failed: " . mysqli_error($con) . "</td></tr>";
                } else {
                // Display options for selecting lecturer from other departments
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['staff_name'] . "</td>";
                    echo "<td>" . $row['service_name'] . "</td>";
                    echo "<td>";
                     echo "<button type='button' class='btn btn-primary btn-sm' 
                    onclick='setSelectedStaffId(\"" . $row['staff_id'] . "\", \"service_department\")'>Book</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
                ?>
            </tbody>
        </table>
        <input type="hidden" id="selected_service_staff_id" name="selected_service_staff_id" value="">
    </div>
</div>
</div>
</div>
</form>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 默认隐藏两个选择框
        document.getElementById('own_subject_select').style.display = 'none';
        document.getElementById('other_department_select').style.display = 'none';
        document.getElementById('service_department_select').style.display = 'none';


        // 手动触发一次选择事件，显示默认选择的内容
        var defaultRadio = document.querySelector('input[name="label_selection"]:checked');
        if (defaultRadio) {
            var defaultRadioValue = defaultRadio.value;
            if (defaultRadioValue === 'own_subject') {
                document.getElementById('own_subject_select').style.display = 'block';
            } else if (defaultRadioValue === 'other_department') {
                document.getElementById('other_department_select').style.display = 'block';
            }else if(defaultRadioValue === 'service_department') {
                document.getElementById('service_department_select').style.display = 'block';
        }
    }
    });

    document.querySelectorAll('input[name="label_selection"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            document.getElementById('own_subject_select').style.display = (radio.value === 'own_subject') ? 'block' : 'none';
            document.getElementById('other_department_select').style.display = (radio.value === 'other_department') ? 'block' : 'none';
            document.getElementById('service_department_select').style.display = (radio.value === 'service_department') ? 'block' : 'none';

            document.getElementById('selected_own_staff_id').value = ''; // Reset own staff ID
            document.getElementById('selected_other_staff_id').value = ''; // Reset other staff ID
            document.getElementById('selected_service_staff_id').value = ''; // Reset other staff ID
        });
    });
    
    function setSelectedStaffId(staffId, type) {
    if (type === 'own_subject') {
        document.getElementById('selected_own_staff_id').value = staffId;
        document.getElementById('filterForm').action = 'student_filter_own_subject.php';
    } else if (type === 'other_department') {
        document.getElementById('selected_other_staff_id').value = staffId;
        document.getElementById('filterForm').action = 'student_filter_other_department.php';
    }else if (type === 'service_department') {
        document.getElementById('selected_service_staff_id').value = staffId;
        document.getElementById('filterForm').action = 'student_filter_service_department.php'; // 假设你有一个用于处理service_department的PHP文件
    }
    document.getElementById('filterForm').submit();
}
    
    function updateSelectedStaffId(radioValue, selectId, hiddenId) {
        var selectedStaffId = '';
        if (document.getElementById(radioValue).checked) {
            selectedStaffId = document.getElementById(selectId).value;
            document.getElementById(hiddenId).value = selectedStaffId;
            document.getElementById('filterForm').action = (radioValue === 'own_subject_radio') ? 'student_filter_own_subject.php' : 'student_filter_other_department.php';
        }
    }

    document.getElementById('filterForm').addEventListener('submit', (event) => {
        updateSelectedStaffId('own_subject_radio', 'own_subject', 'selected_own_staff_id');
        updateSelectedStaffId('other_department_radio', 'other_department', 'selected_other_staff_id');
        updateSelectedStaffId('service_department_radio', 'service_department', 'selected_service_staff_id');
    });
</script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
        $(document).ready(function() {
            $('#notification-icon').click(function() {
                $('#notification-icon').css('background-color', '#ccc');
                //$('#notification-form').submit();
            });
        });

        // 实时监听输入框的文本变化，触发搜索
        $('#own_searchInput').on('input', function() {
            searchTable($(this).val(), 'own_department_table');
        });

        $('#other_searchInput').on('input', function() {
            searchTable($(this).val(), 'other_department_table');
        });

        $('#service_searchInput').on('input', function() {
            searchTable($(this).val(), 'service_department_table');
        });

        // 搜索功能，根据输入的关键字过滤表格中的行
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

// 清除按钮点击事件处理程序
$('.clearButton').click(function(event) {
    event.preventDefault(); // 阻止默认行为

    // 获取清除按钮所在的 div 的 data-div 属性值
    var divId = $(this).data('div');
    // 找到对应的输入框并清空内容
    $('#' + divId + ' input[type="text"]').val('');
    // 触发对应的搜索
    searchTable('', divId.replace('_select', '_table'));
});




</script>



</body>
</html>
