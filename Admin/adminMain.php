<?php
require_once "../db.php";
require_once "../Student/StudentInfo.php";

session_start();

if (!isset($_SESSION['Staff_id'])) 
{
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "LoginForStaff.html";</script>';
	session_destroy();
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="SWE20004" />
    <meta name="keywords" content="HTML,CSS,Javascript" />
    <meta name="author" content="Eazy 4 Leave" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

    <div class="col-md-12 bg-light text-right">
        <form action="addStaff.php" method="post">
            <input type="submit" name="new" value="Add Staff">
        </form>
        <form action="addStudent.php" method="post">
            <input type="submit" class="btn btn-outline-warning" name="logout" value="Add Student">
        </form>
        <form action="addSubject.php" method="post">
            <input type="submit" class="btn btn-outline-warning" name="logout" value="Add Subject">
        </form>
    </div>
</body>
</html>