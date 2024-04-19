<?php
require_once "../db.php";
require_once "../Student/StudentInfo.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['stud_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "LoginForStudent.html";</script>';
    exit();
}

$studentId = $_SESSION['stud_id'];

// Get student informations
$studentInfo = getStudentInfo($conn, $studentId);

// Get the leave applications for the student
$query = "SELECT * FROM leave_application WHERE stud_id = '$studentId'";
$result = mysqli_query($conn, $query);
$leaveApplications = mysqli_fetch_all($result, MYSQLI_ASSOC);


// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="SWE20004" />
    <meta name="keywords" content="HTML,CSS,Javascript" />
    <meta name="author" content="Eazy 4 Leave" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student View Of Leave Submitted</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="styles/style.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="text-center">
            <img class="img-fluid img-thumbnail" src="../images/INTI.jpg" alt="INTI Logo" width="200" />
        </div>
    </header>

    <div class="container">
        <h1>Welcome <?php echo $studentInfo['stud_name']; ?></h1>
        <p> Session: <?php echo $studentInfo['session']; ?> </p>
        <p> Programme: <?php echo $studentInfo['programme']; ?> </p>
        <p> Major: <?php echo $studentInfo['major']; ?> </p>
        <p> Semester: <?php echo $studentInfo['semester']; ?> </p>

        <div class="col-md-12 bg-light text-right">
            <form action="LeaveApplication.php" method="post">
                <input type="submit" class="btn btn-outline-warning" name="new" value="Apply for leave">
            </form><br>
            <form action="LoginForStudent.html" method="post">
                <input type="submit" class="btn btn-outline-warning" name="logout" value="logout">
            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th scope="col">Leave Reference</th>
                    <th scope="col">Subject Code</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">File/Evidence</th>
                    <th scope="col">Reason</th>
                    <th scope="col">Status</th>
                </tr>
                <?php foreach ($leaveApplications as $application): ?>
                    <?php
                    $bgColor = '';
                    switch ($application['lecturer_approval_status']) {
                        case 'Pending':
                            $bgColor = 'bg-warning';
                            break;
                        case 'Approved':
                            $bgColor = 'bg-success';
                            break;
                        case 'Rejected':
                            $bgColor = 'bg-danger';
                            break;
                    }
                    ?>
                    <tr scope="row" class="<?php echo $bgColor; ?>">
                        <td><?php echo $application['leave_ref']; ?></td>
                        <td><?php echo $application['subj_code']; ?></td>
                        <td><?php echo $application['startDate']; ?></td>
                        <td><?php echo $application['endDate']; ?></td>
                        <td>
                        <a href="../file/<?php echo $application['documents']; ?>" target="_blank">View Supporting Documents</a>
                        </td>
                        <td><?php echo $application['reason']; ?></td>
                        <td><?php echo $application['lecturer_approval_status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>