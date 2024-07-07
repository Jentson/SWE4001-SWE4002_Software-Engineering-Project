<?php
require_once "../database/dbconnect.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login as admin first!");</script>';
    echo '<script>window.location.href = "../staff/LoginForStaff.php";</script>';
    exit();
}

// Get admin ID from session
$adminId = $_SESSION['Staff_id'];

// Fetch pending student accounts
$query = "SELECT * FROM student WHERE status_id = '1'";
$result = mysqli_query($con, $query);

// Fetch all rows as an associative array
$pendingStudents = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="SWE20004" />
    <meta name="keywords" content="HTML,CSS,Javascript" />
    <meta name="author" content="Eazy 4 Leave" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Student Accounts</title>
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
        <h2>Pending Student Accounts</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Student ID</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingStudents as $student): ?>
                    <tr>
                        <td><?php echo $student['student_id']; ?></td>
                        <td><?php echo $student['student_name']; ?></td>
                        <td><?php echo $student['student_email']; ?></td>
                        <td>
                            <a href="approve_student.php?id=<?php echo $student['student_id']; ?>" class="btn btn-success">Approve</a>
                            <a href="reject_student.php?id=<?php echo $student['student_id']; ?>" class="btn btn-danger">Reject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
