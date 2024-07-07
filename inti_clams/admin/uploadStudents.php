<?php
require_once "../database/db.php";

session_start();

if (!isset($_SESSION['Staff_id'])) {
    echo '<script>alert("You need to login first!");</script>';
    echo '<script>window.location.href = "../intizh/login.php";</script>';
    session_destroy();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV File</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
        <!-- Images -->
        <div class="row justify-content-center">
            <a href="../admin/adminMain.php">
                <img src="../images/Inti_X_IICS-CLAMS.png" class="img-fluid rounded mx-auto d-block" alt="INTI logo"><br>
            </a>
        </div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
        <div class="d-flex justify-content-between">
            <a href="adminmain.php" class="btn btn-secondary ms-auto mb-2">Back</a>
        </div>
            <div class="card shadow">
                <div class="card-header text-center">
                    <h2>Upload CSV File</h2>
                </div>
                <div class="card-body">
                    <form action="uploadStudent.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="file" class="form-label">Select CSV file to upload:</label>
                            <input type="file" class="form-control" name="file" id="file" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" name="submit">Upload CSV</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
